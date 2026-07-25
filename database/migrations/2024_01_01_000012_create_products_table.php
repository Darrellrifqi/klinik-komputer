<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('series', ['hype', 'pongo'])->default('hype');
            $table->string('processor');
            $table->string('ram');
            $table->string('storage');
            $table->string('gpu')->nullable();
            $table->string('display');
            $table->string('battery')->nullable();
            $table->string('weight')->nullable();
            $table->string('connectivity')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
