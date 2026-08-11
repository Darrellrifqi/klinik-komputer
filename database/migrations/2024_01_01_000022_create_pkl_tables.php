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
        Schema::create('pkl_periods', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('quarter'); // e.g. Q1, Q2, Q3, Q4
            $table->integer('year'); // e.g. 2026
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pkl_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkl_period_id')->constrained('pkl_periods')->cascadeOnDelete();
            $table->string('name');
            $table->string('school');
            $table->string('division'); // e.g. CS, Produksi, Teknisi, Administrasi, dll.
            $table->text('testimonial')->nullable(); // Kesan & Pesan
            $table->date('start_date');
            $table->date('end_date');
            $table->string('photo_path');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pkl_documentations');
        Schema::dropIfExists('pkl_students');
        Schema::dropIfExists('pkl_periods');
        Schema::enableForeignKeyConstraints();
    }
};
