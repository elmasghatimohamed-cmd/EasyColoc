<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier la catégorie
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Modifier la catégorie</h1>
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input name="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description"
                    class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', $category->description) }}</textarea>
            </div>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Mettre à jour</button>
        </form>
    </div>
</x-app-layout>