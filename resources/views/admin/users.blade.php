@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users"></i>
                Gestion des Utilisateurs
            </h3>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <input type="text" 
                           placeholder="Rechercher un utilisateur..." 
                           class="form-control" 
                           style="width: 300px;">
                    <select class="form-control" style="width: 150px;">
                        <option value="">Tous</option>
                        <option value="active">Actifs</option>
                        <option value="banned">Bannis</option>
                        <option value="admin">Admins</option>
                    </select>
                </div>
                <div class="text-muted">
                    {{ $users->total() }} utilisateur(s)
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Réputation</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @if($user->is_global_admin)
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Admin Global</span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Utilisateur</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold {{ $user->reputation >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $user->reputation >= 0 ? '+' : '' }}{{ $user->reputation }}
                                        </span>
                                        <div class="w-12 bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full" 
                                                 style="width: {{ min(100, max(0, ($user->reputation + 10) * 5)) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_banned)
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Banni</span>
                                    @else
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Actif</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @if(!$user->is_global_admin)
                                            @if($user->is_banned)
                                                <form action="{{ route('admin.unban', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-unlock"></i>
                                                        Débannir
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.ban', $user) }}" method="POST" 
                                                      onsubmit="return confirm('Bannir cet utilisateur ?')" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-ban"></i>
                                                        Bannir
                                                    </button>
                                                </form>
                                            @endif
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
