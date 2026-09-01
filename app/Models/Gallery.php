<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Crect fill="%23f0f0f0" width="400" height="300"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" text-anchor="middle" dy=".3em" font-family="Arial" font-size="16"%3EImage Not Found%3C/text%3E%3C/svg%3E';

        if (!$this->image_path) {
            return $placeholder;
        }

        // Cloudinary uploads are saved as a full URL already
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        // Legacy path saved by the old local-disk convention
        if (str_starts_with($this->image_path, 'storage/')) {
            return file_exists(public_path($this->image_path)) ? asset($this->image_path) : $placeholder;
        }

        // Local disk uploads
        return Storage::disk('public')->url($this->image_path);
    }

    // Scope untuk mendapatkan gallery yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
