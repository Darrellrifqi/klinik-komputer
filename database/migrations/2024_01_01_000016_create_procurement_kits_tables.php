<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_laptop_kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_order_id')->constrained('procurement_orders')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('member_id')->unique(); // MBR-XXXX-XXXX
            $table->string('student_name')->nullable(); // Claimed by student
            $table->string('status')->default('assembly'); // assembly, ready, activated
            $table->timestamps();
        });

        Schema::create('procurement_kit_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_kit_id')->constrained('procurement_laptop_kits')->onDelete('cascade');
            $table->string('component_name'); // Motherboard, RAM, SSD, Screen, Battery
            $table->string('serial_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_kit_components');
        Schema::dropIfExists('procurement_laptop_kits');
    }
};
