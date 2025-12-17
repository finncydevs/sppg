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
     Schema::create('salaries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('employee_id');
    $table->string('period'); // '2024-01'
    $table->decimal('basic_salary', 15, 2);
    $table->decimal('allowance', 15, 2);
    $table->decimal('deduction', 15, 2);
    $table->decimal('net_salary', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
