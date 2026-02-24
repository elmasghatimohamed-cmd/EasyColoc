<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Str;

class ColocationController extends Controller
{
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
}
