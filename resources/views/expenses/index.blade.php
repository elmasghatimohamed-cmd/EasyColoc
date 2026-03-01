@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-bold text-2xl text-gray-900">
            <i class="fas fa-receipt mr-3"></i>Dépenses
        </h2>
    </div>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Balance Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($balances as $balance)
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $balance['balance'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mr-3">
                                <i class="fas {{ $balance['balance'] >= 0 ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }}"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $balance['user']->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $balance['user']->pivot->role }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg {{ $balance['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                €{{ number_format(abs($balance['balance']), 2) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $balance['balance'] >= 0 ? 'Créditeur' : 'Débiteur' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span>A payé:</span>
                            <span>€{{ number_format($balance['total_paid'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Part:</span>
                            <span>€{{ number_format($balance['share'], 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Settlements Section -->
        @if(!empty($settlements))
            <div class="card p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-exchange-alt mr-2 text-indigo-600"></i>
                    Remboursements optimisés
                </h3>
                <div class="space-y-3">
                    @foreach($settlements as $settlement)
                        @php
                            $fromUser = $balances[$settlement['from_user_id']]['user'];
                            $toUser = $balances[$settlement['to_user_id']]['user'];
                        @endphp
                        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg border border-indigo-100">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-right text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $fromUser->name }} → {{ $toUser->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">Remboursement recommandé</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-indigo-600">€{{ number_format($settlement['amount'], 2) }}</p>
                                <form action="{{ route('settlements.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="from_user_id" value="{{ $settlement['from_user_id'] }}">
                                    <input type="hidden" name="to_user_id" value="{{ $settlement['to_user_id'] }}">
                                    <input type="hidden" name="amount" value="{{ $settlement['amount'] }}">
                                    <button type="submit" class="text-sm bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition-colors">
                                        <i class="fas fa-check mr-1"></i>Marquer payé
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Expenses Table -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-indigo-600"></i>
                    Historique des dépenses
                </h3>
                <a href="{{ route('expenses.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Nouvelle dépense
                </a>
            </div>

            <!-- Filter -->
            <form method="GET" class="mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <label for="month" class="text-sm font-medium text-gray-700 mr-2">
                            <i class="fas fa-calendar mr-1"></i>Filtrer par mois:
                        </label>
                        <input type="month" 
                               name="month" 
                               id="month" 
                               value="{{ request('month') }}" 
                               class="input-modern">
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                    @if(request('month'))
                        <a href="{{ route('expenses.index') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-times mr-1"></i>Effacer
                        </a>
                    @endif
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Titre</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Payeur</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Catégorie</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="text-sm text-gray-600">{{ $expense->date->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $expense->title }}</p>
                                        @if($expense->description)
                                            <p class="text-sm text-gray-500">{{ Str::limit($expense->description, 50) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-indigo-600">€{{ number_format($expense->amount, 2) }}</span>
                                    <div class="text-xs text-gray-500">/personne: €{{ number_format($expense->amount_per_person, 2) }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-user text-indigo-600 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium">{{ $expense->payer->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($expense->category)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" 
                                              onsubmit="return confirm('Supprimer cette dépense ?')">
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

                @if($expenses->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-600 mb-4">Aucune dépense trouvée</p>
                        <a href="{{ route('expenses.create') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Ajouter une dépense
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection