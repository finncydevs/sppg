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
      Schema::create('purchase_order_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('purchase_order_id');
    $table->unsignedBigInteger('item_id');
    $table->integer('quantity_ordered');
    $table->integer('quantity_received')->default(0);
    $table->decimal('price_per_unit', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
