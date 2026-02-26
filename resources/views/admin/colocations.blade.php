@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-home"></i>
                Gestion des Colocations
            </h3>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <input type="text" 
                           placeholder="Rechercher une colocation..." 
                           class="form-control" 
                           style="width: 300px;">
                    <select class="form-control" style="width: 150px;">
                        <option value="">Toutes</option>
                        <option value="active">Actives</option>
                        <option value="cancelled">Annulées</option>
                    </select>
                </div>
                <div class="text-muted">
                    {{ $colocations->total() }} colocation(s)
                </div>
            </div>

            <!-- Colocations Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Colocation</th>
                            <th>Propriétaire</th>
                            <th>Membres</th>
                            <th>Dépenses</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colocations as $colocation)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-home"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $colocation->name }}</div>
                                            <div class="text-xs text-muted">Token: {{ substr($colocation->invite_token, 0, 8) }}...</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $owner = $colocation->owner()->first();
                                    @endphp
                                    @if($owner)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr($owner->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-sm">{{ $owner->name }}</div>
                                                <div class="text-xs text-muted">{{ $owner->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted text-sm">Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">{{ $colocation->users_count }}</span>
                                        <span class="text-xs text-muted">membres</span>
                                    </div>
                                    <div class="text-xs text-muted">
                                        {{ $colocation->users()->wherePivot('left_at', null)->count() }} actifs
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">{{ $colocation->expenses_count }}</span>
                                        <span class="text-xs text-muted">dépenses</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold text-primary">€{{ number_format($colocation->expenses()->sum('amount'), 2) }}</div>
                                    <div class="text-xs text-muted">
                                        Moyenne: €{{ number_format($colocation->expenses()->avg('amount') ?? 0, 2) }}
                                    </div>
                                </td>
                                <td>
                                    @if($colocation->status === 'active')
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                            <i class="fas fa-times-circle mr-1"></i>Annulée
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm">{{ $colocation->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-muted">{{ $colocation->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('colocations.show', $colocation) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($colocation->status === 'active')
                                            <form action="{{ route('colocations.cancel', $colocation) }}" method="POST" 
                                                  onsubmit="return confirm('Annuler cette colocation ?')" class="inline">
                                                @csrf @method('POST')
                                                <button type="submit" class="text-orange-600 hover:text-orange-800">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $colocations->links() }}
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-primary mb-2">{{ $colocations->sum('expenses_count') }}</div>
                <div class="text-sm text-muted">Total des dépenses</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600 mb-2">{{ $colocations->where('status', 'active')->count() }}</div>
                <div class="text-sm text-muted">Colocations actives</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-orange-600 mb-2">{{ $colocations->sum('users_count') }}</div>
                <div class="text-sm text-muted">Total des membres</div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600 mb-2">€{{ number_format($colocations->sum(function($c) { return $c->expenses()->sum('amount'); }), 2) }}</div>
                <div class="text-sm text-muted">Volume total</div>
            </div>
        </div>
    </div>
@endsection
