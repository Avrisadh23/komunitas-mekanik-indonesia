<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        return 'https://via.placeholder.com/250x200?text=' . urlencode($this->name);
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
