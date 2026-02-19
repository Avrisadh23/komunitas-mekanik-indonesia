@extends('layouts.admin')

@section('title', 'Admin Dashboard - Komunitas Mekanik Indonesia')

@section('content')

<!-- Gallery Section -->
<section class="admin-section" id="gallery">
    <div class="section-header">
        <h2><i class="fas fa-images"></i> Manajemen Gallery</h2>
        <button class="btn-add" onclick="toggleForm('galleryForm')">
            <i class="fas fa-plus"></i> Tambah Gallery
        </button>
    </div>

    <div id="galleryForm" class="form-container hidden">
        <button class="form-close" onclick="toggleForm('galleryForm')">&times;</button>
        <form onsubmit="saveGallery(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="galleryTitle"><i class="fas fa-heading"></i> Judul</label>
                    <input type="text" id="galleryTitle" placeholder="Masukkan judul gallery" required>
                </div>
                <div class="form-group">
                    <label for="galleryImage"><i class="fas fa-image"></i> Foto</label>
                    <input type="file" id="galleryImage" accept="image/*" required>
                </div>
            </div>
            <div class="form-group">
                <label for="galleryDesc"><i class="fas fa-align-left"></i> Deskripsi</label>
                <textarea id="galleryDesc" placeholder="Masukkan deskripsi gallery" required></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Gallery
            </button>
        </form>
    </div>

    <div id="galleryList" class="items-grid"></div>
</section>

<!-- Bengkel Section -->
<section class="admin-section" id="bengkel">
    <div class="section-header">
        <h2><i class="fas fa-tools"></i> Manajemen Bengkel</h2>
        <button class="btn-add" onclick="toggleForm('bengkelForm')">
            <i class="fas fa-plus"></i> Tambah Bengkel
        </button>
    </div>

    <div id="bengkelForm" class="form-container hidden">
        <button class="form-close" onclick="toggleForm('bengkelForm')">&times;</button>
        <form onsubmit="saveBengkel(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="bengkelNama"><i class="fas fa-store"></i> Nama Bengkel</label>
                    <input type="text" id="bengkelNama" placeholder="Masukkan nama bengkel" required>
                </div>
                <div class="form-group">
                    <label for="bengkelPemilik"><i class="fas fa-user"></i> Pemilik</label>
                    <input type="text" id="bengkelPemilik" placeholder="Nama pemilik" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="bengkelProvinsi"><i class="fas fa-map-marker-alt"></i> Provinsi</label>
                    <select id="bengkelProvinsi" onchange="updateKotaOptions()" required>
                        <option value="">-- Pilih Provinsi --</option>
                        <option value="Aceh">Aceh</option>
                        <option value="Sumatera Utara">Sumatera Utara</option>
                        <option value="Sumatera Barat">Sumatera Barat</option>
                        <option value="Riau">Riau</option>
                        <option value="Jambi">Jambi</option>
                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                        <option value="Bengkulu">Bengkulu</option>
                        <option value="Lampung">Lampung</option>
                        <option value="Bangka Belitung">Bangka Belitung</option>
                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                        <option value="DKI Jakarta">DKI Jakarta</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                        <option value="Banten">Banten</option>
                        <option value="Bali">Bali</option>
                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                        <option value="Kalimantan Utara">Kalimantan Utara</option>
                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                        <option value="Maluku">Maluku</option>
                        <option value="Maluku Utara">Maluku Utara</option>
                        <option value="Papua Barat">Papua Barat</option>
                        <option value="Papua">Papua</option>
                        <option value="Papua Tengah">Papua Tengah</option>
                        <option value="Papua Pegunungan">Papua Pegunungan</option>
                        <option value="Papua Selatan">Papua Selatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bengkelKota"><i class="fas fa-city"></i> Kota</label>
                    <select id="bengkelKota" required>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="bengkelAlamat"><i class="fas fa-road"></i> Alamat</label>
                <textarea id="bengkelAlamat" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>
            <div class="form-group">
                <label for="bengkelTelepon"><i class="fas fa-phone"></i> Nomor Telepon</label>
                <input type="tel" id="bengkelTelepon" placeholder="Nomor telepon" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="bengkelEmail"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="bengkelEmail" placeholder="Email (opsional)">
                </div>
            </div>
            <div class="form-group">
                <label for="bengkelDeskripsi"><i class="fas fa-align-left"></i> Deskripsi</label>
                <textarea id="bengkelDeskripsi" placeholder="Deskripsi bengkel (opsional)"></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Bengkel
            </button>
        </form>
    </div>

    <div id="bengkelList" class="items-grid"></div>
</section>

<!-- Produk Section -->
<section class="admin-section" id="produk">
    <div class="section-header">
        <h2><i class="fas fa-box"></i> Manajemen Produk</h2>
        <button class="btn-add" onclick="toggleForm('productForm')">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>

    <div id="productForm" class="form-container hidden">
        <button class="form-close" onclick="toggleForm('productForm')">&times;</button>
        <form onsubmit="saveProduct(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="productNama"><i class="fas fa-box"></i> Nama Produk</label>
                    <input type="text" id="productNama" placeholder="Masukkan nama produk" required>
                </div>
                <div class="form-group">
                    <label for="productHarga"><i class="fas fa-tag"></i> Harga</label>
                    <input type="number" id="productHarga" placeholder="Harga produk" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="productGambar"><i class="fas fa-image"></i> Gambar</label>
                    <input type="file" id="productGambar" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="productKategori"><i class="fas fa-list"></i> Kategori</label>
                    <select id="productKategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="spare-part">Spare Part</option>
                        <option value="tools">Tools</option>
                        <option value="accessories">Accessories</option>
                        <option value="others">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="productDesc"><i class="fas fa-align-left"></i> Deskripsi</label>
                <textarea id="productDesc" placeholder="Masukkan deskripsi produk" required></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </form>
    </div>

    <div id="productList" class="items-grid"></div>
</section>

<!-- Sponsor Section -->
<section class="admin-section" id="sponsor">
    <div class="section-header">
        <h2><i class="fas fa-star"></i> Manajemen Sponsor</h2>
        <button class="btn-add" onclick="toggleForm('sponsorForm')">
            <i class="fas fa-plus"></i> Tambah Sponsor
        </button>
    </div>

    <div id="sponsorForm" class="form-container hidden">
        <button class="form-close" onclick="toggleForm('sponsorForm')">&times;</button>
        <form onsubmit="saveSponsor(event)">
            <div class="form-row">
                <div class="form-group">
                    <label for="sponsorNama"><i class="fas fa-building"></i> Nama Sponsor</label>
                    <input type="text" id="sponsorNama" placeholder="Masukkan nama sponsor" required>
                </div>
                <div class="form-group">
                    <label for="sponsorLogo"><i class="fas fa-image"></i> Logo</label>
                    <input type="file" id="sponsorLogo" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label for="sponsorUrl"><i class="fas fa-link"></i> Website/URL</label>
                <input type="url" id="sponsorUrl" placeholder="https://www.example.com" required>
            </div>
            <div class="form-group">
                <label for="sponsorDesc"><i class="fas fa-align-left"></i> Deskripsi (Opsional)</label>
                <textarea id="sponsorDesc" placeholder="Masukkan deskripsi sponsor..." style="resize: vertical; min-height: 100px;"></textarea>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Sponsor
            </button>
        </form>
    </div>

    <div id="sponsorList" class="items-grid"></div>
</section>

@endsection

@section('extra_js')
<script>
// Just additional helpers if needed
document.addEventListener('DOMContentLoaded', function() {
    // If product description field doesn't have an ID, we might need to add it
    const productDescElement = document.querySelector('textarea[placeholder*="deskripsi produk"]');
    if (productDescElement && !productDescElement.id) {
        productDescElement.id = 'productDesc';
    }
    
    // Same for sponsor description
    const sponsorDescElement = document.querySelector('textarea[placeholder*="deskripsi"]');
    if (sponsorDescElement && !sponsorDescElement.id) {
        sponsorDescElement.id = 'sponsorDesc';
    }
});
</script>

<style>
.item-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.item-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.item-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.item-content {
    padding: 15px;
}

.item-content h3,
.item-content h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 16px;
}

.item-content p {
    margin: 8px 0;
    color: #666;
    font-size: 14px;
    line-height: 1.4;
}

.item-content a {
    color: #0052A3;
    text-decoration: none;
}

.item-content a:hover {
    text-decoration: underline;
}

.item-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    min-width: 100px;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
    font-weight: 500;
}

.btn-edit {
    background: #4CAF50;
    color: white;
}

.btn-edit:hover {
    background: #45a049;
}

.btn-delete {
    background: #E63946;
    color: white;
}

.btn-delete:hover {
    background: #d32f2f;
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.no-data i {
    font-size: 48px;
    display: block;
    margin-bottom: 16px;
    opacity: 0.5;
}

.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

@media (max-width: 768px) {
    .items-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
