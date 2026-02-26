<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $user->load(['colocations' => function($query) {
            $query->wherePivot('left_at', null)->with('users');
        }]);
        
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update basic info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
            
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        // Leave all active colocations
        foreach ($user->colocations()->wherePivot('left_at', null)->get() as $colocation) {
            if ($colocation->owner()->first()->id === $user->id) {
                // If owner, cancel the colocation
                $colocation->update(['status' => 'cancelled']);
                foreach ($colocation->users as $member) {
                    $colocation->users()->updateExistingPivot($member->id, ['left_at' => now()]);
                }
            } else {
                // If member, just leave
                $colocation->users()->updateExistingPivot($user->id, ['left_at' => now()]);
            }
        }

        // Delete user
        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}
