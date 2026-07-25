<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('laptop_kit_id')->nullable()->after('customer_id');
            $table->boolean('is_tune_up')->default(false)->after('unit_type');

            $table->foreign('laptop_kit_id')->references('id')->on('procurement_laptop_kits')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['laptop_kit_id']);
            $table->dropColumn(['laptop_kit_id', 'is_tune_up']);
        });
    }
};
