@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Categories</h2>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>
                    Nouvelle categorie
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($categories->isEmpty())
                <p class="text-gray-500">Aucune categorie pour le moment.</p>
            @else
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <div class="flex items-center justify-between rounded border border-gray-200 p-3">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                                @if($category->description)
                                    <p class="text-sm text-gray-500">{{ $category->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm">
                                    Modifier
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Supprimer cette categorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
