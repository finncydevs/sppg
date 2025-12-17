<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index() {
        $items = Item::latest()->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create() { return view('items.create'); }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'unit' => 'required', 'price' => 'required|numeric']);
        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item) { return view('items.edit', compact('item')); }

    public function update(Request $request, Item $item) {
        $request->validate(['name' => 'required', 'unit' => 'required']);
        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item) {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}
