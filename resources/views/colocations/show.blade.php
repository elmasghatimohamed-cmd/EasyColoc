@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Colocation : {{ $colocation->name }}
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-4">Colocation: {{ $colocation->name }}</h1>

        <div class="mb-8">
            <h2 class="text-xl font-semibold">Membres</h2>
            <ul class="mt-2 space-y-1">
                @foreach($colocation->users as $user)
                    <li class="flex justify-between">
                        <span>{{ $user->name }} {{ $user->pivot->role === 'owner' ? '(owner)' : '' }}</span>
                        <span class="text-sm text-gray-500">Rép: {{ $user->reputation }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        @if(auth()->id() === $colocation->owner()->first()->id)
            <div class="mb-8">
                <h2 class="text-xl font-semibold">Inviter un membre</h2>
                <form action="{{ route('invitations.store') }}" method="POST" class="mt-2 space-y-4">
                    @csrf
                    <input type="hidden" name="colocation_id" value="{{ $colocation->id }}">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Envoyer
                            l'invitation</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-xl font-semibold">Dépenses</h2>
            <a href="{{ route('expenses.index') }}" class="text-indigo-600 hover:underline">Voir toutes les dépenses</a>
            <a href="{{ route('expenses.create') }}" class="ml-4 text-green-600 hover:underline">Ajouter une dépense</a>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold">Catégories</h2>
            <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:underline">Gérer les catégories</a>
        </div>

    </div>
@endsection