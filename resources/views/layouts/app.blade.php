<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Komunitas Mekanik Indonesia')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    @yield('extra_css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('frontend/asset/images/logo-kmi.png') }}" alt="kmi" style="height: 40px; width: auto;">
                </a>
            </div>
            <ul class="navbar-menu">
                <li><a href="{{ route('home') }}">HOME</a></li>
                <li><a href="{{ route('home') }}#tentang">TENTANG KAMI</a></li>
                <li><a href="{{ route('home') }}#gallery">GALLERY</a></li>
                <li><a href="{{ route('home') }}#registrasi">REGISTRASI ANGGOTA</a></li>
                <li><a href="{{ route('produk') }}">PRODUK</a></li>
                <li><a href="{{ route('bengkel') }}">BENGKEL REKANAN</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-section">
                <h3>Tentang KMI</h3>
                <p>Komunitas Mekanik Indonesia adalah wadah silaturahmi untuk mekanik profesional di seluruh nusantara. Kami berkomitmen meningkatkan kualitas dan jaringan bisnis mekanik Indonesia.</p>
            </div>
            <div class="footer-section">
                <h3>Menu</h3>
                <p><a href="{{ route('home') }}">Home</a></p>
                <p><a href="{{ route('produk') }}">Produk & Layanan</a></p>
                <p><a href="{{ route('komunitas') }}">Komunitas</a></p>
                <p><a href="{{ route('admin.login') }}">Admin</a></p>
            </div>
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <p>Komunitas Mekanik Indonesia</p>
                <p>📞 <a href="tel:+628214693145">0821-4693-145</a></p>
                <p>📧 <a href="mailto:admin@kmi.co.id">admin@kmi.co.id</a></p>
                <p>📍 Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Komunitas Mekanik Indonesia. All rights reserved. | Powered by Laravel</p>
            <a href="{{ route('admin.login') }}" class="btn-admin-footer">ADMIN PANEL</a>
            <div class="footer-socials">
                <a href="#" class="social-icon">📘 Facebook</a>
                <a href="#" class="social-icon">📷 Instagram</a>
                <a href="#" class="social-icon">🐦 Twitter</a>
            </div>
        </div>
    </footer>

    @yield('extra_js')

    <!-- Unregister service workers to avoid cache issues -->
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(registrations => {
            for(let registration of registrations) {
                registration.unregister();
                console.log('Unregistered service worker');
            }
        });
    }
    </script>
</body>
</html>
