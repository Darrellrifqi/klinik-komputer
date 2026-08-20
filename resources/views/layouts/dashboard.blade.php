<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Laravel">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Klinik Komputer</title>
    <link rel="icon" type="image/png" href="/favicon-kk.png?v=5">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/dashboard.css">
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
        nav[role="navigation"] svg,
        .pagination svg,
        svg.w-5,
        svg.h-5,
        svg.w-4,
        svg.h-4 {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            min-width: 16px !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="dashboard-wrapper">

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        @php
            $isMember = (auth()->user()->role === 'customer' && auth()->user()->laptopKits()->exists());
            $roleLabel = auth()->user()->role;
            if ($roleLabel === 'customer') {
                $roleLabel = $isMember ? 'Member' : 'Customer';
            } else {
                $roleLabel = ucfirst($roleLabel);
            }
        @endphp
        <div class="sidebar-brand" style="padding: 24px 20px 5px; border-bottom: 1px solid var(--border-light); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 10px; height: auto;">
            <img src="/images/logo.png" alt="Logo Klinik Komputer" style="height: 60px; width: auto; max-width: 180px; object-fit: contain;">
            <small style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; display: block;">Panel {{ $roleLabel }}</small>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar_nav')
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="sidebar-user" style="text-decoration: none; transition: opacity 0.2s;" title="Pengaturan Profile">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--primary);">
                <div class="user-info">
                    <div class="user-name" style="color: var(--text-primary);">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ $roleLabel }} • <span style="color: var(--primary); font-weight: 600;">Edit Profil</span></div>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm btn-block">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="dashboard-main">
        <!-- Topbar -->
        <div class="dashboard-topbar">
            <div class="d-flex align-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
                <div class="topbar-title">
                    <h2>@yield('page_title', 'Dashboard')</h2>
                    <p>@yield('page_subtitle', date('l, d F Y'))</p>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('home') }}" class="btn btn-outline btn-sm" target="_blank">Website</a>
                @yield('topbar_actions')
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div style="padding: 16px 24px 0;" id="flash-msg">
                <div class="alert alert-success"><span>{{ session('success') }}</span></div>
            </div>
        @endif
        @if(session('error'))
            <div style="padding: 16px 24px 0;" id="flash-msg">
                <div class="alert alert-error"><span>{{ session('error') }}</span></div>
            </div>
        @endif
        @if($errors->any())
            <div style="padding: 16px 24px 0;">
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:14px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="dashboard-content">
            @yield('content')
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
// Auto-hide flash
setTimeout(() => {
    const msg = document.getElementById('flash-msg');
    if (msg) { msg.style.opacity = '0'; msg.style.transition = 'opacity 0.5s'; setTimeout(() => msg.remove(), 500); }
}, 4000);

// Global Interceptor for Confirm Modals (SweetAlert2 Centered Popup)
document.addEventListener('DOMContentLoaded', function() {
    // Intercept form submit
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

    // Intercept button/link click with inline confirm
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
