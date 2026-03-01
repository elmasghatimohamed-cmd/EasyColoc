<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use App\Mail\ColocationInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{

    public function index()
    {
        return view('invitations.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        return redirect()->route('invitations.show', $request->token);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'colocation_id' => 'required|exists:colocations,id',
        ]);

        $colocation = Colocation::findOrFail($request->colocation_id);

        // only owner can invite
        if (auth()->id() !== $colocation->owner()->first()->id) {
            abort(403);
        }

        // user cannot already have an active colocation
        // we will check at acceptance as well

        $inv = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => Str::random(40),
        ]);

        // Envoi de l'email d'invitation
        Mail::to($inv->email)->send(new ColocationInvitationMail($inv));

        return back()->with('success', 'Invitation envoyee par email.');
    }

    public function show($token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        if (!$this->emailsMatch(auth()->user()->email, $inv->email)) {
            abort(403, 'Forbidden invitation.');
        }

        return view('invitations.show', compact('inv'));
    }

    public function accept(Request $request, $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        if (!$this->emailsMatch(auth()->user()->email, $inv->email)) {
            abort(403, 'Forbidden invitation.');
        }

        if (auth()->user()->hasActiveColocation()) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Vous avez deja une colocation active.']);
        }

        $inv->update(['status' => 'accepted', 'accepted_at' => now()]);
        $inv->colocation->users()->attach(auth()->id(), ['role' => 'member', 'joined_at' => now()]);

        return redirect()->route('colocations.show', $inv->colocation);
    }

    public function decline(Request $request, $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();
        $inv->update(['status' => 'declined']);
        return redirect()->route('dashboard')->with('status', 'Invitation refusee');
    }

    private function emailsMatch(string $authenticatedEmail, string $invitedEmail): bool
    {
        return strtolower(trim($authenticatedEmail)) === strtolower(trim($invitedEmail));
    }
}
