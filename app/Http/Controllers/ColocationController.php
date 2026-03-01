<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Str;

class ColocationController extends Controller
{

    public function index()
    {
        return view("colocations.create");
    }

    public function show(Colocation $colocation)
    {
        // ensure user is member
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


}
