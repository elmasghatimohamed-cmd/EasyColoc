<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;

class AdminController extends Controller
{
    public function stats()
    {
        $stats = [
            'users_count' => User::count(),
            'active_users_count' => User::where('is_banned', false)->count(),
            'banned_users_count' => User::where('is_banned', true)->count(),
            'global_admins_count' => User::where('is_global_admin', true)->count(),
            'colocations_count' => Colocation::count(),
            'active_colocations_count' => Colocation::where('status', 'active')->count(),
            'cancelled_colocations_count' => Colocation::where('status', 'cancelled')->count(),
            'expenses_count' => Expense::count(),
            'total_expenses_amount' => Expense::sum('amount'),
            'this_month_expenses' => Expense::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('amount'),
            'avg_reputation' => User::avg('reputation') ?? 0,
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_colocations = Colocation::with('owner')->latest()->take(5)->get();
        $recent_expenses = Expense::with('payer', 'colocation')->latest()->take(10)->get();

        return view('admin.stats', compact('stats', 'recent_users', 'recent_colocations', 'recent_expenses'));
    }

    public function users()
    {
        $users = User::withCount(['colocations', 'expenses'])
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->is_global_admin) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas bannir un administrateur global.']);
        }

        $user->update(['is_banned' => true]);

        foreach ($user->colocations()->wherePivot('role', 'owner')->get() as $colocation) {
            $colocation->update(['status' => 'cancelled']);
            $colocation->users()->updateExistingPivot($user->id, ['left_at' => now()]);
        }

        return back()->with('success', 'Utilisateur banni avec succès.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', 'Utilisateur débanni avec succès.');
    }

    public function colocations()
    {
        $colocations = Colocation::with(['users', 'expenses'])
            ->withCount('users', 'expenses')
            ->latest()
            ->paginate(15);

        return view('admin.colocations', compact('colocations'));
    }

    public function expenses()
    {
        $expenses = Expense::with(['payer', 'colocation', 'category'])
            ->latest()
            ->paginate(20);

        return view('admin.expenses', compact('expenses'));
    }
}
