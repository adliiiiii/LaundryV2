<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() { return view('categories.index', ['categories' => Category::withCount('items')->latest()->paginate(15)]); }
    public function create() { return view('categories.create', ['category' => new Category()]); }
    public function store(CategoryRequest $request) { Category::create($request->validated() + ['is_active' => $request->boolean('is_active')]); return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.'); }
    public function edit(Category $category) { return view('categories.edit', compact('category')); }
    public function update(CategoryRequest $request, Category $category) { $category->update($request->validated() + ['is_active' => $request->boolean('is_active')]); return redirect()->route('categories.index')->with('success', 'Kategori diperbarui.'); }
    public function destroy(Category $category) { if ($category->items()->exists()) return back()->with('error', 'Kategori masih memiliki item.'); $category->delete(); return back()->with('success', 'Kategori dihapus.'); }
}
