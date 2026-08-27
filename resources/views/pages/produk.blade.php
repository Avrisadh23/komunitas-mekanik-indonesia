@extends('layouts.app')

@section('title', 'Produk - Komunitas Mekanik Indonesia')

@section('content')
<section class="hero" style="padding: 3rem 0; min-height: 300px;">
    <div class="container" style="display: flex; align-items: center;">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">Katalog Produk</h1>
            <p>Daftar produk unggulan dari Komunitas Mekanik Indonesia</p>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <h2>Katalog Produk</h2>
        <p style="text-align: center; color: #666; margin-bottom: 3rem;">Daftar produk unggulan dari Komunitas Mekanik Indonesia</p>
        
        <div id="productsContainer" class="products-grid">
            <p style="text-align: center; width: 100%; padding: 2rem;">Loading produk...</p>
        </div>
    </div>
</section>

<section class="registration-section">
    <div class="container">
        <h2>Promosikan Produkmu</h2>
        <div class="registration-card">
            <h3>📱 Promosikan Produkmu</h3>
            <p>Ingin produk Anda ditampilkan di sini? Hubungi kami melalui WhatsApp untuk info lebih lanjut dan paket promosi menarik.</p>
            <div class="phone-number">☎️ 087764116086 / 082114693145</div>
            <a href="https://wa.me/6282114693145" class="btn-whatsapp" target="_blank">📲 Hubungi via WhatsApp</a>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
// Local fallback images - NO EXTERNAL PLACEHOLDERS
const productImages = {
    fallback1: '/frontend/asset/images/Ketum.jpeg',
    fallback2: '/frontend/asset/images/Munas.jpeg',
    fallback3: '/frontend/asset/images/Munas1.jpeg'
};

let productLoadCount = 0;

// Load products dari API PostgreSQL - NO PLACEHOLDERS
function loadProductsData() {
    fetch('/api/products')
        .then(response => response.json())
        .then(products => {
            const container = document.getElementById('productsContainer');
            
            if (!products || products.length === 0) {
                container.innerHTML = '<p style="text-align: center; width: 100%; padding: 2rem;">Belum ada produk yang ditambahkan.</p>';
                productLoadCount = 0;
                return;
            }
            
            // Check if product count changed for real-time updates
            if (productLoadCount !== 0 && products.length !== productLoadCount) {
                console.log('[Auto-refresh] Product count changed! Reloading...');
            }
            
            productLoadCount = products.length;
            container.innerHTML = '';
            
            products.forEach((product, idx) => {
                const card = document.createElement('div');
                card.className = 'product-card';
                
                // Use product image or local fallback
                let imgUrl = product.image_url;
                if (!imgUrl || imgUrl.includes('placeholder')) {
                    imgUrl = Object.values(productImages)[idx % Object.values(productImages).length];
                }
                
                card.innerHTML = `
                    <img src="${imgUrl}" alt="${product.name}" style="width: 100%; height: 200px; object-fit: cover;">
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    ${product.price ? `<div style="margin-top: 1rem; font-weight: bold; color: #d32f2f;">Rp ${formatCurrency(product.price)}</div>` : ''}
                `;
                
                // Add onerror fallback
                const img = card.querySelector('img');
                if (img) {
                    img.onerror = function() {
                        this.src = Object.values(productImages)[idx % Object.values(productImages).length];
                        this.onerror = function() {
                            this.src = productImages.fallback1;
                        };
                    };
                }
                
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            document.getElementById('productsContainer').innerHTML = '<p style="color: red;">Error loading products</p>';
        });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    loadProductsData();
    
    // Auto-refresh products every 30 seconds
    setInterval(function() {
        console.log('[Auto-refresh] Checking for new products...');
        loadProductsData();
    }, 30000); // Every 30 seconds
}, { once: true });

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 0
    }).format(amount);
}
</script>
@endsection
