<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            if (!Schema::hasColumn('procurement_laptop_kits', 'membership_plan')) {
                $table->string('membership_plan')->nullable()->after('proof_of_purchase');
            }
            if (!Schema::hasColumn('procurement_laptop_kits', 'membership_price')) {
                $table->decimal('membership_price', 12, 2)->nullable()->after('membership_plan');
            }
            if (!Schema::hasColumn('procurement_laptop_kits', 'membership_duration')) {
                $table->integer('membership_duration')->nullable()->after('membership_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            $table->dropColumn(['membership_plan', 'membership_price', 'membership_duration']);
        });
    }
};
