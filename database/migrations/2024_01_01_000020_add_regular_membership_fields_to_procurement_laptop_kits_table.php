<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            // Make procurement_order_id nullable
            $table->foreignId('procurement_order_id')->nullable()->change();
            
            // Add regular customer fields
            $table->boolean('is_regular')->default(false);
            $table->string('axioo_serial_number')->nullable();
            $table->string('purchase_store')->nullable();
            $table->string('proof_of_purchase')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            $table->foreignId('procurement_order_id')->nullable(false)->change();
            $table->dropColumn(['is_regular', 'axioo_serial_number', 'purchase_store', 'proof_of_purchase']);
        });
    }
};
