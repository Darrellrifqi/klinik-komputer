<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcpPartnerSchool extends Model
{
    protected $fillable = [
        'name', 'npsn', 'city', 'province', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(InternshipApplication::class, 'acp_partner_school_id');
    }
}
