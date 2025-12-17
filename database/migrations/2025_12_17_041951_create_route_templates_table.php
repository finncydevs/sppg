<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Header Template
        Schema::create('route_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Rute Jalur Utara"
            $table->unsignedBigInteger('default_driver_id');
            $table->time('default_departure_time'); // Jam berangkat standar (08:00)
            $table->timestamps();
        });

        // Detail Tujuan Template
        Schema::create('route_template_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_template_id');
            $table->unsignedBigInteger('school_id');
            $table->integer('default_quantity'); // Estimasi porsi standar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_template_destinations');
        Schema::dropIfExists('route_templates');
    }
};
