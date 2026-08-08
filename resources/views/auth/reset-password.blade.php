@extends('layouts.app')
@section('title', 'Reset Password | Klinik Komputer')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px 40px; background: var(--bg-alt); box-sizing: border-box;">
    <div style="width: 100%; max-width: 420px; padding: 40px 0;">
        <!-- Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 12px;">
                <img src="/images/login-logo.png" alt="Logo Klinik Komputer" style="height: 72px; width: auto; max-width: 220px; object-fit: contain; margin-left: -10px;">
            </a>
            <h1 style="font-size: 1.4rem; margin-bottom: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em;">Reset Password</h1>
            <p style="color: var(--text-secondary); font-size: 0.85rem;">Buat password baru yang aman untuk akun Anda.</p>
        </div>

        <!-- Card -->
        <div class="card" style="padding: 28px;">
            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <div>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Alamat Email <span>*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}"
                           placeholder="nama@email.com" required readonly style="background: var(--bg-alt); cursor: not-allowed;">
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Password Baru <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" id="pwdNew"
                               placeholder="Minimal 8 karakter" required style="padding-right: 40px;">
                        <button type="button" onclick="toggleField('pwdNew')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.9rem;">Lihat</button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Konfirmasi Password Baru <span>*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" class="form-control" id="pwdConfirm"
                               placeholder="Ulangi password baru" required style="padding-right: 40px;">
                        <button type="button" onclick="toggleField('pwdConfirm')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.9rem;">Lihat</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="height: 42px; font-weight: 700;">
                    Reset Password Saya
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleField(id) {
    const f = document.getElementById(id);
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
