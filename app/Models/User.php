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
        'name', 'email', 'password', 'role', 'phone', 'status', 'nik',
    ];

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
    public function isProduction(): bool { return $this->role === 'produksi'; }
    public function isSuperAdmin(): bool { return $this->role === 'superadmin'; }
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
        $since = $this->created_at;
        $periodsPassed = (int) $since->diffInYears(now());
        $start = $since->copy()->addYears($periodsPassed);
        $end   = $start->copy()->addYear();
        return ['start' => $start, 'end' => $end];
    }

    /**
     * Count how many free tune-ups this member has used in the current period.
     */
    public function tuneUpCount(): int
    {
        $period = $this->tuneUpPeriod();
        return Ticket::where('customer_id', $this->id)
            ->where('is_tune_up', true)
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->count();
    }

    /**
     * Remaining free tune-ups in the current period (max 2).
     */
    public function tuneUpRemaining(): int
    {
        return max(0, 2 - $this->tuneUpCount());
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
