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
        Schema::create('lab', function (Blueprint $table) {
            $table->id();
            $table->text('name_ar');
            $table->text('name_en');
            $table->string('phone');
            $table->string('whats');
            $table->string('day_en');
            $table->string('day_ar');
            $table->time('time_from');
            $table->time('time_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab');
    }
};
