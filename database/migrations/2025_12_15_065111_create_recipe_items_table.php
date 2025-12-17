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
     Schema::create('recipe_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('recipe_id');
    $table->unsignedBigInteger('item_id');
    $table->decimal('quantity', 10, 4); // Jumlah kebutuhan per porsi
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_items');
    }
};
