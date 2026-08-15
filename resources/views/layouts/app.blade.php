<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Laravel">
    <meta name="description" content="@yield('meta_description', 'Klinik Komputer | Mitra Resmi Axioo di Bandung. Penjualan laptop Axioo, service komputer, dan pengadaan laptop untuk sekolah.')">
    <meta name="keywords" content="klinik komputer, axioo bandung, service laptop bandung, pengadaan laptop sekolah">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Klinik Komputer') | Mitra Resmi Axioo Bandung</title>
    <link class="favicon" rel="icon" type="image/png" href="/favicon-kk.png?v=5">
    <link rel="stylesheet" href="/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            font-family: inherit !important;
            border-radius: var(--radius-md, 12px) !important;
            padding: 24px !important;
            background: #ffffff !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        .swal2-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: var(--text-primary, #1e293b) !important;
        }
        .swal2-html-container {
            font-size: 0.88rem !important;
            color: var(--text-muted, #64748b) !important;
            line-height: 1.5 !important;
        }
        .swal2-confirm {
            background-color: #15803d !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            padding: 9px 20px !important;
            box-shadow: none !important;
        }
        .swal2-cancel {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
            font-weight: 600 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 9px 20px !important;
            box-shadow: none !important;
        }
        .swal2-actions {
            gap: 10px !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Backdrop Overlay -->
    <div class="nav-overlay" id="navOverlay" onclick="closeMobileMenu()"></div>

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
                <li><a href="{{ route('pkl.index') }}" class="{{ request()->routeIs('pkl.*') ? 'active' : '' }}">Internship</a></li>
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
                <div style="display: flex; gap: 10px; margin-top: 14px;">
                    <a href="https://www.tiktok.com/@klinikkomp?_r=1&amp;_t=ZS-98VdwA99Nye" target="_blank" rel="noopener noreferrer" title="TikTok @klinikkomp" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s ease;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 1 1-5.2-1.74 2.89 2.89 0 0 1 2.31-2.85V7.59a6.34 6.34 0 0 0-5.46 6.25 6.34 6.34 0 1 0 11.45-3.8 8.28 8.28 0 0 0 4.12 1.09V7.69a4.84 4.84 0 0 1-0.07-1z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/klinikkomp_id" target="_blank" rel="noopener noreferrer" title="Instagram @klinikkomp_id" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s ease;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="https://youtube.com/@kk-mediaa?si=vdpkezTBYu35niBe" target="_blank" rel="noopener noreferrer" title="YouTube @kk-mediaa" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s ease;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="https://www.tokopedia.com/k-techid" target="_blank" rel="noopener noreferrer" title="Tokopedia Official Store" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 1px solid rgba(255,255,255,0.15); transition: all 0.2s ease;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </a>
                </div>
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
                    <li><a href="{{ route('pkl.index') }}">Internship</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kontak &amp; Sosmed</h4>
                <ul>
                    <li><a href="https://wa.me/6285103051000" target="_blank">085103051000</a></li>
                    <li><a href="https://maps.google.com/?q=Komplek+Ruko+Segitiga+Emas+Kosambi,+Jl.+A.+Yani+Blok+E8,+Merdeka,+Kec.+Sumur+Bandung,+Kota+Bandung,+Jawa+Barat+40113" target="_blank" style="line-height:1.45;">Komplek Ruko Segitiga Emas Kosambi, Jl. A. Yani Blok E8, Merdeka, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40113</a></li>
                    <li><a href="https://www.tiktok.com/@klinikkomp?_r=1&amp;_t=ZS-98VdwA99Nye" target="_blank">TikTok @klinikkomp</a></li>
                    <li><a href="https://www.instagram.com/klinikkomp_id" target="_blank">Instagram @klinikkomp_id</a></li>
                    <li><a href="https://youtube.com/@kk-mediaa?si=vdpkezTBYu35niBe" target="_blank">YouTube KK Media</a></li>
                    <li><a href="https://www.tokopedia.com/k-techid" target="_blank">Tokopedia Official Store</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Klinik Komputer | PT Mabito Karya. All rights reserved.</span>
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
            const navLinks = document.getElementById('navLinks');
            const navOverlay = document.getElementById('navOverlay');
            const isOpen = navLinks.classList.contains('open');

            if (isOpen) {
                closeMobileMenu();
            } else {
                navLinks.classList.add('open');
                if (navOverlay) navOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            const navOverlay = document.getElementById('navOverlay');
            if (navLinks) navLinks.classList.remove('open');
            if (navOverlay) navOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        // Auto-hide flash messages
        setTimeout(() => {
            const msg = document.getElementById('flash-msg');
            if (msg) { msg.style.opacity = '0'; msg.style.transition = 'opacity 0.5s'; setTimeout(() => msg.remove(), 500); }
        }, 4000);

        // Global Interceptor for Confirm Modals (SweetAlert2 Centered Popup)
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.dataset.swalBypassed) return;

                const onsubmitAttr = form.getAttribute('onsubmit') || '';
                const dataConfirm = form.getAttribute('data-confirm');
                let confirmMsg = dataConfirm;

                if (!confirmMsg && onsubmitAttr.includes('confirm(')) {
                    const match = onsubmitAttr.match(/confirm\((['"])(.*?)\1\)/);
                    if (match && match[2]) {
                        confirmMsg = match[2];
                    }
                }

                if (confirmMsg) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    Swal.fire({
                        title: 'Konfirmasi Aksi',
                        text: confirmMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.swalBypassed = 'true';
                            form.removeAttribute('onsubmit');
                            form.submit();
                        }
                    });
                    return false;
                }
            }, true);

            document.addEventListener('click', function(e) {
                const target = e.target.closest('[onclick*="confirm("], [data-confirm]');
                if (!target) return;

                if (target.dataset.swalBypassed) return;

                const onclickAttr = target.getAttribute('onclick') || '';
                const dataConfirm = target.getAttribute('data-confirm');
                let confirmMsg = dataConfirm;

                if (!confirmMsg && onclickAttr.includes('confirm(')) {
                    const match = onclickAttr.match(/confirm\((['"])(.*?)\1\)/);
                    if (match && match[2]) {
                        confirmMsg = match[2];
                    }
                }

                if (confirmMsg) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    Swal.fire({
                        title: 'Konfirmasi Aksi',
                        text: confirmMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            target.dataset.swalBypassed = 'true';
                            const origOnclick = target.getAttribute('onclick');
                            target.removeAttribute('onclick');
                            target.click();
                            if (origOnclick) target.setAttribute('onclick', origOnclick);
                        }
                    });
                }
            }, true);
        });
    </script>

    @stack('scripts')
</body>
</html>
