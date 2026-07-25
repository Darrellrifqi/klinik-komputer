<?php
// database/migrations/2026_07_23_000001_add_nik_and_custom_fields_to_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add NIK to users table
        if (!Schema::hasColumn('users', 'nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nik')->nullable()->unique()->after('id');
            });
        }

        // 2. Add institution, year, unit_model to procurement_laptop_kits
        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            if (!Schema::hasColumn('procurement_laptop_kits', 'institution')) {
                $table->string('institution')->nullable()->after('proof_of_purchase');
            }
            if (!Schema::hasColumn('procurement_laptop_kits', 'year')) {
                $table->string('year')->nullable()->after('institution');
            }
            if (!Schema::hasColumn('procurement_laptop_kits', 'unit_model')) {
                $table->string('unit_model')->nullable()->after('year');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('nik');
            });
        }

        Schema::table('procurement_laptop_kits', function (Blueprint $table) {
            $table->dropColumn(['institution', 'year', 'unit_model']);
        });
    }
};
