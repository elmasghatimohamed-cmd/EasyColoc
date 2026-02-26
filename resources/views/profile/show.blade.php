@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                            <div class="flex items-center gap-3">
                                @if($user->is_global_admin)
                                    <span class="text-xs bg-purple-100 text-purple-800 px-3 py-1 rounded-full font-semibold">
                                        <i class="fas fa-shield-alt mr-1"></i>Admin Global
                                    </span>
                                @endif
                                @if($user->is_banned)
                                    <span class="text-xs bg-red-100 text-red-800 px-3 py-1 rounded-full font-semibold">
                                        <i class="fas fa-ban mr-1"></i>Banni
                                    </span>
                                @else
                                    <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                                        <i class="fas fa-check mr-1"></i>Actif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid mb-6">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $user->reputation ?? 0 }}</div>
                <div class="stat-label">Réputation</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-value">{{ $user->colocations->count() }}</div>
                <div class="stat-label">Colocations</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon primary" style="background: linear-gradient(135deg, #f59e0b, #f97316);">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-value">{{ $user->expenses->count() }}</div>
                <div class="stat-label">Dépenses</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-value">{{ $user->created_at->format('M Y') }}</div>
                <div class="stat-label">Inscription</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Active Colocation -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home"></i>
                        Colocation Active
                    </h3>
                </div>
                <div class="card-body">
                    @if($user->colocations->count() > 0)
                        @foreach($user->colocations as $colocation)
                            <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $colocation->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $colocation->users->count() }} membre(s) • 
                                            {{ $colocation->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                        {{ $colocation->pivot->role }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('colocations.show', $colocation) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    @if($colocation->pivot->role === 'owner')
                                        <a href="#" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-cog mr-1"></i>
                                            Gérer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-home text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 mb-4">Vous n'êtes pas encore membre d'une colocation</p>
                            <a href="{{ route('colocation.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>
                                Créer une colocation
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Activité Récente
                    </h3>
                </div>
                <div class="card-body">
                    @if($user->expenses->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->expenses()->latest()->take(5)->get() as $expense)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium">{{ $expense->title }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $expense->colocation->name }} • 
                                            {{ $expense->date->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-primary">€{{ number_format($expense->amount, 2) }}</div>
                                        <div class="text-xs text-gray-500">Payé</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm w-full">
                                <i class="fas fa-list mr-2"></i>
                                Voir toutes les dépenses
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600">Aucune dépense enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="mt-6">
            <div class="card dark-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i>
                        Paramètres du Compte
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user text-blue-400"></i>
                                <div>
                                    <div class="font-medium">Informations personnelles</div>
                                    <div class="text-xs opacity-75">Nom, email, photo</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        
                        <a href="{{ route('profile.edit') }}#password" class="flex items-center justify-between p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-lock text-green-400"></i>
                                <div>
                                    <div class="font-medium">Mot de passe</div>
                                    <div class="text-xs opacity-75">Sécurité du compte</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        
                        <a href="#" class="flex items-center justify-between p-4 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-bell text-yellow-400"></i>
                                <div>
                                    <div class="font-medium">Notifications</div>
                                    <div class="text-xs opacity-75">Préférences d'alerte</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        
                        <button onclick="confirmDeleteAccount()" class="flex items-center justify-between p-4 bg-gray-800 rounded-lg hover:bg-red-900 transition-colors w-full text-left">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-trash text-red-400"></i>
                                <div>
                                    <div class="font-medium">Supprimer le compte</div>
                                    <div class="text-xs opacity-75">Action irréversible</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteAccount() {
            if (confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et supprimera toutes vos données.')) {
                const password = prompt('Veuillez confirmer avec votre mot de passe:');
                if (password) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('profile.destroy') }}';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="password" value="${password}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
@endsection
