<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    protected $fillable = [
        'acp_partner_school_id', 'school_name', 'contact_person', 'phone',
        'email', 'student_count', 'start_date', 'end_date', 'proposal_file',
        'status', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(AcpPartnerSchool::class, 'acp_partner_school_id');
    }
}
