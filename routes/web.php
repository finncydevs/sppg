<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // PENTING: Bawaan Breeze
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DriverController; // Import Driver Controller

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('employees', EmployeeController::class);
    Route::resource('items', ItemController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('assets', AssetController::class);

    // Operasional - Perencanaan & Produksi
    Route::resource('recipes', RecipeController::class);
    Route::resource('production-orders', ProductionOrderController::class);

    // Route Khusus: Selesaikan Produksi
    Route::post('production-orders/{id}/complete', [ProductionOrderController::class, 'complete'])->name('production-orders.complete');

    // Operasional - Pengadaan & Stok
    Route::resource('purchase-orders', PurchaseOrderController::class);

    // Route Khusus: Terima Barang PO
    Route::post('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Route Khusus: Stok Opname
    Route::post('inventory/adjust', [InventoryController::class, 'adjustment'])->name('inventory.adjust');

    // Operasional - Distribusi
    Route::resource('shipments', ShipmentController::class);
    Route::get('shipments/{id}/print', [ShipmentController::class, 'printSuratJalan'])->name('shipments.print');

    // Keuangan & HR
    Route::resource('transactions', FinanceController::class); // Keuangan
    Route::resource('salaries', SalaryController::class);
    Route::resource('route-templates', App\Http\Controllers\RouteTemplateController::class)->only(['index', 'create', 'store', 'destroy']);
Route::post('route-templates/generate', [App\Http\Controllers\RouteTemplateController::class, 'generate'])->name('route-templates.generate');
    Route::resource('attendances', AttendanceController::class);
Route::resource('menu-plans', App\Http\Controllers\MenuPlanController::class)->only(['index', 'store', 'destroy']);
Route::post('menu-plans/publish', [App\Http\Controllers\MenuPlanController::class, 'publish'])->name('menu-plans.publish');
    // Area Khusus Driver
    Route::middleware(['role:Driver'])->group(function () {
        Route::get('/driver/tasks', [DriverController::class, 'index'])->name('driver.index');
        Route::put('/driver/tasks/{destinationId}', [DriverController::class, 'update'])->name('driver.update');
    });
});

require __DIR__.'/auth.php';
