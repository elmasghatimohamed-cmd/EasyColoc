@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-receipt"></i>
                Gestion des Dépenses
            </h3>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <input type="text" 
                           placeholder="Rechercher une dépense..." 
                           class="form-control" 
                           style="width: 300px;">
                    <select class="form-control" style="width: 150px;">
                        <option value="">Toutes</option>
                        <option value="recent">Récentes</option>
                        <option value="high">Montant élevé</option>
                    </select>
                    <input type="month" class="form-control" style="width: 180px;">
                </div>
                <div class="text-muted">
                    {{ $expenses->total() }} dépense(s) • 
                    Total: €{{ number_format($expenses->sum('amount'), 2) }}
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dépense</th>
                            <th>Payeur</th>
                            <th>Colocation</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>
                                    <div>
                                        <div class="font-medium">{{ $expense->title }}</div>
                                        @if($expense->description)
                                            <div class="text-sm text-muted">{{ Str::limit($expense->description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ strtoupper(substr($expense->payer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $expense->payer->name }}</div>
                                            <div class="text-xs text-muted">{{ $expense->payer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-medium">{{ $expense->colocation->name }}</div>
                                        <div class="text-xs text-muted">{{ $expense->colocation->users->count() }} membres</div>
                                    </div>
                                </td>
                                <td>
                                    @if($expense->category)
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                            Non catégorisé
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm">{{ $expense->date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-muted">{{ $expense->date->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="font-semibold text-primary">€{{ number_format($expense->amount, 2) }}</div>
                                    @if($expense->amount_per_person)
                                        <div class="text-xs text-muted">/personne: €{{ number_format($expense->amount_per_person, 2) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('expenses.show', $expense) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" 
                                              onsubmit="return confirm('Supprimer cette dépense ?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-primary mb-2">{{ $expenses->sum('amount') }}</div>
                <div class="text-sm text-muted">Total des dépenses</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600 mb-2">{{ number_format($expenses->avg('amount'), 2) }}</div>
                <div class="text-sm text-muted">Moyenne par dépense</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-orange-600 mb-2">{{ $expenses->max('amount') ?? 0 }}</div>
                <div class="text-sm text-muted">Dépense la plus élevée</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600 mb-2">{{ $expenses->count() }}</div>
                <div class="text-sm text-muted">Nombre de dépenses</div>
            </div>
        </div>
    </div>
@endsection
