<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_plans', function (Blueprint $table) {
            $table->id();
            $table->date('planned_date'); // Tanggal rencana
            $table->unsignedBigInteger('recipe_id'); // Menu apa?
            $table->string('meal_time')->default('Siang'); // Pagi, Siang, Sore (Fleksibel)
            $table->integer('portion_target')->default(0); // Rencana porsi
            $table->boolean('is_published')->default(false); // Sudah jadi WO atau belum?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_plans');
    }
};
