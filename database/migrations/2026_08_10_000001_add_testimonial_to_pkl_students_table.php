<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pkl_students') && !Schema::hasColumn('pkl_students', 'testimonial')) {
            Schema::table('pkl_students', function (Blueprint $table) {
                $table->text('testimonial')->nullable()->after('division');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pkl_students') && Schema::hasColumn('pkl_students', 'testimonial')) {
            Schema::table('pkl_students', function (Blueprint $table) {
                $table->dropColumn('testimonial');
            });
        }
    }
};
