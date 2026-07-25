<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PklDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'pkl_student_id',
        'image_path',
        'title',
        'description',
    ];

    /**
     * Get the student this documentation belongs to.
     */
    public function student()
    {
        return $this->belongsTo(PklStudent::class, 'pkl_student_id');
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }
        return '';
    }
}
