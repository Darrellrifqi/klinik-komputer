@extends('layouts.app')
@section('title', 'Lupa Password | Klinik Komputer')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px 40px; background: var(--bg-alt); box-sizing: border-box;">
    <div style="width: 100%; max-width: 420px; padding: 40px 0;">
        <!-- Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="{{ route('home') }}" style="display: inline-block; margin-bottom: 12px;">
                <img src="/images/login-logo.png" alt="Logo Klinik Komputer" style="height: 72px; width: auto; max-width: 220px; object-fit: contain; margin-left: -10px;">
            </a>
            <h1 style="font-size: 1.4rem; margin-bottom: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em;">Lupa Password?</h1>
            <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.4;">Masukkan alamat email terdaftar Anda. Kami akan mengirimkan link verifikasi untuk me-reset password Anda.</p>
        </div>

        <!-- Card -->
        <div class="card" style="padding: 28px;">
            @if(session('status'))
            <div class="alert alert-success" style="display: block !important; margin-bottom: 20px; padding: 18px; border-radius: 10px; background: rgba(34, 197, 94, 0.08); border: 1px solid rgba(34, 197, 94, 0.3); color: #15803d; text-align: center;">
                <div style="font-weight: 800; font-size: 1.05rem; color: #166534; margin-bottom: 6px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Email Terverifikasi!
                </div>
                <div style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 16px; font-weight: 500; line-height: 1.4;">
                    Silakan klik tombol di bawah ini untuk membuat password baru Anda:
                </div>
                @if(session('resetUrl'))
                <a href="{{ session('resetUrl') }}" style="display: block; width: 100%; box-sizing: border-box; text-align: center; padding: 12px 16px; background: #2563eb; color: #ffffff; font-weight: 700; font-size: 0.88rem; border-radius: 8px; text-decoration: none; border: none; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    Reset Password Sekarang
                </a>
                @endif
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <div>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Alamat Email Terdaftar <span>*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="nama@email.com" required autofocus style="height: 42px;">
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="height: 42px; font-weight: 700;">
                    Kirim Link Reset Password
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--border-light);">
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; font-size: 0.82rem; text-decoration: none;">
                    Kembali ke Halaman Masuk
                </a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 16px;">
            <a href="{{ route('home') }}" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none;">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
