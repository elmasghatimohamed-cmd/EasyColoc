<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catégories
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Catégories</h1>
        <a href="{{ route('categories.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Nouvelle
            catégorie</a>
        <ul class="mt-4 space-y-2">
            @foreach($categories as $category)
                <li class="flex justify-between items-center">
                    <span>{{ $category->name }}</span>
                    <div class="space-x-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:underline">Éditer</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline"
                                onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>