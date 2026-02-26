@extends('layouts.app')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-value">{{ auth()->user()->reputation ?? 0 }}</div>
            <div class="stat-label">Mon score réputation</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-value">
                @if(isset($active))
                    €{{ number_format($active->expenses()->sum('amount'), 2) }}
                @else
                    0,00 €
                @endif
            </div>
            <div class="stat-label">Dépenses Globales ({{ now()->format('M') }})</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Expenses Table -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Dépenses récentes
                    </h3>
                </div>
                <div class="card-body">
                    @if(isset($active) && $active->expenses()->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>TITRE / CATÉGORIE</th>
                                        <th>PAYEUR</th>
                                        <th>MONTANT</th>
                                        <th>COLOC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($active->expenses()->latest()->take(5)->get() as $expense)
                                        <tr>
                                            <td>
                                                <div>
                                                    <div class="font-medium">{{ $expense->title }}</div>
                                                    <div class="text-sm text-muted">{{ $expense->category?->name ?? 'Non catégorisé' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs">
                                                        {{ strtoupper(substr($expense->payer->name, 0, 1)) }}
                                                    </div>
                                                    {{ $expense->payer->name }}
                                                </div>
                                            </td>
                                            <td class="font-semibold">€{{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->colocation->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-muted mb-4">Aucune dépense récente</div>
                            @if(isset($active))
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Ajouter une dépense
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Members Card -->
        <div class="lg:col-span-1">
            <div class="card dark-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Membres de la coloc
                    </h3>
                </div>
                <div class="card-body">
                    @if(isset($active))
                        <div class="space-y-3">
                            @foreach($active->activeUsers()->get() as $member)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-primary text-sm font-semibold">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $member->name }}</div>
                                            <div class="text-xs opacity-75">{{ $member->pivot->role }}</div>
                                        </div>
                                    </div>
                                    <div class="text-xs opacity-75">
                                        Rép: {{ $member->reputation ?? 0 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($active->owner()->first()->id === auth()->id())
                            <div class="mt-4 pt-4 border-t border-gray-700">
                                <button class="btn btn-secondary btn-sm w-full">
                                    <i class="fas fa-user-plus"></i>
                                    Inviter un membre
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <div class="text-muted mb-4">Aucune colocation active</div>
                            <a href="{{ route('colocation.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Nouvelle colocation
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reputation Widget -->
    <div class="mt-6">
        <div class="card dark-card" style="max-width: 300px;">
            <div class="card-body text-center">
                <div class="text-sm opacity-75 mb-2">VOTRE RÉPUTATION</div>
                <div class="text-2xl font-bold mb-2">
                    {{ auth()->user()->reputation >= 0 ? '+' : '' }}{{ auth()->user()->reputation ?? 0 }} points
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" 
                         style="width: {{ min(100, max(0, (auth()->user()->reputation + 10) * 5)) }}%"></div>
                </div>
            </div>
        </div>
    </div>
@endsection