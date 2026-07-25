<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            $table->timestamp('warranty_start')->nullable();
            $table->timestamp('warranty_expires')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            $table->dropColumn(['warranty_start', 'warranty_expires']);
        });
    }
};
