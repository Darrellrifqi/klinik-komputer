<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('procurement_orders', 'substep_penawaran')) {
                $table->boolean('substep_penawaran')->default(false)->after('status');
            }
            if (!Schema::hasColumn('procurement_orders', 'substep_invoice')) {
                $table->boolean('substep_invoice')->default(false)->after('substep_penawaran');
            }
            if (!Schema::hasColumn('procurement_orders', 'substep_pembayaran')) {
                $table->boolean('substep_pembayaran')->default(false)->after('substep_invoice');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procurement_orders', function (Blueprint $table) {
            $table->dropColumn(['substep_penawaran', 'substep_invoice', 'substep_pembayaran']);
        });
    }
};
