@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope"></i>
                    Invitation à rejoindre {{ $inv->colocation->name }}
                </h3>
            </div>
            <div class="card-body">
                <!-- Invitation Status -->
                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100">
                        <div>
                            <h4 class="font-semibold text-gray-900">Invitation envoyée à</h4>
                            <p class="text-sm text-gray-600">{{ $inv->email }}</p>
                        </div>
                        <div class="text-right">
                            @if($inv->status === 'pending')
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-semibold">
                                    <i class="fas fa-clock mr-1"></i>En attente
                                </span>
                            @elseif($inv->status === 'accepted')
                                <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Acceptée
                                </span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-800 px-3 py-1 rounded-full font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Refusée
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Token Display -->
                @if($inv->status === 'pending')
                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">
                                <i class="fas fa-key mr-2"></i>Votre token d'invitation
                            </h4>
                            <div class="flex items-center gap-2">
                                <code class="flex-1 bg-white border border-gray-300 rounded px-3 py-2 font-mono text-sm">
                                    {{ $inv->token }}
                                </code>
                                <button 
                                    onclick="copyToken('{{ $inv->token }}')" 
                                    class="btn btn-secondary btn-sm">
                                    <i class="fas fa-copy mr-1"></i>
                                    Copier
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Copiez ce token pour rejoindre la colocation
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Colocation Info -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">
                            <i class="fas fa-home mr-2"></i>Détails de la colocation
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Nom:</span>
                                <span class="font-medium">{{ $inv->colocation->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Propriétaire:</span>
                                <span class="font-medium">{{ $inv->colocation->owner()->first()->name ?? 'Non défini' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Membres:</span>
                                <span class="font-medium">{{ $inv->colocation->users()->wherePivot('left_at', null)->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Créée le:</span>
                                <span class="font-medium">{{ $inv->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($inv->status === 'pending')
                    <div class="flex items-center gap-4">
                        <form action="{{ route('invitations.accept', $inv->token) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-full">
                                <i class="fas fa-check mr-2"></i>
                                Accepter l'invitation
                            </button>
                        </form>
                        <form action="{{ route('invitations.decline', $inv->token) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg w-full">
                                <i class="fas fa-times mr-2"></i>
                                Refuser l'invitation
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8">
                        @if($inv->status === 'accepted')
                            <div class="text-green-600 mb-4">
                                <i class="fas fa-check-circle text-4xl mb-2"></i>
                                <h4 class="text-xl font-semibold text-green-600">Invitation acceptée !</h4>
                                <p class="text-gray-600">Vous avez rejoint la colocation {{ $inv->colocation->name }}</p>
                                <a href="{{ route('colocations.show', $inv->colocation) }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    Voir la colocation
                                </a>
                            </div>
                        @else
                            <div class="text-red-600 mb-4">
                                <i class="fas fa-times-circle text-4xl mb-2"></i>
                                <h4 class="text-xl font-semibold text-red-600">Invitation refusée</h4>
                                <p class="text-gray-600">Vous avez refusé l'invitation à rejoindre {{ $inv->colocation->name }}</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-home mr-2"></i>
                                    Retour au dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(function() {
                // Afficher une notification temporaire
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i>Copié !';
                button.classList.add('bg-green-600', 'text-white');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600', 'text-white');
                }, 2000);
            }).catch(function(err) {
                console.error('Impossible de copier le token: ', err);
            });
        }
    </script>
@endsection