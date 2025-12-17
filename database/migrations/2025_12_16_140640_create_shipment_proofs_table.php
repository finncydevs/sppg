<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel khusus untuk menampung banyak foto
        Schema::create('shipment_proofs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_destination_id');
            $table->string('photo_path');
            $table->timestamps();

            // Opsional: Foreign key constraint (jika ingin ketat)
            // $table->foreign('shipment_destination_id')->references('id')->on('shipment_destinations')->onDelete('cascade');
        });

        // 2. Hapus kolom foto lama di tabel tujuan (opsional, bisa dibiarkan null)
        Schema::table('shipment_destinations', function (Blueprint $table) {
            $table->dropColumn('proof_photo_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_proofs');
        Schema::table('shipment_destinations', function (Blueprint $table) {
            $table->string('proof_photo_path')->nullable();
        });
    }
};
