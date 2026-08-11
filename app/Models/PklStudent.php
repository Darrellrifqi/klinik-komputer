<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PklStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'pkl_period_id',
        'name',
        'school',
        'division',
        'testimonial',
        'start_date',
        'end_date',
        'photo_path',
        'status', // pending, approved, rejected
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the period this student belongs to.
     */
    public function period()
    {
        return $this->belongsTo(PklPeriod::class, 'pkl_period_id');
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return Storage::url($this->photo_path);
        }
        return '/images/default-avatar.png'; // Fallback if no avatar exists
    }
}
