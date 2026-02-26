@extends('layouts.app')

@section('content')
    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $stats['users_count'] }}</div>
            <div class="stat-label">Total Utilisateurs</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-home"></i>
            </div>
            <div class="stat-value">{{ $stats['active_colocations_count'] }}</div>
            <div class="stat-label">Colocations Actives</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon primary" style="background: linear-gradient(135deg, #f59e0b, #f97316);">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-value">€{{ number_format($stats['total_expenses_amount'], 2) }}</div>
            <div class="stat-label">Total Dépenses</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-value">€{{ number_format($stats['this_month_expenses'], 2) }}</div>
            <div class="stat-label">Dépenses ce mois</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Users Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Statistiques Utilisateurs
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_users_count'] }}</div>
                        <div class="text-sm text-gray-600">Actifs</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['banned_users_count'] }}</div>
                        <div class="text-sm text-gray-600">Bannis</div>
                    </div>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <div class="text-2xl font-bold text-indigo-600">{{ $stats['global_admins_count'] }}</div>
                    <div class="text-sm text-gray-600">Admins Globaux</div>
                </div>
            </div>
        </div>

        <!-- Colocations Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-home"></i>
                    Statistiques Colocations
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_colocations_count'] }}</div>
                        <div class="text-sm text-gray-600">Actives</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-600">{{ $stats['cancelled_colocations_count'] }}</div>
                        <div class="text-sm text-gray-600">Annulées</div>
                    </div>
                </div>
                <div class="mt-4 text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['expenses_count'] }}</div>
                    <div class="text-sm text-gray-600">Total Dépenses</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-plus"></i>
                    Utilisateurs Récents
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($recent_users as $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $user->name }}</div>
                                    <div class="text-xs text-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($user->is_global_admin)
                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Admin</span>
                                @elseif($user->is_banned)
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Banni</span>
                                @else
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Actif</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm w-full">
                        <i class="fas fa-users"></i>
                        Voir tous les utilisateurs
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Colocations -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-home"></i>
                    Colocations Récentes
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($recent_colocations as $colocation)
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $colocation->name }}</div>
                                <div class="text-xs text-muted">
                                    {{ $colocation->owner->first()->name ?? 'N/A' }} • 
                                    {{ $colocation->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="text-right">
                                @if($colocation->status === 'active')
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Active</span>
                                @else
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Annulée</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('admin.colocations') }}" class="btn btn-secondary btn-sm w-full">
                        <i class="fas fa-home"></i>
                        Voir toutes les colocations
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt"></i>
                    Dépenses Récentes
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($recent_expenses as $expense)
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-sm">{{ $expense->title }}</div>
                                <div class="text-xs text-muted">
                                    {{ $expense->payer->name }} • 
                                    {{ $expense->colocation->name }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-sm">€{{ number_format($expense->amount, 2) }}</div>
                                <div class="text-xs text-muted">{{ $expense->date->format('d/m') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('admin.expenses') }}" class="btn btn-secondary btn-sm w-full">
                        <i class="fas fa-receipt"></i>
                        Voir toutes les dépenses
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reputation Overview -->
    <div class="mt-6">
        <div class="card dark-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i>
                    Aperçu Réputation Globale
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <div class="text-3xl font-bold mb-2">
                            {{ number_format($stats['avg_reputation'], 1) }}
                        </div>
                        <div class="text-sm opacity-75">Réputation Moyenne</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2 text-green-400">
                            {{ $stats['active_users_count'] }}
                        </div>
                        <div class="text-sm opacity-75">Utilisateurs Actifs</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2 text-yellow-400">
                            {{ $stats['expenses_count'] }}
                        </div>
                        <div class="text-sm opacity-75">Dépenses Totales</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
