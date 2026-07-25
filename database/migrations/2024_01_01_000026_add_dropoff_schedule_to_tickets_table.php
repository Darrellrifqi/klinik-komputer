<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tickets') && !Schema::hasColumn('tickets', 'dropoff_schedule')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('dropoff_schedule')->nullable()->after('damage_description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'dropoff_schedule')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('dropoff_schedule');
            });
        }
    }
};
