@extends('layouts.app')

@section('title', 'Komunitas - Komunitas Mekanik Indonesia')

@section('content')
<section class="hero" style="padding: 3rem 0 6rem;">
    <div class="container" style="display: flex; align-items: center;">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">KOMUNITAS MEKANIK INDONESIA</h1>
            <p style="font-size: 1rem;">SILATURAHMI TANPA BATAS</p>
        </div>
    </div>
</section>

<section class="about">
    <div class="container">
        <div>
            <h2 style="text-align: left; color: #0052A3; margin-bottom: 1.5rem;">Tentang Kami</h2>
            <p>Komunitas Mekanik Indonesia (KMI) adalah organisasi yang berdedikasi untuk meningkatkan kualitas layanan mekanik di seluruh Indonesia. Dengan lebih dari seribu anggota aktif, KMI telah menjadi wadah kolaborasi profesional yang kuat untuk mekanik di berbagai negara dan daerah.</p>
            
            <p>KMI berfungsi sebagai jembatan silaturahmi yang menghubungkan mekanik dari berbagai latar belakang dan keahlian. Melalui berbagai aktivitas rutin seperti workshop, seminar, dan gathering, KMI membantu meningkatkan kompetensi dan jaringan bisnis anggotanya.</p>

            <p>Visi kami adalah menjadi komunitas mekanik terkemuka di Asia Tenggara, sementara misi kami adalah memberdayakan mekanik Indonesia untuk mencapai kesuksesan profesional dan finansial yang berkelanjutan.</p>
        </div>
        <div class="about-sidebar">
            <h3>Komunitas Mekanik Indonesia</h3>
            <p>Jembatan silaturahmi untuk mekanik di seluruh Indonesia.</p>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-number">9</div>
                    <div class="stat-label">Tahun Berdiri</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Cabang</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Ribuan Member</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="gallery-section">
    <div class="container">
        <h2>Gallery Komunitas</h2>
        <div class="carousel-container">
            <div class="gallery-grid" id="komuniteGallery">
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/350x250?text=Munas+KMI" alt="Munas KMI">
                    <div class="gallery-item-content">
                        <h4>Munas KMI</h4>
                        <p>Musyawarah Nasional KMI merupakan ajang pertemuan tahunan seluruh anggota untuk koordinasi dan evaluasi program.</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/350x250?text=Gathering+Regional" alt="Gathering Regional">
                    <div class="gallery-item-content">
                        <h4>Gathering Regional</h4>
                        <p>Pertemuan rutin di setiap regional untuk mempererat silaturahmi dan berbagi pengalaman antar anggota.</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/350x250?text=Workshop+Training" alt="Workshop & Training">
                    <div class="gallery-item-content">
                        <h4>Workshop & Training</h4>
                        <p>Program workshop dan training untuk meningkatkan skill dan pengetahuan teknis anggota komunitas.</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://via.placeholder.com/350x250?text=Sosialisasi" alt="Sosialisasi">
                    <div class="gallery-item-content">
                        <h4>Sosialisasi Produk</h4>
                        <p>Program sosialisasi produk dan teknologi terbaru untuk kemajuan bersama komunitas.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-nav">
                <button class="carousel-btn" onclick="prevGallery()">❮</button>
                <button class="carousel-btn" onclick="nextGallery()">❯</button>
            </div>
        </div>
    </div>
</section>

<section class="registration-section">
    <div class="container">
        <h2>Registrasi Anggota</h2>
        <div class="registration-card">
            <h3>Bergabunglah dengan KMI</h3>
            <p>Menjadi bagian dari komunitas mekanik terbesar di Indonesia. Dapatkan akses ke jaringan bisnis, training, dan berbagai keuntungan eksklusif lainnya.</p>
            <a href="https://wa.me/6282114693145" class="btn-whatsapp" target="_blank">📲 Hubungi via WhatsApp</a>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
let currentGalleryIndex = 0;
const galleryItems = document.querySelectorAll('#komuniteGallery .gallery-item');
const galleryPerView = 3;

function showGallerySlide(index) {
    const carousel = document.getElementById('komuniteGallery');
    const itemWidth = 100 / galleryPerView;
    const offset = -index * itemWidth;
    carousel.style.transform = `translateX(${offset}%)`;
}

function nextGallery() {
    const maxIndex = Math.max(0, galleryItems.length - galleryPerView);
    currentGalleryIndex = (currentGalleryIndex + 1) % (maxIndex + 1);
    showGallerySlide(currentGalleryIndex);
}

function prevGallery() {
    const maxIndex = Math.max(0, galleryItems.length - galleryPerView);
    currentGalleryIndex = (currentGalleryIndex - 1 + maxIndex + 1) % (maxIndex + 1);
    showGallerySlide(currentGalleryIndex);
}
</script>
@endsection
