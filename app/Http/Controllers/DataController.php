<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Gallery;
use App\Models\Sponsor;
use App\Models\Product;
use App\Models\Bengkel;

class DataController extends Controller
{
    public function getStats()
    {
        // Tanggal berdiri KMI: 26 September 2016
        $foundedDate = Carbon::createFromDate(2016, 9, 26);
        $now = Carbon::now();
        
        $totalDays = $now->diffInDays($foundedDate);
        $years = intdiv($totalDays, 365);
        $remainingDays = $totalDays % 365;
        $months = intdiv($remainingDays, 30);
        $days = $remainingDays % 30;
        
        return response()->json([
            'years' => $years,
            'months' => $months,
            'days' => $days
        ]);
    }

    public function getGalleryItems()
    {
        // Mengambil data dari database saja, tanpa fallback
        $items = Gallery::active()->get()->toArray();
        return response()->json($items);
    }

    public function getSponsorItems()
    {
        // Mengambil data dari database saja, tanpa fallback
        $sponsors = Sponsor::active()->get()->toArray();
        return response()->json($sponsors);
    }

    public function getProductItems()
    {
        // Mengambil data produk dari database saja, tanpa fallback
        $products = Product::active()->get()->toArray();
        return response()->json($products);
    }

    public function getBengkelData()
    {
        // Data bengkel rekanan per provinsi dari database
        $bengkels = Bengkel::active()->orderBy('province')->orderBy('city')->get();
        
        // Group by province
        $bengkelData = [];
        foreach ($bengkels as $bengkel) {
            if (!isset($bengkelData[$bengkel->province])) {
                $bengkelData[$bengkel->province] = [];
            }
            
            $bengkelData[$bengkel->province][] = [
                'id' => $bengkel->id,
                'nama' => $bengkel->name,
                'pemilik' => $bengkel->owner,
                'alamat' => $bengkel->address,
                'kota' => $bengkel->city,
                'telepon' => $bengkel->phone,
                'email' => $bengkel->email,
                'deskripsi' => $bengkel->description,
                'image_url' => $bengkel->image_url,
            ];
        }
        
        // Jika database kosong, return struktur default dengan beberapa data contoh
        if (empty($bengkelData)) {
            $bengkelData = [
                'Jawa Barat' => [
                    [
                        'nama' => 'Bengkel Mitra Jaya',
                        'alamat' => 'Jl. Raya Bandung No. 123',
                        'kota' => 'Bandung',
                        'telepon' => '0274-123456',
                        'pemilik' => 'Budi Santoso'
                    ],
                ],
            ];
        }
        
        return response()->json($bengkelData);
    }
}
