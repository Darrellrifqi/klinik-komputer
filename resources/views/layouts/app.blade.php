<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Klinik Komputer — Mitra Resmi Axioo di Bandung. Penjualan laptop Axioo, service komputer, dan pengadaan laptop untuk sekolah.')">
    <meta name="keywords" content="klinik komputer, axioo bandung, service laptop bandung, pengadaan laptop sekolah">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Klinik Komputer') — Mitra Resmi Axioo Bandung</title>
    <link class="favicon" rel="icon" type="image/png" href="/favicon-kk.png">
    <link rel="stylesheet" href="/css/main.css">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="nav-brand" style="padding: 0;">
                <img src="/images/logo.png" alt="Logo Klinik Komputer" style="height: 38px; width: auto; max-width: 160px; object-fit: contain;">
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">Produk Axioo</a></li>
                <li><a href="{{ route('procurement.index') }}" class="{{ request()->routeIs('procurement.index') ? 'active' : '' }}">Pengadaan Unit</a></li>
                <li><a href="{{ route('kit.activation') }}" class="{{ request()->routeIs('kit.activation') ? 'active' : '' }}">Membership</a></li>
                <li><a href="{{ route('service.booking') }}" class="{{ request()->routeIs('service.booking') ? 'active' : '' }}">Booking Servis</a></li>
                <li><a href="{{ route('service.track') }}" class="{{ request()->routeIs('service.track') ? 'active' : '' }}">Cek Status Servis</a></li>
                <li><a href="{{ route('pkl.index') }}" class="{{ request()->routeIs('pkl.*') ? 'active' : '' }}">INTERNSHIP</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
                <button class="nav-mobile-btn" id="mobileMenuBtn" onclick="toggleMobileMenu()">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flash-notification alert alert-success" id="flash-msg">
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flash-notification alert alert-error" id="flash-msg">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="nav-brand" style="margin-bottom: 12px; display: inline-block;">
                    KLINIK KOMPUTER
                </a>
                <p>Mitra resmi Axioo di Bandung. Melayani penjualan laptop, service komputer profesional, dan pengadaan laptop untuk institusi pendidikan.</p>
            </div>
            <div class="footer-col">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="{{ route('products') }}">Produk Axioo</a></li>
                    <li><a href="{{ route('procurement.index') }}">Pengadaan Unit</a></li>
                    <li><a href="{{ route('procurement.index') }}#tracking">Cek Status Pengadaan</a></li>
                    <li><a href="{{ route('kit.activation') }}">Membership</a></li>
                    <li><a href="{{ route('service.booking') }}">Booking Servis</a></li>
                    <li><a href="{{ route('service.track') }}">Cek Status Servis</a></li>
                    <li><a href="{{ route('pkl.index') }}">Internship & PKL</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <ul>
                    <li><a href="https://wa.me/6281390727420" target="_blank">081390727420</a></li>
                    <li><a href="#">Jl. A. Yani Blok E8, Bandung</a></li>
                    <li><a href="#" onclick="return false;" style="cursor: default;">Senin–Jumat 09.00–17.00</a></li>
                    <li><a href="#" onclick="return false;" style="cursor: default;">Sabtu 09.00–14.00</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Klinik Komputer — PT Mabito Karya. All rights reserved.</span>
            <span style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Mitra Resmi Axioo</span>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 30);
        });

        // Mobile menu
        function toggleMobileMenu() {
            document.getElementById('navLinks').classList.toggle('open');
        }

        // Auto-hide flash messages
        setTimeout(() => {
            const msg = document.getElementById('flash-msg');
            if (msg) { msg.style.opacity = '0'; msg.style.transition = 'opacity 0.5s'; setTimeout(() => msg.remove(), 500); }
        }, 4000);
    </script>

    @stack('scripts')
</body>
</html>
