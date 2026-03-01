@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Nouvelle categorie</h2>

            @if ($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="form-label">Nom</label>
                    <input id="name" name="name" required value="{{ old('name') }}" class="form-control">
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
