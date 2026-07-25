<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // PRC-YYYYMMDD-XXXX
            
            // Data institusi
            $table->string('school_name');
            $table->string('school_address');
            $table->string('school_city');
            $table->enum('school_type', ['sd', 'smp', 'sma', 'smk', 'perguruan_tinggi', 'instansi_lain']);
            
            // Data PIC (perwakilan)
            $table->string('pic_name');
            $table->string('pic_position'); // Jabatan
            $table->string('pic_phone');    // WA
            $table->string('pic_email');
            
            // Detail pengadaan
            $table->string('axioo_series');   // hype / pongo
            $table->string('axioo_model');    // nama spesifik produk
            $table->integer('total_units');
            $table->enum('usage_purpose', ['cbt_ujian', 'lab_komputer', 'pembelajaran', 'administrasi', 'lainnya']);
            $table->text('notes')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'diproses', 'konfirmasi_harga', 'menunggu_pembayaran', 'dibayar', 'diproses_pengiriman', 'selesai', 'dibatalkan'])->default('pending');
            $table->decimal('quoted_price', 15, 2)->nullable(); // Harga penawaran dari admin
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_orders');
    }
};
