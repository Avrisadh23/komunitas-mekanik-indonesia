<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\Sponsor;
use App\Models\Product;
use App\Models\Bengkel;

class KMISeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Existing image files in public/storage/galleries
        $images = [
            'storage/galleries/ROMVGHHtSNngGyCXchWEfzmeZHIiZ2UOmFHILYml.jpg',
            'storage/galleries/xeD4tNzVsf4fQzzYYXk5E7zn1iZLxaZ8BMn0wG0a.jpg',
            'storage/galleries/c5nZLE8IaVcaL1SirTXjrAoQEG13SO6O1VT0dJMP.jpg',
            'storage/galleries/Zx2NTBrxgLkhfPa7toOmERZQwbk7h51eMBkIbyoa.jpg',
            'storage/galleries/D7Sd6k97Ai1IeIw6fkKce8sWo6YJVY4zt5lMqZAj.jpg',
            'storage/galleries/iTymYaXNHkrkuc53a79Fglw4rhT6P1H2mnqxZq0p.jpg',
            'storage/galleries/62GEZZZStPsu8pah8TK9Qj9x1YruZagWC650dF0Y.jpg',
            'storage/galleries/rBI4mNSlhvmMRLbkxMjEPYBfYCzH6ilyTNgoEiu2.jpg',
            'storage/galleries/cUr7xO71Bg4hEdhWjwloAM2fv8zZABsS8ViOoiCY.jpg',
        ];

        // Seed Galleries - 9 items dengan gambar
        $galleryTitles = [
            'Munas KMI',
            'Pertemuan Regional',
            'Sosialisasi Produk',
            'Unjungan Regional',
            'Pelatihan Mekanik',
            'Gathering Anggota',
            'Kopdarnas KMI',
            'Raker Tahunan',
            'Evaluasi Program'
        ];

        foreach ($galleryTitles as $idx => $title) {
            Gallery::create([
                'title' => $title,
                'description' => 'Kegiatan penting dalam program KMI untuk meningkatkan koordinasi, sinergi, dan pemberdayaan anggota komunitas mekanik Indonesia.',
                'image_path' => $images[$idx] ?? null,
                'order' => $idx + 1,
                'is_active' => true,
            ]);
        }

        // Seed Sponsors
        Sponsor::create([
            'name' => 'Pertamina',
            'description' => 'Sponsor utama dari industri energi Indonesia',
            'url' => '#',
            'order' => 1,
            'is_active' => true,
        ]);

        Sponsor::create([
            'name' => 'RKN',
            'description' => 'Mitra terpercaya untuk automotive solutions',
            'url' => '#',
            'order' => 2,
            'is_active' => true,
        ]);

        Sponsor::create([
            'name' => 'Shield',
            'description' => 'Penyedia solusi proteksi kendaraan',
            'url' => '#',
            'order' => 3,
            'is_active' => true,
        ]);

        // Seed Products
        Product::create([
            'name' => 'Oli Mesin SAE 10W-30',
            'description' => 'Oli mesin berkualitas tinggi untuk performa optimal',
            'category' => 'spare-part',
            'price' => 46000,
            'order' => 1,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Filter Oli',
            'description' => 'Filter oli original dengan daya saringan tinggi',
            'category' => 'spare-part',
            'price' => 25000,
            'order' => 2,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Kampas Rem Depan',
            'description' => 'Kampas rem berkualitas dengan keausan rendah',
            'category' => 'spare-part',
            'price' => 150000,
            'order' => 3,
            'is_active' => true,
        ]);

        // Seed Bengkels - Sample data dari beberapa provinsi
        $bengkelData = [
            // Jawa Barat - 6 kota
            ['nama' => 'Bengkel Mitra Jaya', 'pemilik' => 'Budi Santoso', 'provinsi' => 'Jawa Barat', 'kota' => 'Bandung', 'alamat' => 'Jl. Raya Bandung No. 123', 'telepon' => '0274-123456'],
            ['nama' => 'Bengkel Profesional Auto', 'pemilik' => 'Ahmad Wijaya', 'provinsi' => 'Jawa Barat', 'kota' => 'Cimahi', 'alamat' => 'Jl. Merdeka No. 456', 'telepon' => '0274-234567'],
            ['nama' => 'Bengkel Prima Motor', 'pemilik' => 'Siti Nurhaliza', 'provinsi' => 'Jawa Barat', 'kota' => 'Bandung', 'alamat' => 'Jl. Gatot Subroto No. 789', 'telepon' => '0274-345678'],
            ['nama' => 'Bengkel Bogor Sejati', 'pemilik' => 'Endi Suryana', 'provinsi' => 'Jawa Barat', 'kota' => 'Bogor', 'alamat' => 'Jl. Siliwangi No. 101', 'telepon' => '0251-123456'],
            ['nama' => 'Bengkel Sukabumi Raya', 'pemilik' => 'Fajri Ramadhan', 'provinsi' => 'Jawa Barat', 'kota' => 'Sukabumi', 'alamat' => 'Jl. Pelabuhan No. 202', 'telepon' => '0266-234567'],
            ['nama' => 'Bengkel Depok Motor', 'pemilik' => 'Gusdi Permana', 'provinsi' => 'Jawa Barat', 'kota' => 'Depok', 'alamat' => 'Jl. Margonda No. 303', 'telepon' => '021-345678'],
            
            // Jawa Tengah - 8 kota
            ['nama' => 'Bengkel Central Repair', 'pemilik' => 'Rido Hermanto', 'provinsi' => 'Jawa Tengah', 'kota' => 'Semarang', 'alamat' => 'Jl. Diponegoro No. 234', 'telepon' => '024-123456'],
            ['nama' => 'Bengkel Mesin Jaya', 'pemilik' => 'Doni Kusuma', 'provinsi' => 'Jawa Tengah', 'kota' => 'Semarang', 'alamat' => 'Jl. Ahmad Yani No. 567', 'telepon' => '024-234567'],
            ['nama' => 'Bengkel Solo Raya', 'pemilik' => 'Hendra Putra', 'provinsi' => 'Jawa Tengah', 'kota' => 'Solo', 'alamat' => 'Jl. Gajah Mada No. 890', 'telepon' => '0271-345678'],
            ['nama' => 'Bengkel Yogyakarta Teknik', 'pemilik' => 'Iwan Setiawan', 'provinsi' => 'Jawa Tengah', 'kota' => 'Yogyakarta', 'alamat' => 'Jl. Malioboro No. 404', 'telepon' => '0274-456789'],
            ['nama' => 'Bengkel Pekalongan Jaya', 'pemilik' => 'Jumadi Santoso', 'provinsi' => 'Jawa Tengah', 'kota' => 'Pekalongan', 'alamat' => 'Jl. Hayam Wuruk No. 505', 'telepon' => '0285-567890'],
            ['nama' => 'Bengkel Tegal Motor', 'pemilik' => 'Khalis Rohman', 'provinsi' => 'Jawa Tengah', 'kota' => 'Tegal', 'alamat' => 'Jl. Gatot Subroto No. 606', 'telepon' => '0283-678901'],
            ['nama' => 'Bengkel Salatiga Rapi', 'pemilik' => 'Luki Haryanto', 'provinsi' => 'Jawa Tengah', 'kota' => 'Salatiga', 'alamat' => 'Jl. Dieng No. 707', 'telepon' => '0298-789012'],
            ['nama' => 'Bengkel Magelang Hebat', 'pemilik' => 'Mansyur Wijaya', 'provinsi' => 'Jawa Tengah', 'kota' => 'Magelang', 'alamat' => 'Jl. Ahmad Yani No. 808', 'telepon' => '0293-890123'],
            
            // Jawa Timur - 6 kota
            ['nama' => 'Bengkel Surabaya Teknik', 'pemilik' => 'Eka Prasetya', 'provinsi' => 'Jawa Timur', 'kota' => 'Surabaya', 'alamat' => 'Jl. Ahmad Yani No. 321', 'telepon' => '031-123456'],
            ['nama' => 'Bengkel Malang Motor', 'pemilik' => 'Bambang Irawan', 'provinsi' => 'Jawa Timur', 'kota' => 'Malang', 'alamat' => 'Jl. Ijen No. 654', 'telepon' => '0341-234567'],
            ['nama' => 'Bengkel Sidoarjo Maju', 'pemilik' => 'Cahyo Wibowo', 'provinsi' => 'Jawa Timur', 'kota' => 'Sidoarjo', 'alamat' => 'Jl. Sudirman No. 909', 'telepon' => '031-345678'],
            ['nama' => 'Bengkel Gresik Profesional', 'pemilik' => 'Dian Permata', 'provinsi' => 'Jawa Timur', 'kota' => 'Gresik', 'alamat' => 'Jl. Raya Gresik No. 1010', 'telepon' => '031-456789'],
            ['nama' => 'Bengkel Pasuruan Auto', 'pemilik' => 'Eka Suhendra', 'provinsi' => 'Jawa Timur', 'kota' => 'Pasuruan', 'alamat' => 'Jl. Merdeka No. 1111', 'telepon' => '0343-567890'],
            ['nama' => 'Bengkel Blitar Jaya', 'pemilik' => 'Fajar Gunawan', 'provinsi' => 'Jawa Timur', 'kota' => 'Blitar', 'alamat' => 'Jl. Diponegoro No. 1212', 'telepon' => '0342-678901'],
            
            // DKI Jakarta - 5 kota administratif
            ['nama' => 'Bengkel Jakarta Pusat', 'pemilik' => 'Firman Setiawan', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Pusat', 'alamat' => 'Jl. Sudirman No. 111', 'telepon' => '021-123456'],
            ['nama' => 'Bengkel Jakarta Utara', 'pemilik' => 'Hendra Wijaya', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Utara', 'alamat' => 'Jl. Yos Sudarso No. 222', 'telepon' => '021-234567'],
            ['nama' => 'Bengkel Jakarta Timur', 'pemilik' => 'Rudi Hartono', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Timur', 'alamat' => 'Jl. Mayjen Sungkono No. 333', 'telepon' => '021-345678'],
            ['nama' => 'Bengkel Jakarta Barat', 'pemilik' => 'Agus Suryanto', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Barat', 'alamat' => 'Jl. Raya Kalideres No. 444', 'telepon' => '021-456789'],
            ['nama' => 'Bengkel Jakarta Selatan', 'pemilik' => 'Bambang Hermawan', 'provinsi' => 'DKI Jakarta', 'kota' => 'Jakarta Selatan', 'alamat' => 'Jl. Cilandak No. 555', 'telepon' => '021-567890'],
            
            // Banten - 4 kota
            ['nama' => 'Bengkel Bekasi Maju', 'pemilik' => 'Giat Nugraha', 'provinsi' => 'Banten', 'kota' => 'Bekasi', 'alamat' => 'Jl. Raya Bekasi No. 222', 'telepon' => '021-234567'],
            ['nama' => 'Bengkel Tangerang Raya', 'pemilik' => 'Hail Rahman', 'provinsi' => 'Banten', 'kota' => 'Tangerang', 'alamat' => 'Jl. Sudirman No. 666', 'telepon' => '021-678901'],
            ['nama' => 'Bengkel Serang Hebat', 'pemilik' => 'Indra Kusuma', 'provinsi' => 'Banten', 'kota' => 'Serang', 'alamat' => 'Jl. Ahmad Yani No. 777', 'telepon' => '0254-789012'],
            ['nama' => 'Bengkel Cilegon Motor', 'pemilik' => 'Joko Susilo', 'provinsi' => 'Banten', 'kota' => 'Cilegon', 'alamat' => 'Jl. Gatot Subroto No. 888', 'telepon' => '0254-890123'],
            
            // Sumatera Utara - 5 kota
            ['nama' => 'Bengkel Medan Jaya', 'pemilik' => 'Hardi Sumatra', 'provinsi' => 'Sumatera Utara', 'kota' => 'Medan', 'alamat' => 'Jl. Gatot Subroto No. 333', 'telepon' => '061-123456'],
            ['nama' => 'Bengkel Binjai Teknik', 'pemilik' => 'Kholid Anwar', 'provinsi' => 'Sumatera Utara', 'kota' => 'Binjai', 'alamat' => 'Jl. Merdeka No. 1313', 'telepon' => '061-234567'],
            ['nama' => 'Bengkel Deli Serdang', 'pemilik' => 'Laksmi Purwanto', 'provinsi' => 'Sumatera Utara', 'kota' => 'Deli Serdang', 'alamat' => 'Jl. Diponegoro No. 1414', 'telepon' => '061-345678'],
            ['nama' => 'Bengkel Pematang Siantar', 'pemilik' => 'Mawan Simamora', 'provinsi' => 'Sumatera Utara', 'kota' => 'Pematang Siantar', 'alamat' => 'Jl. Ahmad Yani No. 1515', 'telepon' => '0622-456789'],
            ['nama' => 'Bengkel Tebing Tinggi', 'pemilik' => 'Nanda Hermawan', 'provinsi' => 'Sumatera Utara', 'kota' => 'Tebing Tinggi', 'alamat' => 'Jl. Sudirman No. 1616', 'telepon' => '0623-567890'],
            
            // Gorontalo - Pohuwato
            ['nama' => 'Bengkel Marisa Motor', 'pemilik' => 'Haji Usman', 'provinsi' => 'Gorontalo', 'kota' => 'Pohuwato', 'alamat' => 'Jl. Trans Sulawesi, Marisa', 'telepon' => '0443-21001'],
        ];

        foreach ($bengkelData as $data) {
            Bengkel::create([
                'name' => $data['nama'],
                'owner' => $data['pemilik'],
                'province' => $data['provinsi'],
                'city' => $data['kota'],
                'address' => $data['alamat'],
                'phone' => $data['telepon'],
                'is_active' => true,
            ]);
        }
    }
}
