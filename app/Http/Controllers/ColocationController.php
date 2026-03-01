<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{

    public function index()
    {
        return view("colocations.create");
    }

    public function show(Colocation $colocation)
    {
        $this->authorize('view', $colocation);
        $colocation->load('users', 'categories');
        return view('colocations.show', compact('colocation'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        if (auth()->user()->hasActiveColocation()) {
            return back()->withErrors(['error' => 'Vous faites déjà partie d’une colocation active.']);
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'invite_token' => Str::random(32),
            'status' => 'active',
        ]);

        $colocation->users()->attach(auth()->id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    public function update(Request $request, Colocation $colocation)
    {
        $this->authorize('update', $colocation);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|in:active,cancelled',
        ]);

        $colocation->update([
            'name' => $request->name,
            'status' => $request->status ?? $colocation->status,
        ]);

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', 'Colocation mise a jour avec succes.');
    }

    public function destroy(Colocation $colocation)
    {
        $this->authorize('delete', $colocation);

        $activeMembers = $colocation->users()
            ->wherePivotNull('left_at')
            ->get();

        foreach ($activeMembers as $member) {
            $colocation->users()->updateExistingPivot($member->id, ['left_at' => now()]);
        }

        $colocation->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Colocation supprimee avec succes.');
    }

    public function leave(Colocation $colocation)
    {
        $this->authorize('view', $colocation);

        $membership = $colocation->users()
            ->where('users.id', auth()->id())
            ->wherePivotNull('left_at')
            ->first();

        if (!$membership) {
            return back()->withErrors(['error' => 'Vous ne faites pas partie de cette colocation active.']);
        }

        if ($membership->pivot->role === 'owner') {
            return back()->withErrors(['error' => "L'owner ne peut pas quitter la colocation."]);
        }

        $balances = $colocation->calculateBalances();
        $userBalance = (float) ($balances[auth()->id()]['balance'] ?? 0);
        $currentUser = auth()->user();

        DB::transaction(function () use ($colocation, $userBalance, $currentUser) {
            if ($userBalance < -0.01) {
                $this->imputeMemberDebtToOwner($colocation, $currentUser->id);
            }

            $colocation->users()->updateExistingPivot($currentUser->id, ['left_at' => now()]);
            $currentUser->increment('reputation', $this->reputationDeltaFromBalance($userBalance));
        });

        return redirect()->route('dashboard')->with('success', 'Vous avez quitte la colocation.');
    }

    public function removeMember(Colocation $colocation, User $user)
    {
        $this->authorize('update', $colocation);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Utilisez le bouton annuler pour fermer votre colocation.']);
        }

        $membership = $colocation->users()
            ->where('users.id', $user->id)
            ->wherePivotNull('left_at')
            ->first();

        if (!$membership) {
            return back()->withErrors(['error' => 'Ce membre nest pas actif dans cette colocation.']);
        }

        if ($membership->pivot->role === 'owner') {
            return back()->withErrors(['error' => 'Impossible de retirer le owner.']);
        }

        $balances = $colocation->calculateBalances();
        $memberBalance = (float) ($balances[$user->id]['balance'] ?? 0);

        DB::transaction(function () use ($colocation, $memberBalance, $user) {
            if ($memberBalance < -0.01) {
                $this->imputeMemberDebtToOwner($colocation, $user->id);
            }

            $colocation->users()->updateExistingPivot($user->id, ['left_at' => now()]);
            $user->increment('reputation', $this->reputationDeltaFromBalance($memberBalance));
        });

        return back()->with('success', 'Membre retire avec succes.');
    }

    public function cancel(Colocation $colocation)
    {
        $this->authorize('update', $colocation);

        $balances = $colocation->calculateBalances();
        $colocation->update(['status' => 'cancelled']);

        $activeMembers = $colocation->users()
            ->wherePivotNull('left_at')
            ->get();

        foreach ($activeMembers as $member) {
            $memberBalance = (float) ($balances[$member->id]['balance'] ?? 0);
            $member->increment('reputation', $this->reputationDeltaFromBalance($memberBalance));
            $colocation->users()->updateExistingPivot($member->id, ['left_at' => now()]);
        }

        return redirect()->route('dashboard')->with('success', 'Colocation annulee avec succes.');
    }

    private function reputationDeltaFromBalance(float $balance): int
    {
        return $balance < -0.01 ? -1 : 1;
    }

    private function imputeMemberDebtToOwner(Colocation $colocation, int $memberId): void
    {
        $owner = $colocation->owner()->wherePivotNull('left_at')->first() ?? $colocation->owner()->first();

        if (!$owner || $owner->id === $memberId) {
            return;
        }

        $settlements = $colocation->calculateSettlements();

        foreach ($settlements as $settlement) {
            $fromUserId = (int) ($settlement['from_user_id'] ?? 0);
            $toUserId = (int) ($settlement['to_user_id'] ?? 0);
            $amount = (float) ($settlement['amount'] ?? 0);

            // Transfer only the leaving member's debt to the owner.
            if ($fromUserId !== $memberId || $amount <= 0.01) {
                continue;
            }

            // If the debt is owed directly to owner, owner absorbs it (no transfer entry needed).
            if ($toUserId === (int) $owner->id) {
                continue;
            }

            $colocation->settlements()->create([
                'from_user_id' => $owner->id,
                'to_user_id' => $toUserId,
                'amount' => $amount,
                'paid_at' => now(),
            ]);
        }
    }

}
