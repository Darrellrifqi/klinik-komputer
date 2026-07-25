<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementKitComponent extends Model
{
    protected $fillable = [
        'laptop_kit_id',
        'component_name',
        'serial_number',
    ];

    public function kit()
    {
        return $this->belongsTo(ProcurementLaptopKit::class, 'laptop_kit_id');
    }
}
