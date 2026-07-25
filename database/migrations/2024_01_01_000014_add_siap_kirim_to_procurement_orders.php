<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'siap_kirim' status to procurement_orders enum
        DB::statement("ALTER TABLE procurement_orders MODIFY COLUMN status ENUM(
            'pending',
            'diproses',
            'siap_kirim',
            'konfirmasi_harga',
            'menunggu_pembayaran',
            'dibayar',
            'diproses_pengiriman',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE procurement_orders MODIFY COLUMN status ENUM(
            'pending',
            'diproses',
            'konfirmasi_harga',
            'menunggu_pembayaran',
            'dibayar',
            'diproses_pengiriman',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'pending'");
    }
};
