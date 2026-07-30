@extends('layouts.app')
@section('title', 'Daftar Akun | Klinik Komputer')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px 40px; background: var(--bg-alt); box-sizing: border-box;">
    <div style="width: 100%; max-width: 480px;">
        <!-- Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 12px;">
                <img src="/images/login-logo.png" alt="Logo Klinik Komputer" style="height: 72px; width: auto; max-width: 220px; object-fit: contain; margin-left: -10px;">
            </a>
            <h1 style="font-size: 1.5rem; margin-bottom: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em;">Buat Akun Baru</h1>
            <p style="color: var(--text-secondary); font-size: 0.85rem;">Daftar akun customer untuk kemudahan booking servis &amp; pemesanan</p>
        </div>

        <!-- Form Card -->
        <div class="card" style="padding: 28px;">
            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="customer">

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span>*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                               placeholder="Nama lengkap sesuai identitas" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span>*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                               placeholder="nama@email.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor HP / WhatsApp <span>*</span></label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                               placeholder="Contoh: 081234567890" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label class="form-label">Password <span>*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="password" class="form-control" id="pwd1"
                                       placeholder="Min. 8 karakter" required style="padding-right: 40px;">
                                <button type="button" onclick="togglePwd('pwd1')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.75rem;">Lihat</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password <span>*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="password_confirmation" class="form-control" id="pwd2"
                                       placeholder="Ketik ulang" required style="padding-right: 40px;">
                                <button type="button" onclick="togglePwd('pwd2')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.75rem;">Lihat</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 16px; font-weight: 700; padding: 12px;">
                    Daftar Akun Sekarang
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
function togglePwd(id) {
    const f = document.getElementById(id);
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
