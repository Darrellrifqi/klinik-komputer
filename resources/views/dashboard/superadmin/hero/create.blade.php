@extends('layouts.dashboard')
@section('title', 'Tambah Cover — Super Admin')
@section('page_title', 'Tambah Cover')
@section('page_subtitle', 'Tambahkan slide kustom baru untuk beranda utama')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="max-width: 800px;">
    <a href="{{ route('admin.hero') }}" class="btn btn-outline btn-sm" style="margin-bottom: 16px;">Kembali</a>

    <div class="dash-card">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 14px; margin-bottom: 20px;">
            <h4 style="margin: 0; color: var(--primary);">Form Tambah Slide</h4>
        </div>
        
        <form action="{{ route('admin.hero.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Gambar Latar Belakang <span>*</span></label>
                <input type="file" name="image" class="form-control" accept="image/*" required 
                       style="padding: 8px 12px; border: 1px solid #c8d3c9; border-radius: 6px;">
                <span class="form-hint">Rekomendasi ukuran: minimal 1280x720 piksel (rasio 16:9), format JPG, PNG, atau WEBP (Maksimal 5MB).</span>
                @error('image')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Tag Slide (Kecil di Atas)</label>
                    <input type="text" name="tag" class="form-control" placeholder="Contoh: Mitra Resmi Axioo" value="{{ old('tag') }}"
                           style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                    @error('tag')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Urutan Tampilan</label>
                    <input type="number" name="order_index" class="form-control" placeholder="Contoh: 0, 1, 2" value="{{ old('order_index', 0) }}"
                           style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                    <span class="form-hint">Slide diurutkan dari nomor terkecil.</span>
                    @error('order_index')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Judul Utama (H1)</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Solusi IT Terpercaya" value="{{ old('title') }}"
                       style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                @error('title')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi / Paragraf</label>
                <textarea name="description" class="form-control" placeholder="Tuliskan deskripsi singkat mengenai slide..." rows="3"
                          style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">{{ old('description') }}</textarea>
                @error('description')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="border-top: 1px solid var(--border-light); padding-top: 16px; margin-top: 20px; margin-bottom: 16px;">
                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 12px;">Link Tombol Aksi (CTA)</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 12px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Teks Tombol Utama</label>
                        <input type="text" name="cta_text_primary" class="form-control" placeholder="Contoh: Booking Servis" value="{{ old('cta_text_primary') }}"
                               style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                        @error('cta_text_primary')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">URL Link Utama</label>
                        <input type="text" name="cta_url_primary" class="form-control" placeholder="Contoh: /service atau https://..." value="{{ old('cta_url_primary') }}"
                               style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                        @error('cta_url_primary')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Teks Tombol Kedua</label>
                        <input type="text" name="cta_text_secondary" class="form-control" placeholder="Contoh: Hubungi Sales" value="{{ old('cta_text_secondary') }}"
                               style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                        @error('cta_text_secondary')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">URL Link Kedua</label>
                        <input type="text" name="cta_url_secondary" class="form-control" placeholder="Contoh: https://wa.me/..." value="{{ old('cta_url_secondary') }}"
                               style="padding: 10px 14px; border: 1px solid #c8d3c9; border-radius: 6px;">
                        @error('cta_url_secondary')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px; display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked style="width: 16px; height: 16px; cursor: pointer;">
                <label for="is_active" style="font-size: 0.85rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">Aktifkan slide ini langsung</label>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 16px; padding: 12px 24px; font-weight: 700; border-radius: 6px;">Simpan Slide</button>
        </form>
    </div>
</div>
@endsection
