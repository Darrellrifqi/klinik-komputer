<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'discount_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('discount_price', 12, 2)->nullable()->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'discount_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('discount_price');
            });
        }
    }
};
