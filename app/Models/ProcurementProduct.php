<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementProduct extends Model
{
    protected $fillable = [
        'product_name',
        'color',
        'size',
        'cpu',
        'ram',
        'storage',
        'warranty',
        'os',
        'srp_price',
        'status_stock',
        'category',
        'is_active',
    ];

    protected $casts = [
        'srp_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeRetail($query)
    {
        return $query->where('category', 'retail');
    }

    public function scopeTkdn($query)
    {
        return $query->where('category', 'tkdn');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'HUBUNGI CS';
    }

    public function getFullNameLabelAttribute(): string
    {
        $parts = [$this->product_name];

        $specs = array_filter([
            $this->cpu,
            $this->ram ? 'RAM ' . $this->ram : null,
            $this->storage,
            $this->os && strtolower($this->os) !== 'x' ? 'OS ' . $this->os : null,
        ]);

        if (!empty($specs)) {
            $parts[] = '(' . implode(' / ', $specs) . ')';
        }

        $parts[] = '— HUBUNGI CS';

        return implode(' ', $parts);
    }
}
