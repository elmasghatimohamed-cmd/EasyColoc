@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                <i class="fas fa-plus-circle mr-2 text-indigo-600"></i>
                Ajouter une depense
            </h2>

            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="form-label">Titre</label>
                    <input
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-control"
                        required
                    >
                </div>

                <div>
                    <label for="amount" class="form-label">Montant</label>
                    <input
                        id="amount"
                        name="amount"
                        type="number"
                        step="0.01"
                        min="0.01"
                        value="{{ old('amount') }}"
                        class="form-control"
                        required
                    >
                </div>

                <div>
                    <label for="date" class="form-label">Date</label>
                    <input
                        id="date"
                        name="date"
                        type="date"
                        value="{{ old('date', now()->toDateString()) }}"
                        class="form-control"
                        required
                    >
                </div>

                <div>
                    <label for="category_id" class="form-label">Categorie</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">-- Aucune --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="4"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
