<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Carbon\Carbon;

// Import semua Model
use App\Models\User;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\School;
use App\Models\Asset;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryLot;
use App\Models\InventoryTransaction;
use App\Models\ProductionOrder;
use App\Models\Shipment;
use App\Models\ShipmentDestination;
use App\Models\Transaction;
use App\Models\Attendance;
use App\Models\Salary;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Reset Cache Permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =================================================================
        // BAGIAN 1: ROLES & USERS (SDM)
        // =================================================================
        $roleAdmin    = Role::firstOrCreate(['name' => 'Admin']);
        $roleDirektur = Role::firstOrCreate(['name' => 'Direktur']);
        $roleDriver   = Role::firstOrCreate(['name' => 'Driver']);
        $roleOperator = Role::firstOrCreate(['name' => 'Operator']);

        // -- User: Admin
        $adminUser = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@sppg.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($roleAdmin);
        $empAdmin = Employee::create([
            'user_id' => $adminUser->id, 'name' => 'Super Administrator', 'position' => 'IT Admin', 'phone' => '081100001', 'address' => 'Kantor Pusat', 'join_date' => '2023-01-01', 'status' => 'Aktif'
        ]);

        // -- User: Direktur
        $direkturUser = User::create([
            'name' => 'Bapak Direktur',
            'email' => 'direktur@sppg.com',
            'password' => Hash::make('password'),
        ]);
        $direkturUser->assignRole($roleDirektur);
        Employee::create([
            'user_id' => $direkturUser->id, 'name' => 'Bapak Direktur', 'position' => 'Direktur Utama', 'phone' => '081100002', 'address' => 'Jakarta', 'join_date' => '2020-01-01', 'status' => 'Aktif'
        ]);

        // -- User: Driver (Budi)
        $driverUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'driver@sppg.com',
            'password' => Hash::make('password'),
        ]);
        $driverUser->assignRole($roleDriver);
        $empDriver = Employee::create([
            'user_id' => $driverUser->id, 'name' => 'Budi Santoso', 'position' => 'Driver', 'phone' => '081234567890', 'address' => 'Mess Karyawan', 'join_date' => '2024-01-15', 'status' => 'Aktif'
        ]);

        // -- User: Koki/Operator (Siti)
        $kokiUser = User::create([
            'name' => 'Siti Aminah',
            'email' => 'dapur@sppg.com',
            'password' => Hash::make('password'),
        ]);
        $kokiUser->assignRole($roleOperator);
        $empKoki = Employee::create([
            'user_id' => $kokiUser->id, 'name' => 'Siti Aminah', 'position' => 'Kepala Dapur', 'phone' => '081298765432', 'address' => 'Bandung', 'join_date' => '2024-02-01', 'status' => 'Aktif'
        ]);


        // =================================================================
        // BAGIAN 2: MASTER DATA (Item, Supplier, School, Asset)
        // =================================================================

        // -- Items
        $itemBeras  = Item::create(['name' => 'Beras Premium', 'unit' => 'kg', 'price' => 13000]);
        $itemAyam   = Item::create(['name' => 'Daging Ayam Fillet', 'unit' => 'kg', 'price' => 45000]);
        $itemTelur  = Item::create(['name' => 'Telur Ayam', 'unit' => 'kg', 'price' => 28000]);
        $itemMinyak = Item::create(['name' => 'Minyak Goreng', 'unit' => 'liter', 'price' => 16000]);
        $itemSayur  = Item::create(['name' => 'Wortel & Buncis', 'unit' => 'kg', 'price' => 10000]);
        $itemBumbu  = Item::create(['name' => 'Bumbu Dapur Lengkap', 'unit' => 'paket', 'price' => 5000]);

        // -- Suppliers
        $sup1 = Supplier::create(['name' => 'PT Sinar Pangan Sejahtera', 'contact_person' => 'Pak Joko', 'phone' => '021-5551234', 'address' => 'Kawasan Industri Pulogadung']);
        $sup2 = Supplier::create(['name' => 'CV Sayur Segar Abadi', 'contact_person' => 'Bu Linda', 'phone' => '0818777888', 'address' => 'Pasar Induk Kramat Jati']);

        // -- Schools
        $sch1 = School::create(['name' => 'SDN Merdeka 01', 'principal_name' => 'Drs. Suherman', 'phone' => '022-123456', 'address' => 'Jl. Merdeka No. 10']);
        $sch2 = School::create(['name' => 'SDN Pelita Harapan', 'principal_name' => 'Ibu Ratna S.Pd', 'phone' => '022-654321', 'address' => 'Jl. Pelita No. 5']);
        $sch3 = School::create(['name' => 'SMP Negeri 1 Kota', 'principal_name' => 'Bpk. Hartono', 'phone' => '021-998877', 'address' => 'Jl. Sudirman No. 45']);

        // -- Assets
        Asset::create(['name' => 'Kompor Gas Rinnai High Pressure', 'category' => 'Peralatan Dapur', 'purchase_date' => '2023-05-10', 'value' => 2500000, 'status' => 'Baik']);
        Asset::create(['name' => 'Mobil Box Grand Max', 'category' => 'Kendaraan', 'purchase_date' => '2022-11-20', 'value' => 120000000, 'status' => 'Baik']);


        // =================================================================
        // BAGIAN 3: OPERASIONAL (Resep, PO, Stok, Produksi)
        // =================================================================

        // -- Resep: Paket Nasi Ayam
        $resepAyam = Recipe::create(['name' => 'Paket Nasi Ayam Goreng', 'instruction' => 'Cuci beras, masak nasi. Ungkep ayam, lalu goreng hingga kecoklatan. Tumis sayuran.']);
        RecipeItem::create(['recipe_id' => $resepAyam->id, 'item_id' => $itemBeras->id, 'quantity' => 0.15]); // 150gr beras per porsi
        RecipeItem::create(['recipe_id' => $resepAyam->id, 'item_id' => $itemAyam->id, 'quantity' => 0.12]);  // 120gr ayam
        RecipeItem::create(['recipe_id' => $resepAyam->id, 'item_id' => $itemMinyak->id, 'quantity' => 0.02]); // 20ml minyak
        RecipeItem::create(['recipe_id' => $resepAyam->id, 'item_id' => $itemBumbu->id, 'quantity' => 0.01]); // Sedikit bumbu

        // -- Resep: Paket Telur Balado
        $resepTelur = Recipe::create(['name' => 'Paket Nasi Telur Balado', 'instruction' => 'Rebus telur, kupas, goreng sebentar. Tumis bumbu balado, masukkan telur.']);
        RecipeItem::create(['recipe_id' => $resepTelur->id, 'item_id' => $itemBeras->id, 'quantity' => 0.15]);
        RecipeItem::create(['recipe_id' => $resepTelur->id, 'item_id' => $itemTelur->id, 'quantity' => 0.06]); // 1 butir telur ~60gr
        RecipeItem::create(['recipe_id' => $resepTelur->id, 'item_id' => $itemSayur->id, 'quantity' => 0.05]);

        // -- Purchase Order (Barang Masuk) - Simulasi stok awal bulan ini
        $po = PurchaseOrder::create([
            'po_number' => 'PO-' . date('Ym') . '-001',
            'supplier_id' => $sup1->id,
            'order_date' => Carbon::now()->subDays(5),
            'status' => 'Received',
            'total_amount' => 15000000 // Dummy total
        ]);

        // Detail PO & Generate Stok (Lot)
        $itemsToStock = [
            [$itemBeras, 500],  // Beli 500kg beras
            [$itemAyam, 200],   // Beli 200kg ayam
            [$itemMinyak, 100], // Beli 100 liter minyak
            [$itemTelur, 100],  // Beli 100 kg telur
        ];

        foreach ($itemsToStock as $data) {
            $itm = $data[0];
            $qty = $data[1];

            // Record PO Item
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'item_id' => $itm->id,
                'quantity_ordered' => $qty,
                'quantity_received' => $qty,
                'price_per_unit' => $itm->price
            ]);

            // Buat Inventory Lot (Batch)
            $lot = InventoryLot::create([
                'item_id' => $itm->id,
                'lot_number' => 'LOT-' . strtoupper(substr($itm->name, 0, 3)) . '-' . date('dm'),
                'quantity_initial' => $qty,
                'quantity_current' => $qty, // Stok masih utuh
                'received_date' => Carbon::now()->subDays(4),
                'expiry_date' => Carbon::now()->addMonths(6), // Expired 6 bulan lagi
            ]);

            // Transaksi Masuk
            InventoryTransaction::create([
                'item_id' => $itm->id,
                'inventory_lot_id' => $lot->id,
                'type' => 'IN',
                'quantity' => $qty,
                'reference_type' => 'PurchaseOrder',
                'reference_id' => $po->id,
                'notes' => 'Stok Awal Seeder'
            ]);
        }

        // -- Production Order (Work Order)
        $wo = ProductionOrder::create([
            'wo_number' => 'WO-' . date('Ymd') . '-001',
            'recipe_id' => $resepAyam->id,
            'target_quantity' => 100, // Masak 100 porsi
            'production_date' => Carbon::today(),
            'status' => 'Planned', // Masih rencana
        ]);


        // =================================================================
        // BAGIAN 4: DISTRIBUSI & KEUANGAN
        // =================================================================

        // -- Shipment (Pengiriman)
        $shipment = Shipment::create([
            'shipment_number' => 'SHP-' . date('Ymd') . '-001',
            'driver_id' => $empDriver->id,
            'departure_time' => Carbon::now()->addHours(2), // Berangkat 2 jam lagi
            'status' => 'Planned',
        ]);

        ShipmentDestination::create([
            'shipment_id' => $shipment->id,
            'school_id' => $sch1->id,
            'production_order_id' => $wo->id, // Ambil dari WO masak ayam
            'quantity' => 50,
            'status' => 'Pending'
        ]);

        ShipmentDestination::create([
            'shipment_id' => $shipment->id,
            'school_id' => $sch2->id,
            'production_order_id' => $wo->id,
            'quantity' => 50,
            'status' => 'Pending'
        ]);

        // -- Keuangan (Transactions)
        Transaction::create([
            'date' => Carbon::now()->subDays(2),
            'type' => 'Expense',
            'category' => 'Operasional',
            'description' => 'Isi Bensin Mobil Box',
            'amount' => 250000,
            'reference_type' => 'Manual'
        ]);

        Transaction::create([
            'date' => Carbon::now()->subDays(1),
            'type' => 'Income',
            'category' => 'Subsidi',
            'description' => 'Pencairan Dana Subsidi Tahap 1',
            'amount' => 50000000,
            'reference_type' => 'Manual'
        ]);

        // -- Absensi (Hari Ini)
        Attendance::create(['employee_id' => $empAdmin->id, 'date' => Carbon::today(), 'status' => 'Hadir']);
        Attendance::create(['employee_id' => $empDriver->id, 'date' => Carbon::today(), 'status' => 'Hadir']);
        Attendance::create(['employee_id' => $empKoki->id, 'date' => Carbon::today(), 'status' => 'Sakit']);

        // -- Gaji (Bulan Lalu)
        Salary::create([
            'employee_id' => $empDriver->id,
            'period' => Carbon::now()->subMonth()->format('Y-m'),
            'basic_salary' => 4500000,
            'allowance' => 500000,
            'deduction' => 0,
            'net_salary' => 5000000
        ]);
    }
}
