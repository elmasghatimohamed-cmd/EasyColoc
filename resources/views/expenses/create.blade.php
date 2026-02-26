<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter une dépense
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Ajouter une dépense</h1>
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Titre</label>
                <input name="title" value="{{ old('title') }}" class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Montant</label>
                <input name="amount" type="number" step="0.01" required value="{{ old('amount') }}"
                    class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input name="date" type="date" required value="{{ old('date', now()->toDateString()) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">-- Aucune --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes"
                    class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
            </div>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Enregistrer</button>
        </form>
    </div>
</x-app-layout>