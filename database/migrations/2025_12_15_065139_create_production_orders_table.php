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
   Schema::create('production_orders', function (Blueprint $table) {
    $table->id();
    $table->string('wo_number'); // WO-20240101-01
    $table->unsignedBigInteger('recipe_id');
    $table->integer('target_quantity'); // Jumlah porsi
    $table->date('production_date');
    $table->string('status'); // 'Planned', 'In Progress', 'Completed', 'Cancelled'
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
