<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Menampilkan daftar transaksi keuangan.
     * Bisa difilter berdasarkan bulan atau tipe (Pemasukan/Pengeluaran).
     */
    public function index(Request $request)
    {
        $query = Transaction::latest();

        // Filter berdasarkan Bulan (Format: YYYY-MM)
        if ($request->has('month') && $request->month != '') {
            $query->where('date', 'like', $request->month . '%');
        }

        // Filter berdasarkan Tipe
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Hitung Ringkasan untuk view (Opsional, biar informatif)
        $summary = [
            'income' => Transaction::where('type', 'Income')->sum('amount'),
            'expense' => Transaction::where('type', 'Expense')->sum('amount'),
        ];
        $summary['balance'] = $summary['income'] - $summary['expense'];

        return view('transactions.index', compact('transactions', 'summary'));
    }

    /**
     * Menampilkan form tambah transaksi manual.
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Menyimpan transaksi manual (Misal: Bayar Listrik, Uang Kas, dll).
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Income,Expense',
            'category' => 'required|string|max:50', // Contoh: Operasional, Transport, Lain-lain
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Transaction::create([
            'date' => $request->date,
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'reference_type' => 'Manual', // Penanda bahwa ini inputan manual
            'reference_id' => null,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan form edit transaksi.
     */
    public function edit(Transaction $transaction)
    {
        // Cegah edit transaksi otomatis (misal dari PO) agar data tetap konsisten
        if ($transaction->reference_type !== 'Manual' && $transaction->reference_type !== null) {
            return back()->with('error', 'Transaksi otomatis dari sistem tidak dapat diedit secara manual.');
        }

        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Memperbarui transaksi manual.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->reference_type !== 'Manual' && $transaction->reference_type !== null) {
            return back()->with('error', 'Transaksi otomatis dari sistem tidak dapat diedit secara manual.');
        }

        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:Income,Expense',
            'category' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        $transaction->update([
            'date' => $request->date,
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi.
     */
    public function destroy(Transaction $transaction)
    {
        // Cegah hapus transaksi otomatis
        if ($transaction->reference_type !== 'Manual' && $transaction->reference_type !== null) {
            return back()->with('error', 'Transaksi otomatis dari sistem tidak dapat dihapus. Hapus sumber datanya (misal: PO) jika perlu.');
        }

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
