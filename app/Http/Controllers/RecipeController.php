<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Item;
use App\Models\RecipeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Menampilkan daftar resep.
     */
    public function index() {
        // Eager load items.item untuk performa jika ingin menampilkan summary bahan di index
        $recipes = Recipe::with('items.item')->latest()->paginate(10);
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Menampilkan form buat resep baru.
     */
    public function create() {
        $items = Item::all(); // Load semua bahan baku untuk dropdown
        return view('recipes.create', compact('items'));
    }

    /**
     * Menyimpan resep baru ke database.
     */
    public function store(Request $request) {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'items' => 'required|array|min:1', // Harus ada minimal 1 bahan
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
        ]);

        DB::transaction(function() use ($request) {
            // 1. Simpan Header Resep
            $recipe = Recipe::create([
                'name' => $request->name,
                'instruction' => $request->instruction
            ]);

            // 2. Simpan Detail Bahan (BOM)
            foreach ($request->items as $itemData) {
                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity']
                ]);
            }
        });

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil dibuat.');
    }

    /**
     * Menampilkan form edit resep.
     */
    public function edit(Recipe $recipe) {
        // Load relasi items agar muncul di form edit (untuk AlpineJS)
        $recipe->load('items');
        $items = Item::all();
        return view('recipes.edit', compact('recipe', 'items'));
    }

    /**
     * Memperbarui resep yang sudah ada.
     */
    public function update(Request $request, Recipe $recipe) {
        $request->validate([
            'name' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
        ]);

        DB::transaction(function() use ($request, $recipe) {
            // 1. Update Header Resep
            $recipe->update([
                'name' => $request->name,
                'instruction' => $request->instruction
            ]);

            // 2. Reset Detail Bahan
            // Hapus semua bahan lama, lalu insert yang baru (Strategy Full Sync)
            // Ini cara paling aman dan mudah untuk menangani dynamic rows
            $recipe->items()->delete();

            // 3. Masukkan Detail Bahan Baru
            foreach ($request->items as $itemData) {
                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity']
                ]);
            }
        });

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil diperbarui.');
    }

    /**
     * Menghapus resep.
     */
    public function destroy(Recipe $recipe) {
        DB::transaction(function() use ($recipe) {
            // Hapus item/bahan-bahan terkait dulu
            $recipe->items()->delete();
            // Baru hapus resepnya
            $recipe->delete();
        });

        return redirect()->route('recipes.index')->with('success', 'Resep berhasil dihapus.');
    }
}
