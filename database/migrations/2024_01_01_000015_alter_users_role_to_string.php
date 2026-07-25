<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change role column to string to allow adding 'produksi' easily on both SQLite and MySQL
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'cs', 'teknisi', 'superadmin'])->default('customer')->change();
        });
    }
};
