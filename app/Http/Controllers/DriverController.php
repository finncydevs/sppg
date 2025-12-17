<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShipmentDestination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Atau Imagick jika pakai Imagick
use Illuminate\Support\Str;
class DriverController extends Controller
{
    /**
     * Menampilkan daftar tugas pengiriman untuk Driver yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan user punya data employee terkait
        if (!$user->employee) {
            abort(403, 'Akun Anda tidak terhubung dengan data karyawan.');
        }

        $driverId = $user->employee->id;
        $today = now()->format('Y-m-d');

        // Ambil pengiriman yang ditugaskan ke driver ini untuk HARI INI
        $shipments = Shipment::where('driver_id', $driverId)
            ->whereDate('departure_time', $today)
            ->with(['destinations.school', 'destinations.productionOrder.recipe']) // Eager load relasi
            ->orderBy('departure_time', 'asc')
            ->get();

        return view('driver.index', compact('shipments', 'today'));
    }

    /**
     * Menyimpan laporan pengiriman (Status, Penerima, Catatan, Foto).
     */
   public function update(Request $request, $destinationId)
    {
        $request->validate([
            'status' => 'required|in:Terkirim,Gagal Kirim',
            'receiver_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Validasi array file (multiple)
            'proof_photos' => 'nullable|array',
            'proof_photos.*' => 'image|mimes:jpeg,png,jpg|max:10240', // Max 10MB per file sebelum kompresi
        ]);

        $destination = ShipmentDestination::findOrFail($destinationId);

        // Security Check
        if ($destination->shipment->driver_id !== Auth::user()->employee->id) {
            abort(403, 'Anda tidak berhak mengupdate pengiriman ini.');
        }

        DB::transaction(function() use ($request, $destination) {
            // 1. Update Data Teks
            $destination->update([
                'status' => $request->status,
                'receiver_name' => $request->receiver_name,
                'notes' => $request->notes,
                'delivered_at' => now(),
            ]);

            // 2. Handle Multiple Upload & Compression
            if ($request->hasFile('proof_photos')) {
                // Setup Image Manager (Intervention Image v3)
                $manager = new ImageManager(new Driver());

                foreach ($request->file('proof_photos') as $photo) {
                    // Generate nama file unik
                    $filename = 'proof_' . $destination->id . '_' . Str::random(10) . '.jpg';
                    $path = 'proofs/' . $filename;

                    // A. Baca Gambar
                    $image = $manager->read($photo);

                    // B. Resize (Kunci Storage Hemat)
                    // Resize lebar ke 800px, tinggi menyesuaikan aspek rasio.
                    // Jangan resize jika gambar aslinya lebih kecil dari 800px.
                    $image->scale(width: 800);

                    // C. Encode & Kompres (Quality 60-75% sudah cukup bagus untuk bukti)
                    $encoded = $image->toJpeg(75);

                    // D. Simpan ke Storage Laravel (storage/app/public/proofs)
                    Storage::disk('public')->put($path, (string) $encoded);

                    // E. Simpan ke Database
                    $destination->proofs()->create([
                        'photo_path' => $path
                    ]);
                }
            }

            // 3. Cek Status Shipment Induk
            $shipment = $destination->shipment;
            $allDone = $shipment->destinations()->where('status', 'Pending')->doesntExist();

            if ($allDone) {
                $shipment->update(['status' => 'Completed']);
            } elseif ($shipment->status === 'Planned') {
                $shipment->update(['status' => 'In Transit']);
            }
        });

        return back()->with('success', 'Laporan berhasil dikirim.');
    }
    
}
