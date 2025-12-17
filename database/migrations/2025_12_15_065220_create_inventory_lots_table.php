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
      Schema::create('inventory_lots', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('item_id');
    $table->string('lot_number'); // Kode Batch
    $table->decimal('quantity_current', 10, 2); // Sisa stok di lot ini
    $table->decimal('quantity_initial', 10, 2); // Stok awal masuk
    $table->date('expiry_date')->nullable();
    $table->date('received_date');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_lots');
    }
};
