<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('warranty')->nullable();
            $table->string('os')->nullable();
            $table->decimal('srp_price', 15, 2)->nullable();
            $table->string('status_stock')->default('READY');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_products');
    }
};
