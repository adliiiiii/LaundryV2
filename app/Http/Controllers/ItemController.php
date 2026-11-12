<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request) { return view('items.index', ['items' => Item::with('category')->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%$search%"))->latest()->paginate(15)->withQueryString()]); }
    public function create() { return view('items.create', ['item' => new Item(), 'categories' => Category::orderBy('name')->get()]); }
    public function store(ItemRequest $request) { Item::create($request->validated()); return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.'); }
    public function edit(Item $item) { return view('items.edit', ['item' => $item, 'categories' => Category::orderBy('name')->get()]); }
    public function update(ItemRequest $request, Item $item) { $item->update($request->validated()); return redirect()->route('items.index')->with('success', 'Item diperbarui.'); }
    public function destroy(Item $item) { $item->delete(); return back()->with('success', 'Item dihapus.'); }
}
