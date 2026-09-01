<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $appends = ['image_url', 'formatted_price'];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return file_exists(public_path($this->image_path)) ? asset($this->image_path) : null;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? 'Rp ' . number_format($this->price, 0, ',', '.') : null;
    }

    // Scope untuk mendapatkan produk yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
