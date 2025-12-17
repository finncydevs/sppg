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
    Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->date('date');
    $table->string('type'); // 'Income', 'Expense'
    $table->string('category')->nullable(); // 'Bahan Baku', 'Gaji', 'Operasional'
    $table->string('description');
    $table->decimal('amount', 15, 2);
    $table->unsignedBigInteger('reference_id')->nullable(); // Misal ID PO
    $table->string('reference_type')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
