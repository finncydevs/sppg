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
      Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable(); // Link ke user login (opsional)
    $table->string('name');
    $table->string('position'); // Jabatan: 'Supir', 'Koki', dll
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->date('join_date');
    $table->string('status')->default('Aktif'); // Aktif/Nonaktif
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
