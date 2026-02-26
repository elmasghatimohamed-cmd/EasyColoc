<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected function currentColocation()
    {
        return auth()->user()->colocations()->wherePivot('left_at', null)->firstOrFail();
    }

    public function index(Request $request)
    {
        $coloc = $this->currentColocation();
        $query = $coloc->expenses()->with('payer', 'category');

        if ($request->has('month')) {
            $query->whereMonth('date', $request->month);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $balances = $coloc->calculateBalances();
        $settlements = $coloc->calculateSettlements();
        
        return view('expenses.index', compact('expenses', 'coloc', 'balances', 'settlements'));
    }

    public function create()
    {
        $coloc = $this->currentColocation();
        $categories = $coloc->categories;
        return view('expenses.create', compact('categories', 'coloc'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $coloc = $this->currentColocation();
        
        $expense = $coloc->expenses()->create([
            'payer_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        // Ajouter tous les membres actifs à la dépense
        $activeUsers = $coloc->activeUsers()->pluck('users.id');
        $expense->users()->attach($activeUsers);

        return redirect()->route('expenses.index')->with('success', 'Dépense enregistrée avec succès');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        $expense->load('payer', 'category', 'users');
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $coloc = $this->currentColocation();
        $categories = $coloc->categories;
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $expense->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée');
    }
}
