<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $coloc = auth()->user()->colocations()->wherePivot('left_at', null)->firstOrFail();

        $coloc->settlements()->create([
            'from_user_id' => $request->from_user_id,
            'to_user_id' => $request->to_user_id,
            'amount' => $request->amount,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Paiement enregistré');
    }
}
