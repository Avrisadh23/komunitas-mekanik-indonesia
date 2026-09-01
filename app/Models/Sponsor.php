<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'url',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        $fallback = asset('/frontend/asset/images/logo-pertamina.jpeg');

        if (!$this->logo_path) {
            return $fallback;
        }

        if (str_starts_with($this->logo_path, 'http://') || str_starts_with($this->logo_path, 'https://')) {
            return $this->logo_path;
        }

        if (str_starts_with($this->logo_path, 'storage/')) {
            return file_exists(public_path($this->logo_path)) ? asset($this->logo_path) : $fallback;
        }

        return Storage::disk('public')->url($this->logo_path);
    }

    // Scope untuk mendapatkan sponsor yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
