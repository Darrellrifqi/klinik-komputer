<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'airtable_service_number', 'queue_number', 'customer_id', 'laptop_kit_id', 'customer_name',
        'customer_phone', 'unit_type', 'is_tune_up', 'brand', 'model', 'damage_description',
        'dropoff_schedule', 'status', 'assigned_to', 'created_by', 'start_check_date', 'pic_name',
        'components_issue', 'cause', 'estimated_cost', 'notes',
    ];

    protected $casts = [
        'components_issue' => 'array',
        'start_check_date' => 'date',
        'is_tune_up' => 'boolean',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting'                               => 'Menunggu',
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
            'checking'                              => 2,
            'konfirmasi_user', 'checked'            => 3,
            'proses_service', 'rma', 'in_service'   => 4,
            'done', 'siap_diambil'                  => 5,
            'sudah_diambil', 'taken'                => 6,
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
