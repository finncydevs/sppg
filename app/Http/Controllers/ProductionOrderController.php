<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\Recipe;
use App\Models\InventoryLot;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function index() {
        $wos = ProductionOrder::with('recipe')->latest()->paginate(10);
        return view('production_orders.index', compact('wos'));
    }

    public function create() {
        $recipes = Recipe::all();
        return view('production_orders.create', compact('recipes'));
    }

    public function store(Request $request) {
        $request->validate([
            'recipe_id' => 'required',
            'target_quantity' => 'required|numeric|min:1',
            'production_date' => 'required|date',
        ]);

        // Generate WO Number: WO-20240101-001
        $dateStr = date('Ymd', strtotime($request->production_date));
        $count = ProductionOrder::whereDate('production_date', $request->production_date)->count() + 1;
        $woNumber = "WO-{$dateStr}-" . str_pad($count, 3, '0', STR_PAD_LEFT);

        ProductionOrder::create([
            'wo_number' => $woNumber,
            'recipe_id' => $request->recipe_id,
            'target_quantity' => $request->target_quantity,
            'production_date' => $request->production_date,
            'status' => 'Planned',
        ]);

        return redirect()->route('production-orders.index')->with('success', 'Work Order berhasil dibuat.');
    }

    // Logic: Selesaikan Produksi & Potong Stok Bahan Baku
    public function complete($id) {
        $wo = ProductionOrder::with('recipe.items')->findOrFail($id);

        if ($wo->status === 'Completed') {
            return back()->with('error', 'WO ini sudah selesai.');
        }

        DB::transaction(function() use ($wo) {
            // 1. Loop semua bahan baku di resep
            foreach ($wo->recipe->items as $recipeItem) {
                // Hitung total kebutuhan: (Butuh per porsi * Target Porsi WO)
                $qtyNeeded = $recipeItem->quantity * $wo->target_quantity;

                // 2. Ambil stok (FIFO - First In First Out)
                // Cari lot yang itemnya sama, stok > 0, urutkan dari yang paling lama masuk
                $lots = InventoryLot::where('item_id', $recipeItem->item_id)
                            ->where('quantity_current', '>', 0)
                            ->orderBy('received_date', 'asc')
                            ->get();

                $qtyToDeduct = $qtyNeeded;

                foreach ($lots as $lot) {
                    if ($qtyToDeduct <= 0) break;

                    // Berapa yang bisa diambil dari lot ini?
                    $deducted = min($lot->quantity_current, $qtyToDeduct);

                    // Kurangi stok lot
                    $lot->decrement('quantity_current', $deducted);

                    // Catat transaksi OUT
                    InventoryTransaction::create([
                        'item_id' => $recipeItem->item_id,
                        'inventory_lot_id' => $lot->id,
                        'type' => 'OUT',
                        'quantity' => $deducted,
                        'reference_type' => 'ProductionOrder',
                        'reference_id' => $wo->id,
                        'notes' => "Produksi {$wo->wo_number} (Resep: {$wo->recipe->name})"
                    ]);

                    $qtyToDeduct -= $deducted;
                }

                // Jika stok kurang, kita biarkan minus di logic ini atau throw error
                // Di sini kita catat sisa kekurangan sebagai 'OUT' tanpa lot (opsional, agar balance)
                if ($qtyToDeduct > 0) {
                     // Opsional: Handle stock minus logic here if needed
                }
            }

            // 3. Update Status WO
            $wo->update(['status' => 'Completed']);
        });

        return back()->with('success', 'Produksi selesai. Stok bahan baku telah dikurangi otomatis.');
    }

}
