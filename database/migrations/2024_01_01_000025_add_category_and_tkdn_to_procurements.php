<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('procurement_products') && !Schema::hasColumn('procurement_products', 'category')) {
            Schema::table('procurement_products', function (Blueprint $table) {
                $table->string('category')->default('retail')->after('status_stock');
            });
        }

        if (Schema::hasTable('procurement_orders') && !Schema::hasColumn('procurement_orders', 'is_tkdn')) {
            Schema::table('procurement_orders', function (Blueprint $table) {
                $table->boolean('is_tkdn')->default(false)->after('total_units');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('procurement_products') && Schema::hasColumn('procurement_products', 'category')) {
            Schema::table('procurement_products', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasTable('procurement_orders') && Schema::hasColumn('procurement_orders', 'is_tkdn')) {
            Schema::table('procurement_orders', function (Blueprint $table) {
                $table->dropColumn('is_tkdn');
            });
        }
    }
};
