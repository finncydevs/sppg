<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('inventory_transactions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('inventory_lot_id')->nullable();
    $table->string('type'); // 'IN' (Masuk), 'OUT' (Keluar), 'ADJUSTMENT' (Opname)
    $table->decimal('quantity', 10, 2); // Jumlah perubahan
    $table->string('reference_type')->nullable(); // 'PurchaseOrder', 'ProductionOrder', 'Shipment'
    $table->unsignedBigInteger('reference_id')->nullable(); // ID dari referensi tsb
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
