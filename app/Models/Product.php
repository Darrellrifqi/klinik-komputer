<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'series', 'processor', 'ram', 'storage', 'gpu',
        'display', 'battery', 'weight', 'connectivity', 'price',
        'image_path', 'description', 'features', 'is_active', 'tokopedia_url',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return $this->price
            ? 'Rp ' . number_format($this->price, 0, ',', '.')
            : 'Hubungi Kami';
    }

    public function getSeriesLabelAttribute(): string
    {
        return match ($this->series) {
            'hype'  => 'Axioo Hype',
            'pongo' => 'Axioo Pongo',
            default => ucfirst($this->series),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImagesAttribute(): array
    {
        if (!$this->image_path) {
            return [];
        }
        
        if (str_starts_with($this->image_path, '[') || str_starts_with($this->image_path, '{')) {
            $decoded = json_decode($this->image_path, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        return [$this->image_path];
    }
}
