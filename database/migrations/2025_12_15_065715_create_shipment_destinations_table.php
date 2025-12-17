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
    Schema::create('shipment_destinations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('shipment_id');
    $table->unsignedBigInteger('school_id');
    $table->unsignedBigInteger('production_order_id')->nullable(); // Ambil makanan dari WO mana?
    $table->integer('quantity'); // Jumlah porsi dikirim
    $table->string('status'); // 'Pending', 'Delivered', 'Failed'
    $table->string('receiver_name')->nullable();
    $table->text('notes')->nullable();
    $table->string('proof_photo_path')->nullable(); // Foto bukti
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_destinations');
    }
};
