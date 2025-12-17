<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentDestination;
use App\Models\Employee;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request) {
    // Mulai query
    $query = Shipment::with(['driver', 'destinations.school']);

    // LOGIKA FILTER:
    // Jika ada parameter ?filter=today di URL, ambil data hari ini saja.
    // Jika tidak, tampilkan semua.
    if ($request->get('filter') == 'today') {
        $query->whereDate('departure_time', now()->format('Y-m-d'));
    }

    // SORTING:
    // Urutkan berdasarkan waktu keberangkatan, bukan waktu pembuatan
    $shipments = $query->orderBy('departure_time', 'desc')->paginate(10);

    return view('shipments.index', compact('shipments'));
}

    public function create() {
        $drivers = Employee::where('position', 'Driver')->get();
        $schools = School::all();
        return view('shipments.create', compact('drivers', 'schools'));
    }

    public function store(Request $request) {
        $request->validate([
            'driver_id' => 'required',
            'departure_time' => 'required',
            'destinations' => 'required|array'
        ]);

        DB::transaction(function() use ($request) {
            $shipmentNumber = 'SHP-' . date('Ymd') . '-' . rand(100,999);

            $shipment = Shipment::create([
                'shipment_number' => $shipmentNumber,
                'driver_id' => $request->driver_id,
                'departure_time' => $request->departure_time,
                'status' => 'Planned'
            ]);

            foreach ($request->destinations as $dest) {
                ShipmentDestination::create([
                    'shipment_id' => $shipment->id,
                    'school_id' => $dest['school_id'],
                    'quantity' => $dest['quantity'],
                    'status' => 'Pending'
                ]);
            }
        });

        return redirect()->route('shipments.index')->with('success', 'Jadwal pengiriman berhasil dibuat.');
    }

    public function printSuratJalan($id) {
        $shipment = Shipment::with(['driver', 'destinations.school'])->findOrFail($id);
        return view('shipments.print', compact('shipment'));
    }

    /**
     * Menampilkan form edit pengiriman.
     */
    public function edit(Shipment $shipment)
    {
        // Eager load relasi agar query efisien
        $shipment->load(['destinations.school', 'driver']);

        $drivers = Employee::where('position', 'Driver')->where('status', 'Aktif')->get();
        $schools = School::all();

        return view('shipments.edit', compact('shipment', 'drivers', 'schools'));
    }

    /**
     * Memperbarui data pengiriman dan tujuannya.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'driver_id' => 'required|exists:employees,id',
            'departure_time' => 'required|date',
            'status' => 'required|in:Planned,In Transit,Completed,Cancelled',
            'destinations' => 'required|array|min:1',
            'destinations.*.school_id' => 'required|exists:schools,id',
            'destinations.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($request, $shipment) {
            // 1. Update Header
            $shipment->update([
                'driver_id' => $request->driver_id,
                'departure_time' => $request->departure_time,
                'status' => $request->status,
            ]);

            // 2. Sync Destinations (Hapus lama, buat baru - cara paling aman untuk dynamic rows)
            // Note: Jika ingin history status per sekolah (Pending/Delivered) tetap ada, logicnya harus lebih kompleks (update existing).
            // Untuk MVP ini, kita reset tujuan jika diedit total.
            $shipment->destinations()->delete();

            foreach ($request->destinations as $dest) {
                ShipmentDestination::create([
                    'shipment_id' => $shipment->id,
                    'school_id' => $dest['school_id'],
                    'quantity' => $dest['quantity'],
                    'status' => 'Pending' // Reset status pengiriman per sekolah
                ]);
            }
        });

        return redirect()->route('shipments.index')->with('success', 'Jadwal pengiriman berhasil diperbarui.');
    }

    /**
     * Menghapus pengiriman.
     */
    public function destroy(Shipment $shipment)
    {
        DB::transaction(function() use ($shipment) {
            $shipment->destinations()->delete();
            $shipment->delete();
        });

        return redirect()->route('shipments.index')->with('success', 'Data pengiriman berhasil dihapus.');
    }

    public function show($id)
    {
        // Eager load: driver, tujuan -> sekolah, tujuan -> bukti foto
        $shipment = Shipment::with([
            'driver',
            'destinations.school',
            'destinations.proofs'
        ])->findOrFail($id);

        return view('shipments.show', compact('shipment'));
    }
}
