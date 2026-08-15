<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function getActiveDivisions()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pkl_divisions')) {
                $divs = static::where('is_active', true)->orderBy('sort_order')->orderBy('name')->pluck('name')->toArray();
                if (!empty($divs)) {
                    return $divs;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to default
        }

        return ['Customer Service', 'Teknisi', 'Produksi', 'Digital Sales Media', 'Sales & Marketing', 'Admin'];
    }
}
