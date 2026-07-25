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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('tag')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_text_primary')->nullable();
            $table->string('cta_url_primary')->nullable();
            $table->string('cta_text_secondary')->nullable();
            $table->string('cta_url_secondary')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
