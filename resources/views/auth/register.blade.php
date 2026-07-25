@extends('layouts.app')
@section('title', 'Daftar Akun — Klinik Komputer')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 80px 20px 40px; background: var(--bg-alt);">
    <div style="width: 100%; max-width: 480px;">
        <!-- Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="display: inline-block; font-weight: 900; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-primary); margin-bottom: 12px;">
                KLINIK KOMPUTER
            </a>
            <h1 style="font-size: 1.5rem; margin-bottom: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em;">Buat Akun</h1>
            <p style="color: var(--text-secondary); font-size: 0.85rem;">Pilih jenis akun sesuai dengan peran Anda</p>
        </div>

        <!-- Role Selector -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 20px;">
            @php $roles = [
                ['val'=>'customer', 'label'=>'Customer', 'desc'=>'Servis'],
                ['val'=>'cs',       'label'=>'CS',       'desc'=>'Staff CS'],
                ['val'=>'teknisi',  'label'=>'Teknisi',  'desc'=>'Teknisi'],
                ['val'=>'produksi', 'label'=>'Produksi', 'desc'=>'Gudang'],
            ]; @endphp
            @foreach($roles as $role)
            <label for="role_{{ $role['val'] }}" style="cursor: pointer;">
                <input type="radio" name="role_select" id="role_{{ $role['val'] }}" value="{{ $role['val'] }}"
                       style="display: none;" onchange="selectRole('{{ $role['val'] }}')"
                       {{ old('role', 'customer') === $role['val'] ? 'checked' : '' }}>
                <div class="role-card" id="rc_{{ $role['val'] }}" style="text-align: center; padding: 10px 4px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); transition: var(--transition);">
                    <div style="font-weight: 700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-primary);">{{ $role['label'] }}</div>
                    <div style="font-size: 0.65rem; color: var(--text-muted); margin-top: 2px;">{{ $role['desc'] }}</div>
                </div>
            </label>
            @endforeach
        </div>

        <!-- Info for CS/Teknisi/Produksi -->
        <div id="pendingInfo" style="display: none; margin-bottom: 16px;">
            <div class="alert alert-warning">
                <span style="font-size: 0.8rem;">Pendaftaran akun staff CS/Teknisi/Produksi membutuhkan persetujuan Super Admin sebelum dapat digunakan.</span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card" style="padding: 28px;">
            @if($errors->any())
            <div class="alert alert-error">
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'customer') }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Nama Lengkap <span>*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                               placeholder="Nama lengkap sesuai identitas" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Alamat Email <span>*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                               placeholder="nama@email.com" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Nomor HP / WhatsApp <span>*</span></label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                               placeholder="Contoh: 081234567890" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password <span>*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password" class="form-control" id="pwd1"
                                   placeholder="Min. 8 karakter" required style="padding-right: 40px;">
                            <button type="button" onclick="togglePwd('pwd1')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.85rem;">Lihat</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span>*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" class="form-control" id="pwd2"
                                   placeholder="Ketik ulang password" required style="padding-right: 40px;">
                            <button type="button" onclick="togglePwd('pwd2')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.85rem;">Lihat</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 8px;">
                    Daftar Akun
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                <span style="color: var(--text-muted); font-size: 0.82rem;">Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; margin-left: 4px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.02em;">Masuk</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 16px;">
            <a href="{{ route('home') }}" style="color: var(--text-muted); font-size: 0.8rem;">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;
    document.querySelectorAll('.role-card').forEach(c => {
        c.style.borderColor = 'var(--border)';
        c.style.background = 'var(--bg-card)';
        c.querySelector('div').style.color = 'var(--text-primary)';
    });
    const selected = document.getElementById('rc_' + role);
    selected.style.borderColor = 'var(--primary)';
    selected.style.background = 'var(--primary-glow)';
    selected.querySelector('div').style.color = 'var(--primary-dark)';
    document.getElementById('pendingInfo').style.display = (role === 'cs' || role === 'teknisi' || role === 'produksi') ? 'block' : 'none';
}
function togglePwd(id) {
    const f = document.getElementById(id);
    f.type = f.type === 'password' ? 'text' : 'password';
}
// Init
selectRole('{{ old('role', 'customer') }}');
</script>
@endpush
