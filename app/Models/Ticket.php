<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'airtable_service_number', 'queue_number', 'customer_id', 'laptop_kit_id', 'customer_name',
        'customer_phone', 'unit_type', 'is_tune_up', 'is_os_install', 'brand', 'model', 'damage_description',
        'dropoff_schedule', 'status', 'sub_status', 'assigned_to', 'created_by', 'start_check_date', 'pic_name',
        'components_issue', 'cause', 'estimated_cost', 'notes',
    ];

    protected $casts = [
        'components_issue' => 'array',
        'start_check_date' => 'date',
        'is_tune_up' => 'boolean',
        'is_os_install' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function laptopKit()
    {
        return $this->belongsTo(ProcurementLaptopKit::class, 'laptop_kit_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class)->orderBy('created_at', 'desc');
    }

    public function getSubStatusLabelAttribute(): ?string
    {
        if (!in_array($this->status, ['konfirmasi_user', 'checked', 'proses_service', 'rma', 'in_service'])) {
            return null;
        }

        return match ($this->sub_status) {
            'pembelian_part'  => 'Pembelian Part',
            'klaim_garansi'   => 'Klaim Garansi',
            'menunggu_part'   => 'Menunggu Part',
            'pengerjaan_unit' => 'Pengerjaan Unit',
            default           => $this->sub_status ? ucfirst(str_replace('_', ' ', $this->sub_status)) : null,
        };
    }

    public function getIsMemberAttribute(): bool
    {
        if ($this->laptop_kit_id) {
            return true;
        }
        if ($this->customer) {
            return (bool)($this->customer->is_member ?? true);
        }
        return !empty($this->customer_id);
    }

    public function getMemberTypeLabelAttribute(): ?string
    {
        if ($this->laptop_kit_id || ($this->customer && $this->customer->has_procurement_kit)) {
            return 'Member Pengadaan';
        }
        if ($this->is_member) {
            return 'Member';
        }
        return null;
    }

    public function getDropoffScheduleAttribute($value): ?string
    {
        if (!$value) return null;

        // Clean any repeated "Jam Jam", "WIB WIB", "Jam (Jam", etc.
        $cleaned = preg_replace('/\bJam\s+Jam\b/i', 'Jam', $value);
        $cleaned = preg_replace('/\bWIB\s+WIB\b/i', 'WIB', $cleaned);
        $cleaned = preg_replace('/\(Jam\s+Jam\s+/i', '(Jam ', $cleaned);
        $cleaned = preg_replace('/\s+WIB\s+WIB\)/i', ' WIB)', $cleaned);

        return $cleaned;
    }

    public function getFullStatusLabelAttribute(): string
    {
        $main = $this->status_label;
        $sub  = $this->sub_status_label;
        return $sub ? "{$main} ({$sub})" : $main;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting'                               => 'Menunggu Unit',
            'unit_received'                         => 'Antrian Servis',
            'checking'                              => 'Pengecekan Teknisi',
            'konfirmasi_user', 'checked'            => 'Konfirmasi User',
            'proses_service', 'rma', 'in_service'   => 'Proses Service',
            'done', 'siap_diambil'                  => 'Siap Diambil',
            'sudah_diambil', 'taken'                => 'Sudah Diambil',
            'cancelled'                             => 'Dibatalkan',
            default                                 => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'waiting'                               => 'warning',
            'unit_received'                         => 'info',
            'checking'                              => 'info',
            'konfirmasi_user', 'checked'            => 'primary',
            'proses_service', 'rma', 'in_service'   => 'secondary',
            'done', 'siap_diambil'                  => 'info',
            'sudah_diambil', 'taken'                => 'success',
            'cancelled'                             => 'danger',
            default                                 => 'secondary',
        };
    }

    public function getStatusStepAttribute(): int
    {
        return match ($this->status) {
            'waiting'                               => 1,
            'unit_received'                         => 2,
            'checking'                              => 3,
            'konfirmasi_user', 'checked'            => 4,
            'proses_service', 'rma', 'in_service'   => 5,
            'done', 'siap_diambil'                  => 6,
            'sudah_diambil', 'taken'                => 7,
            'cancelled'                             => 0,
            default                                 => 1,
        };
    }

    public static function generateTicketNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "SRV-{$date}-";
        $lastTicket = self::where('ticket_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')->first();
        $seq = $lastTicket ? (int) substr($lastTicket->ticket_number, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public static function generateQueueNumber(): int
    {
        $today = now()->toDateString();
        $lastQueue = self::whereDate('created_at', $today)->max('queue_number');
        return ($lastQueue ?? 0) + 1;
    }
}
