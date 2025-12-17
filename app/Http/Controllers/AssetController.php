<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index() {
        $assets = Asset::latest()->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function create() { return view('assets.create'); }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'value' => 'required|numeric']);
        Asset::create($request->all());
        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Asset $asset) { return view('assets.edit', compact('asset')); }

    public function update(Request $request, Asset $asset) {
        $request->validate(['name' => 'required', 'value' => 'required|numeric']);
        $asset->update($request->all());
        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset) {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }
}
