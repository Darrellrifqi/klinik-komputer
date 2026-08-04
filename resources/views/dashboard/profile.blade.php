@extends('layouts.dashboard')

@section('title', 'Pengaturan Profile | Klinik Komputer')
@section('page_title', 'Pengaturan Profile Akun')
@section('page_subtitle', 'Kelola informasi data diri, foto profil, dan keamanan akun Anda')

@section('sidebar_nav')
@php $role = auth()->user()->role; @endphp

@if($role === 'customer')
    <a href="{{ route('dashboard.customer') }}">
        <span class="nav-icon">Tiket &amp; Laptop Saya</span>
    </a>
    <a href="{{ route('dashboard.customer.chat') }}">
        <span class="nav-icon">Hubungi CS (Chat)</span>
        @php $unreadCust = \App\Models\Chat::where('customer_id', auth()->id())->where('unread_by_customer', true)->count(); @endphp
        @if($unreadCust > 0)<span class="badge-count">{{ $unreadCust }}</span>@endif
    </a>
    <a href="{{ route('service.booking') }}">
        <span class="nav-icon">Booking Servis Baru</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="active">
        <span class="nav-icon">Pengaturan Profile</span>
    </a>
    <div class="sidebar-section-label">Navigasi</div>
    <a href="{{ route('service.track') }}"><span class="nav-icon">Cek Status Tiket</span></a>
    <a href="{{ route('home') }}"><span class="nav-icon">Beranda Utama</span></a>

@elseif($role === 'cs')
    <a href="{{ route('dashboard.cs') }}"><span class="nav-icon">Semua Tiket</span></a>
    <a href="{{ route('dashboard.cs.create') }}"><span class="nav-icon">Buat Tiket Baru</span></a>
    <a href="{{ route('dashboard.cs.chat') }}"><span class="nav-icon">Konsultasi Member</span></a>
    <a href="{{ route('profile.edit') }}" class="active"><span class="nav-icon">Pengaturan Profile</span></a>
    <div class="sidebar-section-label">Membership</div>
    <a href="{{ route('cs.users') }}"><span class="nav-icon">Persetujuan Member</span></a>
    <a href="{{ route('cs.regular-members') }}"><span class="nav-icon">Daftar Member Mandiri</span></a>

@elseif($role === 'teknisi')
    <a href="{{ route('dashboard.teknisi') }}"><span class="nav-icon">Antrean Servis Saya</span></a>
    <a href="{{ route('profile.edit') }}" class="active"><span class="nav-icon">Pengaturan Profile</span></a>
    <div class="sidebar-section-label">Navigasi</div>
    <a href="{{ route('home') }}"><span class="nav-icon">Beranda</span></a>

@elseif($role === 'produksi')
    <a href="{{ route('dashboard.produksi') }}"><span class="nav-icon">Daftar Pengadaan</span></a>
    <a href="{{ route('profile.edit') }}" class="active"><span class="nav-icon">Pengaturan Profile</span></a>

@elseif($role === 'superadmin')
    <a href="{{ route('dashboard.admin') }}"><span class="nav-icon">Ringkasan Admin</span></a>
    <a href="{{ route('admin.users') }}"><span class="nav-icon">Kelola User</span></a>
    <a href="{{ route('admin.tickets') }}"><span class="nav-icon">Kelola Tiket</span></a>
    <a href="{{ route('profile.edit') }}" class="active"><span class="nav-icon">Pengaturan Profile</span></a>
    <div class="sidebar-section-label">Pengadaan &amp; Toko</div>
    <a href="{{ route('admin.products') }}"><span class="nav-icon">Katalog Produk</span></a>
    <a href="{{ route('admin.procurement') }}"><span class="nav-icon">Order Pengadaan</span></a>
@endif
@endsection

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- CARD 1: FOTO PROFIL & IDENTITAS RINGKAS --}}
        <div class="dash-card" style="margin-bottom: 24px;">
            <div class="dash-card-header">
                <h3>Foto Profil</h3>
            </div>
            <div class="dash-card-body">
                <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
                    <div style="position: relative;">
                        <img id="avatarPreview"
                             src="{{ $user->avatar_url }}"
                             alt="{{ $user->name }}"
                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    </div>
                    
                    <div style="flex: 1; min-width: 220px; display: flex; flex-direction: column; gap: 8px;">
                        <label for="avatarInput" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 8px; width: fit-content; cursor: pointer;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            Pilih Foto Baru
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display: none;" onchange="previewAvatar(event)">
                        
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">
                            Format yang didukung: <strong>JPG, PNG, WEBP, GIF</strong>. Ukuran maksimal <strong>2MB</strong>.
                        </p>

                        <div style="display: flex; gap: 8px; align-items: center; margin-top: 4px;">
                            <span class="badge badge-primary" style="font-size: 0.7rem; text-transform: uppercase;">Role: {{ $user->role }}</span>
                            <span class="badge badge-success" style="font-size: 0.7rem;">Akun {{ ucfirst($user->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: INFORMASI DATA DIRI --}}
        <div class="dash-card" style="margin-bottom: 24px;">
            <div class="dash-card-header">
                <h3>Informasi Data Diri</h3>
            </div>
            <div class="dash-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                    
                    {{-- Nama Lengkap --}}
                    <div class="form-group">
                        <label for="name" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Nama Lengkap <span style="color:red;">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                    {{-- Alamat Email --}}
                    <div class="form-group">
                        <label for="email" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Alamat Email <span style="color:red;">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                    {{-- Nomor Telepon / WhatsApp --}}
                    <div class="form-group">
                        <label for="phone" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                    {{-- NIK (Readonly jika ada) --}}
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block; color: var(--text-muted);">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" class="form-control" value="{{ $user->nik ?? '-' }}" readonly style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; background: var(--bg-alt); color: var(--text-muted); font-family: monospace;">
                    </div>

                </div>
            </div>
        </div>

        {{-- CARD 3: KEAMANAN & PASSWORD --}}
        <div class="dash-card" style="margin-bottom: 24px;">
            <div class="dash-card-header">
                <h3>Keamanan &amp; Ubah Password</h3>
            </div>
            <div class="dash-card-body">
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px;">
                    Kosongkan bagian ini jika Anda tidak ingin mengubah password akun Anda.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    
                    {{-- Password Saat Ini --}}
                    <div class="form-group">
                        <label for="current_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="••••••••" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-group">
                        <label for="new_password" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Password Baru</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Minimal 6 karakter" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div class="form-group">
                        <label for="new_password_confirmation" style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Konfirmasi Password Baru</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem;">
                    </div>

                </div>
            </div>
        </div>

        {{-- BUTTON SUBMIT --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 11px 28px; font-weight: 700; font-size: 0.9rem; border-radius: var(--radius-sm); display: inline-flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Perubahan Profil
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
