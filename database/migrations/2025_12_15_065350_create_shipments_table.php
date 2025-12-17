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
       Schema::create('shipments', function (Blueprint $table) {
    $table->id();
    $table->string('shipment_number');
    $table->unsignedBigInteger('driver_id'); // ID dari Employees (posisi Driver)
    $table->unsignedBigInteger('vehicle_id')->nullable(); // Jika nanti ada master kendaraan
    $table->dateTime('departure_time')->nullable();
    $table->string('status'); // 'Planned', 'In Transit', 'Completed'
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
