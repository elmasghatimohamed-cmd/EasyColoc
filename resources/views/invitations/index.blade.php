@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope"></i>
                    Rejoindre une Colocation
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('invitations.search') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-blue-500 rounded-full mb-4">
                            <i class="fas fa-envelope-open text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Rejoindre une colocation</h2>
                        <p class="text-gray-600">Entrez votre token d'invitation pour rejoindre une colocation existante</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="token" class="form-label">
                            <i class="fas fa-key mr-2"></i>Token d'invitation
                        </label>
                        <input type="text" 
                               name="token" 
                               id="token" 
                               required
                               placeholder="Entrez votre token d'invitation"
                               class="form-control">
                        <p class="text-sm text-gray-500 mt-2">
                            Le token vous a été envoyé par email par le propriétaire de la colocation
                        </p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Rejoindre la colocation
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t">
                    <div class="text-center">
                        <h4 class="font-semibold text-gray-900 mb-2">Pas encore de colocation ?</h4>
                        <p class="text-gray-600 mb-4">Créez votre propre colocation et invitez vos colocataires</p>
                        <a href="{{ route('colocation.create') }}" class="btn btn-secondary">
                            <i class="fas fa-plus mr-2"></i>
                            Créer une colocation
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection
