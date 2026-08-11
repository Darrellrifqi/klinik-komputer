@extends('layouts.app')
@section('title', 'Input Data History PKL')

@section('content')
<div style="background: linear-gradient(135deg, rgba(95, 138, 99, 0.05) 0%, rgba(59, 142, 202, 0.05) 100%); min-height: 100vh; padding: 110px 20px 60px; box-sizing: border-box;">
    <div style="max-width: 600px; margin: 0 auto;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 40px;">
            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.1em; background: rgba(95, 138, 99, 0.1); padding: 6px 16px; border-radius: 20px;">Input Mandiri</span>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--text-primary); margin-top: 12px; letter-spacing: -0.5px;">History PKL & Magang</h1>
            <p style="color: var(--text-muted); font-size: 1rem; margin-top: 8px;">Klinik Komputer Bandung &bull; Portofolio Alumni Magang</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 16px 20px;">
                <h4 style="color: #15803d; font-weight: 700; margin: 0 0 4px 0;">Sukses!</h4>
                <p style="margin: 0; font-size: 0.9rem; color: #166534;">{{ session('success') }}</p>
                <div style="margin-top: 12px;">
                    <a href="{{ route('pkl.index') }}" class="btn btn-primary btn-sm" style="border-radius: 6px;">Lihat History PKL</a>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 16px 20px;">
                <h4 style="color: #b91c1c; font-weight: 700; margin: 0 0 4px 0;">Terjadi Kesalahan:</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.88rem; color: #991b1b;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="dash-card" style="border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); border: 1px solid var(--border-light); overflow: hidden;">
            <div style="background: var(--bg-card); padding: 32px;">
                <form action="{{ route('pkl.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Section 1: Validasi -->
                    <div style="border-bottom: 1px solid var(--border-light); padding-bottom: 24px; margin-bottom: 24px;">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.5px;">1. Validasi Akses</h3>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700;">TOKEN REGISTRASI PKL <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="token" class="form-control" placeholder="Masukkan token kuartal aktif (contoh: PKL-2026-Q3)" value="{{ old('token') }}" required style="padding: 12px; border-radius: 8px; font-family: monospace; font-size: 1.05rem; letter-spacing: 0.5px;">
                            <small class="form-text text-muted" style="font-size: 0.78rem; display: block; margin-top: 6px; color: var(--text-muted);">Minta token registrasi aktif ke Superadmin atau Pembimbing PKL Anda.</small>
                        </div>
                    </div>

                    <!-- Section 2: Biodata & Detail PKL -->
                    <div style="margin-bottom: 24px;">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.5px;">2. Biodata & Detail Magang</h3>
                        
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" style="font-weight: 700;">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap Anda" value="{{ old('name') }}" required style="padding: 12px; border-radius: 8px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" style="font-weight: 700;">Asal Sekolah / Universitas <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="school" class="form-control" placeholder="Contoh: SMKN 1 Demak, Universitas Telkom" value="{{ old('school') }}" required style="padding: 12px; border-radius: 8px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" style="font-weight: 700;">Divisi Magang <span style="color: var(--danger);">*</span></label>
                            <select name="division" class="form-control" required style="padding: 12px; border-radius: 8px; height: auto;">
                                <option value="" disabled selected>Pilih Divisi Magang</option>
                                <option value="Teknisi" {{ old('division') === 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                                <option value="CS" {{ old('division') === 'CS' ? 'selected' : '' }}>CS</option>
                                <option value="Produksi" {{ old('division') === 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            </select>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 700;">Tanggal Mulai PKL <span style="color: var(--danger);">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required style="padding: 12px; border-radius: 8px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" style="font-weight: 700;">Tanggal Selesai PKL <span style="color: var(--danger);">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required style="padding: 12px; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label" style="font-weight: 700;">Kesan & Pesan Selama PKL</label>
                            <textarea name="testimonial" class="form-control" rows="3" placeholder="Tuliskan kesan dan pesan kamu selama mengikuti kegiatan PKL/magang di Klinik Komputer..." style="padding: 12px; border-radius: 8px;">{{ old('testimonial') }}</textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700;">Foto Profil (Pas Foto) <span style="color: var(--danger);">*</span></label>
                            <input type="file" name="photo" accept="image/*" class="form-control" required style="padding: 8px; border-radius: 8px; border-style: dashed;">
                            <small style="font-size: 0.78rem; display: block; margin-top: 6px; color: var(--text-muted);">Gunakan foto formal/semi-formal rapi. Maksimal 4MB (JPG, PNG, WebP).</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: center; border-top: 1px solid var(--border-light); padding-top: 24px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 0.95rem; font-weight: 700; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(95, 138, 99, 0.2);">
                            Kirim Data History PKL
                        </button>
                        <a href="{{ route('pkl.index') }}" style="display: inline-block; margin-top: 14px; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-decoration: none;">Kembali ke History PKL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
