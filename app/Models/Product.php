<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'series', 'processor', 'ram', 'storage', 'gpu',
        'display', 'battery', 'weight', 'connectivity', 'price', 'discount_price',
        'image_path', 'description', 'features', 'is_active', 'tokopedia_url',
    ];

    protected $casts = [
        'features'       => 'array',
        'is_active'      => 'boolean',
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->price
            ? 'Rp ' . number_format($this->price, 0, ',', '.')
            : 'Hubungi Kami';
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        return $this->has_discount
            ? 'Rp ' . number_format($this->discount_price, 0, ',', '.')
            : null;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount || $this->price <= 0) {
            return 0;
        }
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
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
