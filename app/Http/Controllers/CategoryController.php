<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected function currentColocation()
    {
        return auth()->user()->colocations()->wherePivot('left_at', null)->firstOrFail();
    }

    public function index()
    {
        $coloc = $this->currentColocation();
        $categories = $coloc->categories()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $coloc = $this->currentColocation();
        $coloc->categories()->create($request->only('name', 'description'));
        return redirect()->route('categories.index')->with('success', 'Catégorie ajoutée');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->only('name', 'description'));
        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée');
    }
}
