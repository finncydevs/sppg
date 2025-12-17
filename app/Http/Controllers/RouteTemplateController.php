<?php

namespace App\Http\Controllers;

use App\Models\RouteTemplate;
use App\Models\RouteTemplateDestination;
use App\Models\Employee;
use App\Models\School;
use App\Models\Shipment;
use App\Models\ShipmentDestination;
use App\Models\ProductionOrder;
use App\Models\MenuPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RouteTemplateController extends Controller
{
    /**
     * Menampilkan daftar template rute.
     */
    public function index()
    {
        $templates = RouteTemplate::with(['driver', 'destinations.school'])->latest()->paginate(10);
        return view('route_templates.index', compact('templates'));
    }

    /**
     * Form tambah template.
     */
    public function create()
    {
        $drivers = Employee::where('position', 'Driver')->where('status', 'Aktif')->get();
        $schools = School::all();
        return view('route_templates.create', compact('drivers', 'schools'));
    }

    /**
     * Simpan template baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'default_driver_id' => 'required|exists:employees,id',
            'default_departure_time' => 'required',
            'destinations' => 'required|array|min:1',
        ]);

        DB::transaction(function() use ($request) {
            $template = RouteTemplate::create([
                'name' => $request->name,
                'default_driver_id' => $request->default_driver_id,
                'default_departure_time' => $request->default_departure_time,
            ]);

            foreach ($request->destinations as $dest) {
                RouteTemplateDestination::create([
                    'route_template_id' => $template->id,
                    'school_id' => $dest['school_id'],
                    'default_quantity' => $dest['quantity'],
                ]);
            }
        });

        return redirect()->route('route-templates.index')->with('success', 'Master Rute berhasil dibuat.');
    }

    /**
     * Hapus template.
     */
    public function destroy(RouteTemplate $routeTemplate)
    {
        DB::transaction(function() use ($routeTemplate) {
            $routeTemplate->destinations()->delete();
            $routeTemplate->delete();
        });
        return redirect()->route('route-templates.index')->with('success', 'Template rute dihapus.');
    }

    /**
     * GENERATOR INTI: Membuat jadwal massal berdasarkan template.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:route_templates,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array', // Array hari (1=Senin, 7=Minggu)
        ]);

        $template = RouteTemplate::with('destinations')->findOrFail($request->template_id);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $generatedCount = 0;

        DB::transaction(function() use ($template, $startDate, $endDate, $request, &$generatedCount) {

            // Loop setiap hari dalam rentang tanggal
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

                // Cek apakah hari ini dipilih (misal hanya Senin-Jumat)
                // Carbon dayOfWeekIso: 1 (Senin) - 7 (Minggu)
                if (in_array($date->dayOfWeekIso, $request->days)) {

                    // Generate Nomor Shipment Unik
                    $dateStr = $date->format('Ymd');
                    // Cek urutan shipment hari itu
                    $dailyCount = Shipment::whereDate('departure_time', $date)->count() + 1;
                    $shipNumber = "SHP-{$dateStr}-" . str_pad($dailyCount, 3, '0', STR_PAD_LEFT);

                    // Gabungkan Tanggal + Jam Default Template
                    $departureTime = $date->format('Y-m-d') . ' ' . $template->default_departure_time;

                    // 1. Buat Header Shipment
                    $shipment = Shipment::create([
                        'shipment_number' => $shipNumber,
                        'driver_id' => $template->default_driver_id,
                        'vehicle_id' => null, // Opsional
                        'departure_time' => $departureTime,
                        'status' => 'Planned',
                    ]);

                    // 2. Buat Detail Tujuan
                    foreach ($template->destinations as $dest) {

                        // Fitur Canggih (Opsional):
                        // Mencoba mencari apakah ada Menu/WO di tanggal tsb untuk dihubungkan?
                        // Untuk sekarang kita biarkan null dulu biar ringan.
                        $productionOrderId = null;

                        ShipmentDestination::create([
                            'shipment_id' => $shipment->id,
                            'school_id' => $dest->school_id,
                            'production_order_id' => $productionOrderId,
                            'quantity' => $dest->default_quantity,
                            'status' => 'Pending'
                        ]);
                    }

                    $generatedCount++;
                }
            }
        });

        return back()->with('success', "Berhasil men-generate {$generatedCount} jadwal pengiriman dari template '{$template->name}'.");
    }
}
