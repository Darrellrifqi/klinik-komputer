<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tickets') && !Schema::hasColumn('tickets', 'airtable_service_number')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('airtable_service_number')->nullable()->after('ticket_number')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'airtable_service_number')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('airtable_service_number');
            });
        }
    }
};
