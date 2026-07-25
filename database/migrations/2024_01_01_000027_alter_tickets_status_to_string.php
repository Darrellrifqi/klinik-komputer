<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tickets')) {
            // For MySQL, change status column to string(50) so any status value fits
            try {
                DB::statement("ALTER TABLE tickets MODIFY status VARCHAR(50) NOT NULL DEFAULT 'waiting'");
            } catch (\Throwable $e) {
                // Ignore if driver doesn't support raw modify
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tickets')) {
            try {
                DB::statement("ALTER TABLE tickets MODIFY status VARCHAR(20) NOT NULL DEFAULT 'waiting'");
            } catch (\Throwable $e) {
                // Ignore
            }
        }
    }
};
