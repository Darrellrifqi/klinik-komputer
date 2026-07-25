<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementOrder extends Model
{
    protected $fillable = [
        'order_number',
        'school_name',
        'school_address',
        'school_city',
        'school_type',
        'pic_name',
        'pic_position',
        'pic_phone',
        'pic_email',
        'axioo_series',
        'axioo_model',
        'items',
        'total_units',
        'usage_purpose',
        'notes',
        'status',
        'quoted_price',
        'admin_notes',
        'is_tkdn',
        'substep_penawaran',
        'substep_invoice',
        'substep_pembayaran',
    ];

    protected $casts = [
        'items'              => 'array',
        'quoted_price'       => 'decimal:2',
        'is_tkdn'            => 'boolean',
        'substep_penawaran'  => 'boolean',
        'substep_invoice'    => 'boolean',
        'substep_pembayaran' => 'boolean',
    ];

    // ─── Sub-step Accessors ────────────────────────────────────────
    public function getSubstepPenawaranActiveAttribute(): bool
    {
        if ($this->quoted_price && $this->quoted_price > 0) {
            return true;
        }
        return (bool) ($this->attributes['substep_penawaran'] ?? false);
    }

    public function getSubstepInvoiceActiveAttribute(): bool
    {
        return (bool) ($this->attributes['substep_invoice'] ?? false);
    }

    public function getSubstepPembayaranActiveAttribute(): bool
    {
        return (bool) ($this->attributes['substep_pembayaran'] ?? false);
    }

    // ─── Accessors ────────────────────────────────────────
    public function getSchoolTypeLabelAttribute(): string
    {
        return match($this->school_type) {
            'sd'               => 'SD / MI',
            'smp'              => 'SMP / MTs',
            'sma'              => 'SMA / MA',
            'smk'              => 'SMK',
            'perguruan_tinggi' => 'Perguruan Tinggi',
            'instansi_lain'    => 'Instansi Lain',
            default            => ucfirst($this->school_type),
        };
    }

    public function getUsagePurposeLabelAttribute(): string
    {
        return match($this->usage_purpose) {
            'cbt_ujian'       => 'Ujian CBT / Mandiri',
            'lab_komputer'    => 'Laboratorium Komputer',
            'pembelajaran'    => 'Kegiatan Pembelajaran',
            'administrasi'    => 'Administrasi Sekolah',
            'lainnya'         => 'Penggunaan Lainnya',
            default           => ucfirst($this->usage_purpose),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'                => 'Menunggu Konfirmasi',
            'diproses'               => 'Unit Diproses',
            'siap_kirim'             => 'Unit Siap Dikirim',
            'konfirmasi_harga'       => 'Penawaran Harga',
            'menunggu_pembayaran'    => 'Menunggu Pembayaran',
            'dibayar'                => 'Sudah Dibayar',
            'diproses_pengiriman'    => 'Unit Dikirim',
            'selesai'                => 'Selesai',
            'dibatalkan'             => 'Dibatalkan',
            default                  => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'             => 'warning',
            'diproses'            => 'info',
            'siap_kirim'          => 'accent',
            'konfirmasi_harga'    => 'accent',
            'menunggu_pembayaran' => 'warning',
            'dibayar'             => 'primary',
            'diproses_pengiriman' => 'info',
            'selesai'             => 'success',
            'dibatalkan'          => 'danger',
            default               => 'secondary',
        };
    }

    public function getFormattedQuotedPriceAttribute(): string
    {
        if (!$this->quoted_price) return 'Belum ditentukan';
        return 'Rp ' . number_format($this->quoted_price, 0, ',', '.');
    }

    // ─── Generate Order Number ────────────────────────────
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "PRC-{$date}-";
        $last = static::where('order_number', 'like', "{$prefix}%")
            ->orderBy('order_number', 'desc')
            ->first();
        $seq = $last ? (int) substr($last->order_number, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function kits()
    {
        return $this->hasMany(ProcurementLaptopKit::class, 'procurement_order_id');
    }
}
