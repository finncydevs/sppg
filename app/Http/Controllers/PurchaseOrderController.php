<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryLot;
use App\Models\InventoryTransaction;
use App\Models\Supplier;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Menampilkan daftar Purchase Order (PO).
     */
    public function index()
    {
        $pos = PurchaseOrder::with('supplier')->latest()->paginate(10);
        return view('purchase_orders.index', compact('pos'));
    }

    /**
     * Menampilkan form untuk membuat PO baru.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('purchase_orders.create', compact('suppliers', 'items'));
    }

    /**
     * Menyimpan PO baru ke database (Lengkap dengan Validasi).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Lengkap
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1', // Harus ada minimal 1 item
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // 2. Generate Nomor PO Otomatis (Format: PO-YYYYMM-001)
            // Menggunakan lockForUpdate (opsional) atau logic sederhana count+1
            $prefix = 'PO-' . date('Ym') . '-';
            // Mencari nomor urut terakhir di bulan ini untuk menghindari duplikat
            $lastPo = PurchaseOrder::where('po_number', 'like', $prefix . '%')
                        ->orderBy('id', 'desc')
                        ->first();

            $lastNumber = $lastPo ? intval(substr($lastPo->po_number, -3)) : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $poNumber = $prefix . $newNumber;

            // 3. Simpan Header PO
            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'status' => 'Draft',
                'total_amount' => 0 // Inisialisasi 0, nanti di-update
            ]);

            $total = 0;

            // 4. Simpan Detail Item PO
            foreach ($request->items as $row) {
                $item = Item::findOrFail($row['item_id']);
                $subtotal = $item->price * $row['quantity'];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id' => $row['item_id'],
                    'quantity_ordered' => $row['quantity'],
                    'quantity_received' => 0, // Default belum diterima
                    'price_per_unit' => $item->price, // Kunci harga saat beli
                ]);

                $total += $subtotal;
            }

            // 5. Update Total Amount di Header
            $po->update(['total_amount' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dibuat.');
    }

    /**
     * Menangani proses Penerimaan Barang (Receiving).
     * Menerima barang dari PO -> Menambah Stok (Lot) -> Update Status PO.
     */
    public function receive(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        // Validasi input penerimaan
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_received' => 'required|numeric|min:1',
            'items.*.expiry_date' => 'nullable|date',
            'items.*.lot_number' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $po) {
            $hasReceivedAny = false;

            foreach ($request->items as $receivedItem) {
                $qtyReceived = $receivedItem['qty_received'];

                // Skip jika jumlah yang diterima 0 atau kosong
                if ($qtyReceived <= 0) continue;

                $hasReceivedAny = true;

                // 1. Update jumlah yang diterima di tabel purchase_order_items
                $poItem = PurchaseOrderItem::where('purchase_order_id', $po->id)
                            ->where('item_id', $receivedItem['item_id'])
                            ->first();

                if ($poItem) {
                    $poItem->increment('quantity_received', $qtyReceived);
                }

                // 2. Buat Inventory Lot Baru (Batch Barang Masuk)
                // Jika user tidak input nomor lot, generate otomatis
                $lotNumber = $receivedItem['lot_number'] ?? ('LOT-' . date('Ymd') . '-' . time());

                $lot = InventoryLot::create([
                    'item_id' => $receivedItem['item_id'],
                    'lot_number' => $lotNumber,
                    'quantity_initial' => $qtyReceived,
                    'quantity_current' => $qtyReceived,
                    'received_date' => now(),
                    'expiry_date' => $receivedItem['expiry_date'] ?? null,
                ]);

                // 3. Catat Riwayat Transaksi Stok (Kartu Stok)
                InventoryTransaction::create([
                    'item_id' => $receivedItem['item_id'],
                    'inventory_lot_id' => $lot->id,
                    'type' => 'IN', // Barang Masuk
                    'quantity' => $qtyReceived,
                    'reference_type' => 'PurchaseOrder', // Referensi ke Model
                    'reference_id' => $po->id,
                    'notes' => 'Penerimaan PO ' . $po->po_number
                ]);
            }

            if (!$hasReceivedAny) {
                // Jika tidak ada item valid yang diterima, jangan update status
                return;
            }

            // 4. Cek Status PO: Apakah semua item sudah diterima penuh?
            $allItems = $po->items;
            $isFull = true;
            $isPartial = false;

            foreach ($allItems as $item) {
                if ($item->quantity_received < $item->quantity_ordered) {
                    $isFull = false;
                }
                if ($item->quantity_received > 0) {
                    $isPartial = true;
                }
            }

            if ($isFull) {
                $po->update(['status' => 'Received']); // Selesai Penuh
            } elseif ($isPartial) {
                $po->update(['status' => 'Partial']); // Diterima Sebagian
            }
        });

        return back()->with('success', 'Barang berhasil diterima dan stok telah ditambahkan.');
    }

    /**
     * Menampilkan detail PO (Opsional, untuk view show).
     */
    public function show($id)
    {
        $po = PurchaseOrder::with(['items.item', 'supplier'])->findOrFail($id);
        return view('purchase_orders.show', compact('po'));
    }

    /**
     * Menghapus PO (Hanya jika status masih Draft).
     */
    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'Draft') {
            return back()->with('error', 'Hanya PO dengan status Draft yang bisa dihapus.');
        }

        $po->items()->delete(); // Hapus item dulu
        $po->delete(); // Hapus header

        return redirect()->route('purchase-orders.index')->with('success', 'PO berhasil dihapus.');
    }
}
