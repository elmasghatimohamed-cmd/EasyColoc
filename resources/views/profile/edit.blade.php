@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-edit"></i>
                    Modifier le Profil
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user mr-2 text-primary"></i>
                            Informations Personnelles
                        </h4>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   required
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control">
                            @error('name')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   required
                                   value="{{ old('email', $user->email) }}"
                                   class="form-control">
                            @error('email')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-t pt-6 space-y-4" id="password">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-lock mr-2 text-green-600"></i>
                            Modifier le Mot de Passe
                        </h4>
                        <p class="text-sm text-gray-600">Laissez vide si vous ne souhaitez pas modifier votre mot de passe</p>
                        
                        <div class="form-group">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password"
                                   class="form-control">
                            @error('current_password')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password"
                                   class="form-control">
                            @error('new_password')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation"
                                   class="form-control">
                            @error('new_password_confirmation')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="mt-6">
            <div class="card border-red-200">
                <div class="card-header bg-red-50">
                    <h3 class="card-title text-red-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Zone de Danger
                    </h3>
                </div>
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900">Supprimer le compte</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Cette action est irréversible et supprimera définitivement toutes vos données.
                            </p>
                        </div>
                        <button onclick="confirmDeleteAccount()" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer mon compte
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

        // Password strength indicator
        document.getElementById('new_password')?.addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthIndicator = document.getElementById('password-strength');
            
            if (!strengthIndicator) {
                const indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'mt-2';
                e.target.parentNode.appendChild(indicator);
            }
            
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            return strength;
        }

        function updatePasswordStrengthIndicator(strength) {
            const indicator = document.getElementById('password-strength');
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-lime-500', 'bg-green-500'];
            const labels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
            
            if (strength > 0) {
                indicator.innerHTML = `
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="${colors[strength - 1]} h-2 rounded-full transition-all" style="width: ${(strength * 20)}%"></div>
                        </div>
                        <span class="text-xs ${colors[strength - 1].replace('bg-', 'text-')}">${labels[strength - 1]}</span>
                    </div>
                `;
            } else {
                indicator.innerHTML = '';
            }
        }
    </script>
@endsection
