<a href="{{ route('dashboard.cs') }}" class="{{ request()->routeIs('dashboard.cs') && !request()->routeIs('dashboard.cs.history*') && !request()->routeIs('dashboard.cs.chat*') && !request()->routeIs('dashboard.cs.create') && !request()->routeIs('dashboard.cs.procurement*') && !request()->routeIs('dashboard.cs.show') ? 'active' : '' }}">
    <span class="nav-icon">Tiket Aktif</span>
</a>
<a href="{{ route('dashboard.cs.history') }}" class="{{ request()->routeIs('dashboard.cs.history*') ? 'active' : '' }}">
    <span class="nav-icon">Riwayat Servis</span>
</a>
<a href="{{ route('dashboard.cs.create') }}" class="{{ request()->routeIs('dashboard.cs.create') ? 'active' : '' }}">
    <span class="nav-icon">Buat Tiket Baru</span>
</a>
<a href="{{ route('dashboard.cs.chat') }}" class="{{ request()->routeIs('dashboard.cs.chat*') ? 'active' : '' }}">
    <span class="nav-icon">Konsultasi Member</span>
    @php $unreadCS = \App\Models\Chat::where('unread_by_cs', true)->count(); @endphp
    @if($unreadCS > 0)
    <span style="background:var(--danger); color:#fff; border-radius:10px; font-size:0.65rem; font-weight:700; padding:2px 7px; margin-left:auto;">{{ $unreadCS }}</span>
    @endif
</a>
<div class="sidebar-section-label">Membership</div>
<a href="{{ route('cs.users') }}" class="{{ request()->routeIs('cs.users*') ? 'active' : '' }}">
    <span class="nav-icon">Persetujuan Member</span>
    @php $pendingUsers = \App\Models\User::where('role', 'customer')->where('status', 'pending')->count(); @endphp
    @if($pendingUsers > 0)
    <span style="background:var(--warning); color:#fff; border-radius:10px; font-size:0.65rem; font-weight:700; padding:2px 7px; margin-left:auto;">{{ $pendingUsers }}</span>
    @endif
</a>
<a href="{{ route('cs.regular-members') }}" class="{{ request()->routeIs('cs.regular-members*') ? 'active' : '' }}">
    <span class="nav-icon">Daftar Member Mandiri</span>
</a>

<div class="sidebar-section-label">Pengadaan Unit</div>
<a href="{{ route('dashboard.cs.procurement.index') }}" class="{{ request()->routeIs('dashboard.cs.procurement*') ? 'active' : '' }}">
    <span class="nav-icon">Tiket Pengadaan</span>
    @php $pendingProc = \App\Models\ProcurementOrder::where('status', 'pending')->count(); @endphp
    @if($pendingProc > 0)
    <span style="background:var(--warning); color:#fff; border-radius:10px; font-size:0.65rem; font-weight:700; padding:2px 7px; margin-left:auto;">{{ $pendingProc }}</span>
    @endif
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('service.track') }}">
    <span class="nav-icon">Cek Status Tiket</span>
</a>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
<div class="sidebar-section-label">Pengaturan</div>
<a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="nav-icon">Pengaturan Profile</span>
</a>
