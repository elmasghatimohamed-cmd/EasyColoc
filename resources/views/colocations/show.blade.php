@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Colocation : {{ $colocation->name }}
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="card mb-6">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">Colocation: {{ $colocation->name }}</h1>
                        <div class="text-sm text-muted">Gérez les membres, invitations, dépenses et catégories</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="card mb-6" style="border-color: rgba(80, 205, 137, 0.35);">
                <div class="card-body" style="background: rgba(80, 205, 137, 0.06);">
                    <div class="flex items-center" style="color: #0f5132;">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="card mb-6" style="border-color: rgba(241, 65, 108, 0.35);">
                <div class="card-body" style="background: rgba(241, 65, 108, 0.06);">
                    <div class="font-semibold mb-2" style="color: #842029;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Erreurs
                    </div>
                    <ul class="list-disc pl-5" style="color: #842029;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $owner = $colocation->owner()->first();
            $isOwner = $owner && auth()->id() === $owner->id;
            $currentMembership = $colocation->users->firstWhere('id', auth()->id());
        @endphp

        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Membres
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($colocation->users as $user)
                        <div class="flex items-center justify-between py-2" style="border-bottom: 1px solid var(--gray-100);">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">
                                        {{ $user->name }} {{ $user->pivot->role === 'owner' ? '(owner)' : '' }}
                                    </div>
                                    <div class="text-xs {{ $user->pivot->left_at ? 'text-muted' : 'text-green-600' }}">
                                        {{ $user->pivot->left_at ? 'inactif' : 'actif' }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-sm text-muted">Rep: {{ $user->reputation }}</span>
                                @if($isOwner && !$user->pivot->left_at && $user->pivot->role !== 'owner')
                                    <form method="POST" action="{{ route('colocations.members.remove', [$colocation, $user]) }}" onsubmit="return confirm('Retirer ce membre de la colocation ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-user-minus"></i>
                                            Retirer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($isOwner)
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus"></i>
                        Inviter un membre
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('invitations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">
                        <div class="form-group mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" required class="form-control">
                            @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i>
                                Envoyer l'invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="card mb-6" style="border-color: rgba(241, 65, 108, 0.25);">
            <div class="card-header" style="background: rgba(241, 65, 108, 0.06);">
                <h3 class="card-title" style="color: #842029;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Actions sensibles
                </h3>
            </div>
            <div class="card-body">
                @if($isOwner)
                    <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" onsubmit="return confirm('Annuler cette colocation ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i>
                            Annuler la colocation
                        </button>
                    </form>
                @elseif($currentMembership && !$currentMembership->pivot->left_at)
                    <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Quitter cette colocation ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            Quitter la colocation
                        </button>
                    </form>
                @else
                    <div class="text-muted">Aucune action sensible disponible.</div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i>
                        Dépenses
                    </h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list"></i>
                            Voir toutes les dépenses
                        </a>
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            Ajouter une dépense
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tags"></i>
                        Catégories
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-cog"></i>
                        Gérer les catégories
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection
