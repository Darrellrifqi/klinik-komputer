<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Ticket;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'status', 'nik', 'avatar',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=15803d&color=ffffff&bold=true';
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isCustomer(): bool { return $this->role === 'customer'; }
    public function isCs(): bool { return $this->role === 'cs'; }
    public function isTechnician(): bool { return $this->role === 'teknisi'; }
    public function isTeknisi(): bool { return $this->role === 'teknisi'; }
    public function isProduction(): bool { return $this->role === 'produksi'; }
    public function isProduksi(): bool { return $this->role === 'produksi'; }
    public function isSuperAdmin(): bool { return $this->role === 'superadmin'; }
    public function isAdmin(): bool { return $this->role === 'superadmin'; }
    public function isActive(): bool { return $this->status === 'active'; }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function laptopKits()
    {
        return $this->hasMany(ProcurementLaptopKit::class, 'customer_id');
    }

    /**
     * Get the active tune-up period window for this member.
     * Resets every 1 year from the member's created_at date.
     * Returns ['start' => Carbon, 'end' => Carbon]
     */
    public function tuneUpPeriod(): array
    {
        $since = $this->created_at ?: now();
        $periodsPassed = (int) $since->diffInYears(now());
        $start = $since->copy()->addYears($periodsPassed);
        $end   = $start->copy()->addYear();
        return ['start' => $start, 'end' => $end];
    }

    public function tuneUpResetDate()
    {
        return $this->tuneUpPeriod()['end'];
    }

    /**
     * Count how many free tune-ups (Deep Care Cleaning) this member has used in the current period.
     */
    public function tuneUpCount(): int
    {
        $period = $this->tuneUpPeriod();
        return Ticket::where('customer_id', $this->id)
            ->where('is_tune_up', true)
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->count();
    }

    public function getMembershipPlanAttribute(): ?string
    {
        $kit = ProcurementLaptopKit::where('customer_id', $this->id)
            ->where('is_regular', true)
            ->first();
        return $kit ? $kit->membership_plan : null;
    }

    public function getMembershipPlanLabelAttribute(): string
    {
        $plan = $this->membership_plan;
        if ($plan) {
            return $plan;
        }
        if ($this->has_procurement_kit) {
            return 'Member Pengadaan Sekolah';
        }
        if ($this->is_regular_member) {
            return 'Member Mandiri';
        }
        return 'Non-Member';
    }

    public function getCleaningQuotaMaxAttribute(): int
    {
        $plan = $this->membership_plan;
        return match($plan) {
            'Basic Priority'    => 2,
            'Silver Priority'   => 2,
            'Gold Priority'     => 3,
            'Platinum Priority' => 4,
            default             => 2,
        };
    }

    public function getOsQuotaMaxAttribute(): int
    {
        $plan = $this->membership_plan;
        return match($plan) {
            'Basic Priority'    => 1,
            'Silver Priority'   => 2,
            'Gold Priority'     => 3,
            'Platinum Priority' => 4,
            default             => 2,
        };
    }

    /**
     * Remaining free tune-ups (Deep Care Cleaning) in the current period.
     */
    public function tuneUpRemaining(): int
    {
        return max(0, $this->cleaning_quota_max - $this->tuneUpCount());
    }

    /**
     * Count how many free Essential OS installations this member has used in the current period.
     */
    public function osInstallCount(): int
    {
        $period = $this->tuneUpPeriod();
        return Ticket::where('customer_id', $this->id)
            ->where(function($q) {
                $q->where('is_os_install', true)
                  ->orWhere('damage_description', 'LIKE', '%Essential Instalasi OS%');
            })
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->count();
    }

    /**
     * Remaining free Essential OS installations in the current period.
     */
    public function osInstallRemaining(): int
    {
        return max(0, $this->os_quota_max - $this->osInstallCount());
    }

    public function getHasProcurementKitAttribute(): bool
    {
        return ProcurementLaptopKit::where('customer_id', $this->id)->where('is_regular', false)->exists();
    }

    public function getIsRegularMemberAttribute(): bool
    {
        return !empty($this->nik) || ProcurementLaptopKit::where('customer_id', $this->id)->where('is_regular', true)->exists();
    }

    public function getIsMemberAttribute(): bool
    {
        return $this->has_procurement_kit || $this->is_regular_member;
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function ticketHistories()
    {
        return $this->hasMany(TicketHistory::class);
    }
}
