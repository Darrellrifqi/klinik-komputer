<a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
    <span class="nav-icon">Overview</span>
</a>
<a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
    <span class="nav-icon">Kelola User</span>
    @php $pending = \App\Models\User::where('status','pending')->count(); @endphp
    @if($pending > 0)<span class="badge-count">{{ $pending }}</span>@endif
</a>
<a href="{{ route('admin.tickets') }}" class="{{ request()->routeIs('admin.tickets') && !request()->routeIs('admin.tickets.history*') ? 'active' : '' }}">
    <span class="nav-icon">Tiket Servis Aktif</span>
</a>
<a href="{{ route('admin.tickets.history') }}" class="{{ request()->routeIs('admin.tickets.history*') ? 'active' : '' }}">
    <span class="nav-icon">History Servis Selesai</span>
</a>
<a href="{{ route('admin.procurement') }}" class="{{ request()->routeIs('admin.procurement') ? 'active' : '' }}">
    <span class="nav-icon">Daftar Pengadaan</span>
</a>
<a href="{{ route('admin.procurement-products') }}" class="{{ request()->routeIs('admin.procurement-products*') ? 'active' : '' }}">
    <span class="nav-icon">Daftar Unit Pengadaan</span>
</a>
<a href="{{ route('admin.procurement-kits') }}" class="{{ request()->routeIs('admin.procurement-kits*') ? 'active' : '' }}">
    <span class="nav-icon">Member Pengadaan</span>
</a>
<a href="{{ route('admin.regular-members') }}" class="{{ request()->routeIs('admin.regular-members*') ? 'active' : '' }}">
    <span class="nav-icon">Member Mandiri</span>
</a>
<div class="sidebar-section-label">Produk</div>
<a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
    <span class="nav-icon">Kelola Produk</span>
</a>
<a href="{{ route('admin.products.create') }}" class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
    <span class="nav-icon">Tambah Produk</span>
</a>
<div class="sidebar-section-label">Homepage & Banners</div>
<a href="{{ route('admin.hero') }}" class="{{ request()->routeIs('admin.hero*') ? 'active' : '' }}">
    <span class="nav-icon">Kelola Cover</span>
</a>
<a href="{{ route('admin.banners') }}" class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
    <span class="nav-icon">Kelola Banner Halaman</span>
</a>
<div class="sidebar-section-label">Internship & PKL</div>
<a href="{{ route('admin.acp.schools') }}" class="{{ request()->routeIs('admin.acp.schools*') ? 'active' : '' }}">
    <span class="nav-icon">Mitra Sekolah ACP</span>
</a>
<a href="{{ route('admin.internship.applications') }}" class="{{ request()->routeIs('admin.internship.applications*') ? 'active' : '' }}">
    <span class="nav-icon">Pengajuan Sekolah</span>
    @php $pendingApps = \App\Models\InternshipApplication::where('status','pending')->count(); @endphp
    @if($pendingApps > 0)<span class="badge-count">{{ $pendingApps }}</span>@endif
</a>
<a href="{{ route('admin.pkl.tokens') }}" class="{{ request()->routeIs('admin.pkl.tokens*') ? 'active' : '' }}">
    <span class="nav-icon">Kelola Token PKL</span>
</a>
<a href="{{ route('admin.pkl.divisions') }}" class="{{ request()->routeIs('admin.pkl.divisions*') ? 'active' : '' }}">
    <span class="nav-icon">Kelola Divisi PKL</span>
</a>
<a href="{{ route('admin.pkl.students') }}" class="{{ request()->routeIs('admin.pkl.students*') ? 'active' : '' }}">
    <span class="nav-icon">Review Pendaftar</span>
    @php $pendingPkl = \App\Models\PklStudent::where('status','pending')->count(); @endphp
    @if($pendingPkl > 0)<span class="badge-count">{{ $pendingPkl }}</span>@endif
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('products') }}" target="_blank"><span class="nav-icon">Katalog Publik</span></a>
<a href="{{ route('home') }}"><span class="nav-icon">Beranda</span></a>
<a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="nav-icon">Pengaturan Profile</span>
</a>
