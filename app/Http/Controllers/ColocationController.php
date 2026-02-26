<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\User;
use Illuminate\Support\Str;

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
        
        $request->validate(['name' => 'required|string|max:255']);
        
        $colocation->update(['name' => $request->name]);
        
        return back()->with('success', 'Colocation mise à jour.');
    }

    public function destroy(Colocation $colocation)
    {
        $this->authorize('delete', $colocation);
        
        $colocation->delete();
        
        return redirect()->route('dashboard')->with('success', 'Colocation supprimée.');
    }

    public function leave(Colocation $colocation)
    {
        $user = auth()->user();
        
        // Owner ne peut pas quitter, doit annuler
        if ($colocation->owner()->first()->id === $user->id) {
            return back()->withErrors(['error' => 'Le propriétaire ne peut pas quitter la colocation.']);
        }
        
        // Vérifier si l'utilisateur a des dettes
        $balance = $this->calculateUserBalance($user, $colocation);
        
        if ($balance < 0) { // L'utilisateur doit de l'argent
            $user->decrement('reputation');
        } else {
            $user->increment('reputation');
        }
        
        // Marquer comme parti
        $colocation->users()->updateExistingPivot($user->id, ['left_at' => now()]);
        
        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation.');
    }

    public function cancel(Colocation $colocation)
    {
        $this->authorize('delete', $colocation);
        
        $colocation->update(['status' => 'cancelled']);
        
        // Marquer tous les membres comme partis
        foreach ($colocation->users as $user) {
            $colocation->users()->updateExistingPivot($user->id, ['left_at' => now()]);
        }
        
        return redirect()->route('dashboard')->with('success', 'Colocation annulée.');
    }

    private function calculateUserBalance(User $user, Colocation $colocation)
    {
        $totalPaid = $colocation->expenses()
            ->where('payer_id', $user->id)
            ->sum('amount');
            
        $userShare = $colocation->expenses()
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->sum('amount') / $colocation->users()->wherePivot('left_at', null)->count();
            
        return $totalPaid - $userShare;
    }


}
