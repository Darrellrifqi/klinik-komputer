<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'quarter',
        'year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    /**
     * Get the students belonging to this period.
     */
    public function students()
    {
        return $this->hasMany(PklStudent::class, 'pkl_period_id');
    }
}
