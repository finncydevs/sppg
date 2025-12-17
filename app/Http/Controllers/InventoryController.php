<?php

namespace App\Http\Controllers;

use App\Models\InventoryLot;
use App\Models\InventoryTransaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar stok agregat per item dan detail lot-nya.
     */
    public function index() {
        // Ambil item beserta total stok saat ini
        $stocks = Item::withSum('inventoryLots as total_stock', 'quantity_current')
                    ->with(['inventoryLots' => function($q) {
                        // Ambil lot yang masih ada stoknya saja, urutkan dari yang mau expired
                        $q->where('quantity_current', '>', 0)
                          ->orderBy('expiry_date', 'asc');
                    }])
                    ->get();

        return view('inventory.index', compact('stocks'));
    }

    /**
     * Menangani Stok Opname (Penyesuaian Stok Manual).
     */
    public function adjustment(Request $request) {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:increase,decrease', // Tambah atau Kurang
            'quantity' => 'required|numeric|min:0.01',
            'lot_id' => 'nullable|exists:inventory_lots,id', // Opsional, jika null = buat lot baru (khusus increase)
            'notes' => 'nullable|string',
            'expiry_date' => 'nullable|date|required_if:type,increase', // Jika nambah stok baru, butuh expired date
        ]);

        DB::transaction(function() use ($request) {
            $lot = null;

            // SKENARIO 1: PENAMBAHAN STOK (INCREASE)
            if ($request->type === 'increase') {
                if ($request->lot_id) {
                    // Update lot yang sudah ada
                    $lot = InventoryLot::findOrFail($request->lot_id);
                    $lot->increment('quantity_current', $request->quantity);
                } else {
                    // Buat Lot Baru (misal: Stok Awal atau Barang Temuan)
                    $lot = InventoryLot::create([
                        'item_id' => $request->item_id,
                        'lot_number' => 'ADJ-' . time(), // Kode unik manual
                        'quantity_initial' => $request->quantity,
                        'quantity_current' => $request->quantity,
                        'received_date' => now(),
                        'expiry_date' => $request->expiry_date,
                    ]);
                }

                // Catat Transaksi Masuk (IN)
                InventoryTransaction::create([
                    'item_id' => $request->item_id,
                    'inventory_lot_id' => $lot->id,
                    'type' => 'ADJUSTMENT', // Tipe khusus penyesuaian
                    'quantity' => $request->quantity,
                    'notes' => 'Penambahan Stok: ' . ($request->notes ?? 'Manual Adjustment'),
                    'reference_type' => 'Manual',
                ]);

            }
            // SKENARIO 2: PENGURANGAN STOK (DECREASE)
            else {
                // Wajib pilih lot mana yang mau dikurangi (agar akurat expired-nya)
                if (!$request->lot_id) {
                    throw new \Exception("Untuk pengurangan stok, mohon pilih No. Lot spesifik.");
                }

                $lot = InventoryLot::findOrFail($request->lot_id);

                if ($lot->quantity_current < $request->quantity) {
                    throw new \Exception("Stok di lot ini tidak mencukupi. Sisa: " . $lot->quantity_current);
                }

                $lot->decrement('quantity_current', $request->quantity);

                // Catat Transaksi Keluar (OUT) dengan nilai minus atau type khusus
                InventoryTransaction::create([
                    'item_id' => $request->item_id,
                    'inventory_lot_id' => $lot->id,
                    'type' => 'ADJUSTMENT',
                    'quantity' => -$request->quantity, // Simpan sebagai negatif untuk pengurangan
                    'notes' => 'Pengurangan Stok: ' . ($request->notes ?? 'Barang Rusak/Hilang'),
                    'reference_type' => 'Manual',
                ]);
            }
        });

        return back()->with('success', 'Stok berhasil disesuaikan.');
    }
}
