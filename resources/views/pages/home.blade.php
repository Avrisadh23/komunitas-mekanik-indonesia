@extends('layouts.app')

@section('title', 'Home - Komunitas Mekanik Indonesia')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>KOMUNITAS MEKANIK INDONESIA</h1>
            <p style="font-size: 1.5rem; line-height: 1.4; font-weight: 800; letter-spacing: 1px;">SILATURAHMI TANPA BATAS</p>
            <p style="font-size: 1rem; font-weight: 400; margin-bottom: 2rem; color: rgba(255,255,255,0.95);">Gabung Bersama Kami Untuk Menjalin Silaturahmi</p>
            <a href="{{ route('home') }}#tentang" class="btn btn-primary">Lihat Selengkapnya</a>
        </div>
    </div>
</section>

<section class="about" id="tentang">
    <div class="container">
        <div>
            <h2 style="text-align: left; color: #0052A3; margin-bottom: 1.5rem;">Tentang Kami</h2>
            <p>Komunitas Mekanik Indonesia (KMI) adalah organisasi yang berdedikasi untuk meningkatkan kualitas layanan mekanik di seluruh Indonesia. Kami memiliki misi utama untuk membangun jaringan profesional mekanik yang kuat, saling mendukung dan berbagi pengetahuan.</p>
            
            <p>Dengan lebih dari 1000 anggota aktif, KMI telah menjadi platform terpercaya bagi mekanik untuk berkembang, mendiskusikan tantangan industri, serta menciptakan peluang bisnis baru. Komunitas kami menyediakan berbagai forum diskusi, training, dan networking event yang membantu mekanik meningkatkan kompetensi mereka secara profesional.</p>
        </div>
        <div class="about-sidebar">
            <h3>Komunitas Mekanik Indonesia</h3>
            <p>Jembatan silaturahmi untuk mekanik di seluruh Indonesia.</p>
            <p style="font-size: 0.85rem; color: #999; margin-top: 1rem; text-align: center;">
                <strong>Sudah Berdiri Sejak</strong>
            </p>
            <div class="stats-row" id="statsContainer">
                <strong></strong>
                <div class="stat-item">
                    <div class="stat-number" id="yearsDisplay">9</div>
                    <div class="stat-label">Tahun</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="monthsDisplay">4</div>
                    <div class="stat-label">Bulan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="daysDisplay">0</div>
                    <div class="stat-label">Hari</div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<section class="gallery-section" id="gallery">
    <div class="container">
        <h2>Gallery</h2>
        <div class="carousel-container">
            <div class="gallery-grid" id="galleryCarousel">
                <div id="galleryLoading" style="padding: 2rem; color: #999; text-align: center; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <div>
                        <p>⏳ Loading gallery...</p>
                        <p style="font-size: 12px; margin-top: 10px;">Jika tidak berubah dalam 5 detik, refresh halaman</p>
                    </div>
                </div>
                <div id="galleryError" style="display: none; padding: 2rem; color: #d32f2f; text-align: center; background: #ffebee; border-radius: 8px; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                    <div>
                        <p style="font-weight: bold; font-size: 16px;">❌ Gagal Memuat Gallery</p>
                        <p id="galleryErrorMsg" style="font-size: 14px; margin-top: 10px;">Terjadi error saat memuat data</p>
                        <p style="font-size: 12px; margin-top: 10px;">Silakan refresh halaman (Ctrl+R) atau hubungi admin</p>
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

<section class="sponsors-section">
    <div class="container">
        <h2>Sponsored By</h2>
        <div class="sponsors-grid" id="sponsorsContainer">
            <!-- Sponsors akan dimuat via JavaScript -->
            <div class="sponsor-item loading">
                <p style="text-align: center;">Loading Sponsors...</p>
            </div>
        </div>
    </div>
</section>


<section class="registration-section" id="registrasi">
    <div class="container">
        <h2>Registrasi Anggota</h2>
        <div class="registration-card">
            <h3>Bergabunglah dengan KMI</h3>
            <p>Menjadi bagian dari komunitas mekanik terbesar di Indonesia. Dapatkan akses ke jaringan bisnis, training, dan berbagai keuntungan eksklusif lainnya.</p>
            <div class="phone-number">☎️ 087764116086 / 082114693145</div>
            <a href="https://wa.me/6282114693145" class="btn-whatsapp" target="_blank">📲 Hubungi via WhatsApp</a>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
'use strict';
console.log('✅ Gallery script loading...');

// ============================================
// SIMPLE GALLERY SLIDER STATE
// ============================================
window.galleryState = {
    items: [],
    currentIdx: 0,
    perView: 3,
    moving: false
};

// ============================================
// GALLERY FUNCTIONS
// ============================================

/**
 * Fetch from API
 */
function loadGallery() {
    console.log('🔄 [loadGallery] Starting fetch from /api/gallery...');
    
    fetch('/api/gallery')
        .then(r => {
            console.log('🔄 [loadGallery] HTTP Response received:', r.status, r.statusText);
            if (!r.ok) {
                throw new Error('HTTP Error: ' + r.status + ' ' + r.statusText);
            }
            return r.json();
        })
        .then(data => {
            console.log('📦 [loadGallery] Data received:', Array.isArray(data) ? data.length + ' items' : 'NOT AN ARRAY!', typeof data);
            
            if (!Array.isArray(data)) {
                console.error('❌ [loadGallery] Data is not an array!', typeof data, data);
                showGalleryError('Data format error: expected array');
                return;
            }
            
            if (data.length === 0) {
                console.error('❌ [loadGallery] Empty array received');
                showGalleryError('No gallery items found');
                return;
            }
            
            console.log('✅ [loadGallery] Got ' + data.length + ' items, setting galleryState.items');
            window.galleryState.items = data;
            
            console.log('🎨 [loadGallery] Calling renderGallery()...');
            renderGallery();
        })
        .catch(e => {
            console.error('❌ [loadGallery] FETCH ERROR:', e.message, e);
            console.error('🔗 [loadGallery] Tried to fetch: /api/gallery');
            showGalleryError('Fetch error: ' + e.message);
        });
}

/**
 * Show gallery error on page
 */
function showGalleryError(msg) {
    console.error('⚠️ [showGalleryError]:', msg);
    const loading = document.getElementById('galleryLoading');
    const error = document.getElementById('galleryError');
    const errorMsg = document.getElementById('galleryErrorMsg');
    
    if (loading) loading.style.display = 'none';
    if (error) error.style.display = 'flex';
    if (errorMsg) errorMsg.textContent = msg;
}

/**
 * Render items to DOM
 */
function renderGallery() {
    console.log('🎨 [renderGallery] Starting renderGallery()...');
    const carousel = document.getElementById('galleryCarousel');
    console.log('🎨 [renderGallery] Carousel element:', carousel ? '✅ Found' : '❌ NOT FOUND');
    if (!carousel) return;
    
    console.log('🎨 [renderGallery] Clearing carousel HTML...');
    carousel.innerHTML = '';
    
    // Hide loading message
    const loading = document.getElementById('galleryLoading');
    if (loading) loading.style.display = 'none';
    
    // FORCE FLEX LAYOUT & VISIBILITY
    carousel.style.display = 'flex';
    carousel.style.flexWrap = 'nowrap';
    carousel.style.gap = '32px';
    carousel.style.overflow = 'visible';
    carousel.style.width = 'max-content';
    carousel.style.transition = 'transform 0.5s ease-in-out';
    
    // CRITICAL: Measure from actual container
    const containerEl = document.querySelector('.carousel-container');
    if (containerEl) {
        containerEl.style.overflow = 'hidden';
    }
    let containerWidth = containerEl ? containerEl.offsetWidth : (window.innerWidth - 60);
    
    // Ensure minimum width
    containerWidth = Math.max(320, containerWidth);
    
    // DEBUG: Show calculation
    console.log('📐 [renderGallery] Window width: ' + window.innerWidth + 'px, Container: ' + containerWidth + 'px');
    
    // FORCED: For viewport >= 1000px, always 3 items. Otherwise, use breakpoints
    let perView;
    if (window.innerWidth >= 1000) {
        perView = 3;
        console.log('📐 [renderGallery] FORCED 3 items (window >= 1000px)');
    } else {
        perView = containerWidth >= 850 ? 3 : (containerWidth >= 600 ? 2 : 1);
        console.log('📐 [renderGallery] Breakpoint check: containerWidth (' + containerWidth + 'px) >= 850px? ' + (containerWidth >= 850));
    }
    
    console.log('📐 [renderGallery] Final perView: ' + perView);
    
    window.galleryState.perView = perView;
    
    const gap = 32;
    const totalGap = (perView - 1) * gap;
    const itemWidth = Math.floor((containerWidth - totalGap) / perView);
    
    console.log('📐 [renderGallery] Window: ' + window.innerWidth + 'px, Container: ' + containerWidth + 'px, Items per view: ' + perView + ', Item width: ' + itemWidth + 'px');
    console.log('📐 [renderGallery] About to render ' + window.galleryState.items.length + ' items...');
    
    // Create items
    let addedCount = 0;
    window.galleryState.items.forEach((item, idx) => {
        try {
            const div = document.createElement('div');
            div.className = 'gallery-item';
            div.style.width = itemWidth + 'px';
            div.style.flex = '0 0 auto';
            
            const img = document.createElement('img');
            img.style.width = '100%';
            img.style.height = '250px';
            img.style.objectFit = 'cover';
            img.alt = item.title;
            img.src = resolveImageUrl(item.image_url) || '/frontend/asset/images/Munas.jpeg';
            img.onerror = function() {
                console.log('⚠️ Image ' + idx + ' failed, using fallback');
                this.src = '/frontend/asset/images/Munas.jpeg';
                this.onerror = null;
            };
            
            const content = document.createElement('div');
            content.className = 'gallery-item-content';
            content.innerHTML = '<h4>' + escapeHtml(item.title) + '</h4><p>' + escapeHtml(item.description || '') + '</p>';
            
            div.appendChild(img);
            div.appendChild(content);
            carousel.appendChild(div);
            addedCount++;
        } catch(e) {
            console.error('❌ [renderGallery] Error creating item ' + idx + ':', e.message);
        }
    });
    
    console.log('✅ [renderGallery] Added ' + addedCount + ' items to DOM out of ' + window.galleryState.items.length);
    console.log('🎬 [renderGallery] Calling setupSlider()...');
    
    // Position slider
    setupSlider();
}

/**
 * Setup slider - reset and then position
 */
function setupSlider() {
    console.log('🎬 [setupSlider] Starting setupSlider()...');
    const carousel = document.getElementById('galleryCarousel');
    if (!carousel) {
        console.error('🎬 [setupSlider] ❌ Carousel not found!');
        showGalleryError('Carousel element not found');
        return;
    }
    
    console.log('🎬 [setupSlider] Resetting position to 0...');
    carousel.style.transform = 'translateX(0px)';
    
    setTimeout(() => {
        console.log('🎬 [setupSlider] Calling positionSlider(0) after 100ms...');
        positionSlider(0);
    }, 100);
}

/**
 * Get item measurements
 */
function getMeasurements() {
    const carousel = document.getElementById('galleryCarousel');
    if (!carousel) {
        console.error('📏 [getMeasurements] ❌ Carousel not found');
        return null;
    }
    
    const item = carousel.querySelector('.gallery-item');
    if (!item) {
        console.error('📏 [getMeasurements] ❌ No gallery items found. Carousel has ' + carousel.children.length + ' children');
        return null;
    }
    
    const w = item.offsetWidth;
    if (w <= 0) {
        console.error('📏 [getMeasurements] ❌ Item width is invalid: ' + w + 'px');
        return null;
    }
    
    console.log('📏 [getMeasurements] ✅ Item width: ' + w + 'px');
    return { itemWidth: w };
}

/**
 * Position slider to index
 */
function positionSlider(idx) {
    const carousel = document.getElementById('galleryCarousel');
    if (!carousel) {
        console.error('📍 [positionSlider] ❌ Carousel not found!');
        return;
    }
    
    const m = getMeasurements();
    if (!m) {
        console.error('📍 [positionSlider] ❌ Cannot measure items! Carousel has ' + carousel.children.length + ' children');
        return;
    }
    
    const offset = -(idx * (m.itemWidth + 32));
    carousel.style.transform = 'translateX(' + offset + 'px)';
    
    console.log('📍 [positionSlider] ✅ Index ' + idx + ' → Transform: ' + offset + 'px');
}

/**
 * Navigation - Next
 */
window.nextGallery = function() {
    const st = window.galleryState;
    
    // Safety checks
    if (st.moving) {
        console.log('⏭️ [nextGallery] ⏳ Already moving, ignoring click');
        return;
    }
    if (st.items.length === 0) {
        console.log('⏭️ [nextGallery] ❌ No items loaded');
        return;
    }
    
    st.moving = true;
    const maxIdx = Math.max(0, st.items.length - st.perView);
    let newIdx = st.currentIdx + 1;
    if (newIdx > maxIdx) newIdx = 0;
    
    console.log('⏭️ [nextGallery] Navigate ' + st.currentIdx + ' → ' + newIdx + ' (maxIdx: ' + maxIdx + ', total: ' + st.items.length + ', perView: ' + st.perView + ')');
    st.currentIdx = newIdx;
    positionSlider(newIdx);
    
    setTimeout(() => { st.moving = false; }, 400);
};

/**
 * Navigation - Prev
 */
window.prevGallery = function() {
    const st = window.galleryState;
    
    // Safety checks
    if (st.moving) {
        console.log('⏮️ [prevGallery] ⏳ Already moving, ignoring click');
        return;
    }
    if (st.items.length === 0) {
        console.log('⏮️ [prevGallery] ❌ No items loaded');
        return;
    }
    
    st.moving = true;
    const maxIdx = Math.max(0, st.items.length - st.perView);
    let newIdx = st.currentIdx - 1;
    if (newIdx < 0) newIdx = maxIdx;
    
    console.log('⏮️ [prevGallery] Navigate ' + st.currentIdx + ' → ' + newIdx + ' (maxIdx: ' + maxIdx + ', total: ' + st.items.length + ', perView: ' + st.perView + ')');
    st.currentIdx = newIdx;
    positionSlider(newIdx);
    
    setTimeout(() => { st.moving = false; }, 400);
};

/**
 * Escape HTML
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function resolveImageUrl(url) {
    if (!url) return null;
    // Fix: Handle placeholder URLs that are missing the domain (causes ERR_NAME_NOT_RESOLVED)
    if (url.match(/^\d+x\d+/)) {
        return `https://placehold.co/${url}`;
    }
    return url;
}

/**
 * Load sponsors
 */
function loadSponsors() {
    fetch('/api/sponsors')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('sponsorsContainer');
            if (!container) return;
            
            container.innerHTML = '';
            if (!data || !data.length) return;
            
            data.forEach(s => {
                const div = document.createElement('div');
                div.className = 'sponsor-item';
                
                const img = document.createElement('img');
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                img.style.objectFit = 'contain';
                img.alt = s.name;
                img.src = resolveImageUrl(s.logo_url) || '/frontend/asset/images/logo-pertamina.jpeg';
                img.onerror = function() {
                    this.src = '/frontend/asset/images/logo-pertamina.jpeg';
                    this.onerror = null;
                };
                
                div.appendChild(img);
                container.appendChild(div);
            });
        })
        .catch(e => console.error('[Sponsors] Error:', e));
}

/**
 * Load stats
 */
function loadStats() {
    fetch('/api/stats')
        .then(r => r.json())
        .then(data => {
            if (data) {
                const y = document.getElementById('yearsDisplay');
                const m = document.getElementById('monthsDisplay');
                const d = document.getElementById('daysDisplay');
                if (y) y.textContent = data.years || '9';
                if (m) m.textContent = data.months || '4';
                if (d) d.textContent = data.days || '0';
            }
        })
        .catch(e => console.error('[Stats] Error:', e));
}

// ============================================
// INIT
// ============================================

console.log('⏳ Waiting for DOMContentLoaded...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOMContentLoaded fired!');
    console.log('📍 Window object:', typeof window === 'object');
    console.log('📍 Document object:', typeof document === 'object');
    
    const carouselEl = document.getElementById('galleryCarousel');
    console.log('📍 Gallery carousel element found:', carouselEl ? 'YES ✅' : 'NO ❌');
    
    console.log('🚀 Calling loadStats()...');
    loadStats();
    
    console.log('🚀 Calling loadGallery()...');
    loadGallery();
    
    console.log('🚀 Calling loadSponsors()...');
    loadSponsors();
    
    setInterval(loadSponsors, 60000);
    console.log('✅ Initialization complete!');
}, { once: true });

console.log('⏳ Script registration complete, waiting for DOM...');

// Reposition on resize
window.addEventListener('resize', () => {
    if (window.galleryState.items.length > 0) {
        const containerWidth = document.querySelector('.carousel-container')?.offsetWidth || window.innerWidth - 60;
        let newPerView;
        
        // Same logic: force 3 for >= 1000px
        if (window.innerWidth >= 1000) {
            newPerView = 3;
        } else {
            newPerView = containerWidth >= 850 ? 3 : (containerWidth >= 600 ? 2 : 1);
        }
        
        console.log('📐 [resize] New perView: ' + newPerView + ', Old: ' + window.galleryState.perView);
        
        if (newPerView !== window.galleryState.perView) {
            console.log('📐 [resize] Breakpoint changed, re-rendering...');
            renderGallery();
        } else {
            console.log('📐 [resize] Same breakpoint, just repositioning...');
            positionSlider(window.galleryState.currentIdx);
        }
    }
});
</script>
@endsection
