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
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('img');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('specialist_ar')->nullable();
            $table->string('specialist_en')->nullable();
            $table->text('desc_ar');
            $table->text('desc_en');
            $table->string('phone');
            $table->string('whats');
            $table->integer('age');
            $table->enum('sex', ['male', 'female']);
            $table->string('day_en');
            $table->string('day_ar');
            $table->time('time_from');
            $table->time('time_to');
            $table->json('certificate_ar');
            $table->json('certificate_en');
            $table->json('exp_ar');
            $table->json('exp_en');
            $table->string('cv')->nullable();  // ملف السيرة الذاتية
            $table->boolean('active')->default(true);
            $table->boolean('show')->default(true);
            $table->string('floor');

            // العلاقة مع التخصص (specialist)
            $table->foreignId('specialist_id')->constrained('specialists')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
