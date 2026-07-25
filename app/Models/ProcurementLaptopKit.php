<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementLaptopKit extends Model
{
    protected $fillable = [
        'procurement_order_id',
        'customer_id',
        'member_id',
        'student_name',
        'status',
        'warranty_start',
        'warranty_expires',
        'is_regular',
        'axioo_serial_number',
        'purchase_store',
        'proof_of_purchase',
        'institution',
        'year',
        'unit_model',
    ];

    protected $casts = [
        'warranty_start'   => 'datetime',
        'warranty_expires' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(ProcurementOrder::class, 'procurement_order_id');
    }

    public function components()
    {
        return $this->hasMany(ProcurementKitComponent::class, 'laptop_kit_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'laptop_kit_id');
    }

    // ─── Warranty Helpers ─────────────────────────────────
    public function getWarrantyStartAttribute($value)
    {
        if ($value) return \Carbon\Carbon::parse($value);
        if ($this->status === 'activated') {
            return $this->updated_at;
        }
        return null;
    }

    public function getWarrantyExpiresAttribute($value)
    {
        if ($value) return \Carbon\Carbon::parse($value);
        if ($this->status === 'activated') {
            return $this->updated_at ? $this->updated_at->copy()->addYear() : $this->created_at->copy()->addYear();
        }
        return null;
    }

    public function getWarrantyStatusAttribute(): string
    {
        $expires = $this->warranty_expires;
        if (!$expires) return 'tidak_aktif';
        if (now()->greaterThan($expires)) return 'expired';
        
        $daysLeft = now()->diffInDays($expires, false);
        if ($daysLeft >= 0 && $daysLeft <= 30) return 'hampir_habis';
        return 'aktif';
    }

    public function getWarrantyDaysLeftAttribute(): int
    {
        $expires = $this->warranty_expires;
        if (!$expires) return 0;
        return (int) max(0, now()->diffInDays($expires, false));
    }

    public function getWarrantyStatusLabelAttribute(): string
    {
        return match($this->warranty_status) {
            'aktif'        => 'Garansi Aktif',
            'hampir_habis' => 'Garansi Hampir Habis',
            'expired'      => 'Garansi Habis',
            default        => 'Tidak Ada Garansi',
        };
    }

    public function getWarrantyStatusColorAttribute(): string
    {
        return match($this->warranty_status) {
            'aktif'        => 'success',
            'hampir_habis' => 'warning',
            'expired'      => 'danger',
            default        => 'secondary',
        };
    }

    public static function generateMemberId(string $schoolPrefix = 'MBR'): string
    {
        $date = now()->format('ymd');
        $prefix = "{$schoolPrefix}-{$date}-";
        $last = static::where('member_id', 'like', "{$prefix}%")
            ->orderBy('member_id', 'desc')
            ->first();
        $seq = $last ? (int) substr($last->member_id, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
