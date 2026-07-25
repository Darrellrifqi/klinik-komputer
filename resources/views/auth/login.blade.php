@extends('layouts.app')
@section('title', 'Masuk — Klinik Komputer')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background: var(--bg-alt);">
    <div style="width: 100%; max-width: 400px; padding: 40px 0;">
        <!-- Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="display: inline-block; font-weight: 900; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-primary); margin-bottom: 12px;">
                KLINIK KOMPUTER
            </a>
            <h1 style="font-size: 1.5rem; margin-bottom: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em;">Masuk</h1>
            <p style="color: var(--text-secondary); font-size: 0.85rem;">Gunakan akun terdaftar Anda</p>
        </div>

        <!-- Card -->
        <div class="card" style="padding: 28px;">
            @if($errors->any())
            <div class="alert alert-error">
                <div>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email <span>*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="nama@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" id="pwdField"
                               placeholder="Masukkan password" required style="padding-right: 40px;">
                        <button type="button" onclick="togglePwd()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.95rem;">Lihat</button>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary); width: 14px; height: 14px;">
                    <label for="remember" style="font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; user-select: none;">Ingat Sesi Masuk</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Masuk
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-light);">
                <span style="color: var(--text-muted); font-size: 0.82rem;">Belum memiliki akun?</span>
                <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 700; margin-left: 4px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.02em;">Daftar</a>
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
function togglePwd() {
    const f = document.getElementById('pwdField');
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
