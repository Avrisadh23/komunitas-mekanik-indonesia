<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bengkel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'description',
        'image_path',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $fallback = 'https://via.placeholder.com/250x200?text=' . urlencode($this->name);

        if (!$this->image_path) {
            return $fallback;
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return file_exists(public_path($this->image_path)) ? asset($this->image_path) : $fallback;
        }

        return Storage::disk(config('filesystems.uploads'))->url($this->image_path);
    }

    // Scope untuk mendapatkan bengkel yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter by province
    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    // Scope untuk filter by city
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    // Get unique provinces untuk dropdown
    public static function getProvinces()
    {
        return self::active()->distinct()->pluck('province')->sort();
    }

    // Get cities by province
    public static function getCitiesByProvince($province)
    {
        $cities = self::active()
            ->where('province', $province)
            ->select('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values(); // Reset array keys
        
        return $cities->toArray();
    }
}
