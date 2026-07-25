<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Klinik Komputer</title>
    <link rel="icon" type="image/png" href="/favicon-kk.png">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/dashboard.css">
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
            <div class="sidebar-user">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ $roleLabel }}</div>
                </div>
            </div>
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
</script>

    @stack('scripts')
</body>
</html>
