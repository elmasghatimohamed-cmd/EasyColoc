<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $active = $user->colocations()->wherePivot('left_at', null)->first();
        return view('dashboard', compact('active'));
    }
}
