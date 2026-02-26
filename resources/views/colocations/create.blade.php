@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus"></i>
                    Créer une nouvelle colocation
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('colocations.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag mr-2"></i>Nom de la colocation
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               placeholder="Ex: Appartement Paris 15ème"
                               class="form-control"
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="mt-2 flex items-center text-red-600 text-sm">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Features Preview -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                        <h4 class="font-semibold text-gray-800 mb-4">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                            Ce que vous pourrez faire :
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Ajouter des dépenses partagées
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Inviter des membres par email
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Calculer automatiquement les soldes
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                Suivre les remboursements
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-full">
                            <i class="fas fa-rocket mr-2"></i>
                            Créer ma colocation
                        </button>
                    </div>
                </form>

                <!-- Alternative -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Vous avez déjà une invitation ? 
                        <a href="{{ route('invitations.index') }}" class="text-primary font-semibold hover:text-primary-dark transition-colors">
                            Rejoindre une colocation
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection