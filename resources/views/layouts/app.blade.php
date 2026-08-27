<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Komunitas Mekanik Indonesia')</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/asset/images/logo-kmi.png') }}">
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
            <button class="navbar-toggle" id="navbarToggle" aria-label="Buka menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="{{ route('home') }}">HOME</a></li>
                <li><a href="{{ route('home') }}#tentang">TENTANG KAMI</a></li>
                <li><a href="{{ route('home') }}#gallery">GALLERY</a></li>
                <li><a href="{{ route('home') }}#registrasi">REGISTRASI ANGGOTA</a></li>
                <li><a href="{{ route('produk') }}">PRODUK</a></li>
                <li><a href="{{ route('bengkel') }}">BENGKEL REKANAN</a></li>
            </ul>
        </div>
    </nav>
    <script>
    (function() {
        var toggle = document.getElementById('navbarToggle');
        var menu = document.getElementById('navbarMenu');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', function() {
            var isOpen = menu.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                menu.classList.remove('is-open');
                toggle.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    })();
    </script>

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
            </div>
            <div class="footer-section">
                <h3>Hubungi Kami</h3>
                <p>Komunitas Mekanik Indonesia</p>
                <p>📞 <a href="tel:+6282114693145">0821-1469-3145</a></p>
                <p>📧 <a href="mailto:admin@kmi.co.id">admin@kmi.co.id</a></p>
                <p>📍 Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-socials">
                <a href="#" class="social-icon" aria-label="Facebook" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="social-icon" aria-label="Instagram" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-icon" aria-label="Twitter" title="Twitter"><i class="fa-brands fa-twitter"></i></a>
            </div>
            <p>&copy; 2026 Komunitas Mekanik Indonesia. All rights reserved. | Powered by AVSDH-DEV
                <a href="{{ route('admin.login') }}" class="admin-link-subtle" aria-label="Admin">·</a>
            </p>
        </div>
    </footer>

    <!-- Upcoming Event Countdown (hardcoded, not admin-managed) -->
    <div id="eventCountdownPopup" class="event-countdown-popup">
        <div class="ecp-header">
            <span class="ecp-dot"></span>
            <span class="ecp-label">Upcoming Event</span>
        </div>
        <div class="ecp-title">Kopdarnas 7 Makassar</div>
        <div class="ecp-timer">
            <div class="ecp-unit">
                <div class="ecp-circle" id="ecpHours">--</div>
                <div class="ecp-unit-label">Jam</div>
            </div>
            <div class="ecp-colon">:</div>
            <div class="ecp-unit">
                <div class="ecp-circle" id="ecpMinutes">--</div>
                <div class="ecp-unit-label">Menit</div>
            </div>
            <div class="ecp-colon">:</div>
            <div class="ecp-unit">
                <div class="ecp-circle" id="ecpSeconds">--</div>
                <div class="ecp-unit-label">Detik</div>
            </div>
        </div>
        <a href="https://wa.me/6285696490530" class="ecp-cta" target="_blank">
            <i class="fa-brands fa-whatsapp"></i> Daftar Sekarang
        </a>
    </div>
    <script>
    (function() {
        var targetDate = new Date('2026-09-15T10:00:00+07:00');
        var popup = document.getElementById('eventCountdownPopup');
        var hoursEl = document.getElementById('ecpHours');
        var minutesEl = document.getElementById('ecpMinutes');
        var secondsEl = document.getElementById('ecpSeconds');
        var timer = null;

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function updateCountdown() {
            var diff = targetDate.getTime() - Date.now();
            if (diff <= 0) {
                if (popup) popup.style.display = 'none';
                if (timer) clearInterval(timer);
                return;
            }
            var hoursLeft = Math.floor(diff / (1000 * 60 * 60));
            var minutesLeft = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var secondsLeft = Math.floor((diff % (1000 * 60)) / 1000);
            if (hoursEl) hoursEl.textContent = pad(hoursLeft);
            if (minutesEl) minutesEl.textContent = pad(minutesLeft);
            if (secondsEl) secondsEl.textContent = pad(secondsLeft);
        }

        updateCountdown();
        timer = setInterval(updateCountdown, 1000);
    })();
    </script>

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
