@extends('layouts.dashboard')
@section('title', 'Kelola Banner Halaman | Super Admin')
@section('page_title', 'Kelola Banner Halaman')
@section('page_subtitle', 'Sesuaikan gambar latar belakang banner / cover halaman utama publik')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="max-width: 800px;">

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; border-radius: 8px; padding: 14px 20px; font-weight: 600;">
        {{ session('success') }}
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
        
        <!-- Form Banners -->
        <form action="{{ route('admin.banners.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Card 1: Banner Halaman Pengadaan -->
            <div class="dash-card" style="margin-bottom: 20px; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div class="dash-card-header" style="background: var(--bg-card); padding: 20px 24px; border-bottom: 1px solid var(--border-light);">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">1. Banner Halaman Pengadaan Laptop</h3>
                </div>
                <div class="dash-card-body" style="padding: 24px; background: var(--bg-card);">
                    
                    @php
                        $procBanner = '/images/procurement-hero.jpg';
                        $procExists = file_exists(public_path($procBanner));
                    @endphp

                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-weight: 700; display: block; margin-bottom: 8px; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Pratinjau Banner Saat Ini</label>
                        @if($procExists)
                            <div style="position: relative; width: 100%; height: 160px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); background: #eee;">
                                <img src="{{ $procBanner }}?v={{ filemtime(public_path($procBanner)) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Banner Pengadaan">
                                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.8rem; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">Custom Banner Aktif</div>
                            </div>
                            <div style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--danger); font-weight: 700; cursor: pointer;">
                                    <input type="checkbox" name="reset_procurement" value="1" style="accent-color: var(--danger); width: 14px; height: 14px;">
                                    Kembalikan ke Banner Default (Bawaan)
                                </label>
                            </div>
                        @else
                            <div style="width: 100%; height: 120px; border-radius: 8px; border: 1px dashed var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg-alt); color: var(--text-muted); font-size: 0.82rem; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span>Menggunakan banner bawaan (Background warna solid abu-abu)</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Unggah Gambar Banner Baru</label>
                        <input type="file" name="procurement_banner" accept="image/jpeg,image/png,image/webp,image/jpg" class="form-control" style="padding: 8px; border-radius: 8px; border-style: dashed;">
                        <small class="form-text text-muted" style="display: block; margin-top: 6px; font-size: 0.76rem; color: var(--text-muted);">Format: JPG, JPEG, PNG, WebP. Maksimal ukuran file: 4MB. Resolusi disarankan: 1920x600 px untuk hasil terbaik.</small>
                    </div>

                </div>
            </div>

            <!-- Card 2: Banner Halaman History PKL -->
            <div class="dash-card" style="margin-bottom: 24px; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15 rgba(0,0,0,0.02);">
                <div class="dash-card-header" style="background: var(--bg-card); padding: 20px 24px; border-bottom: 1px solid var(--border-light);">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">2. Banner Halaman History PKL & Magang</h3>
                </div>
                <div class="dash-card-body" style="padding: 24px; background: var(--bg-card);">
                    
                    @php
                        $pklBanner = '/images/pkl-hero.jpg';
                        $pklExists = file_exists(public_path($pklBanner));
                    @endphp

                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-weight: 700; display: block; margin-bottom: 8px; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Pratinjau Banner Saat Ini</label>
                        @if($pklExists)
                            <div style="position: relative; width: 100%; height: 160px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); background: #eee;">
                                <img src="{{ $pklBanner }}?v={{ filemtime(public_path($pklBanner)) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Banner PKL">
                                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.8rem; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">Custom Banner Aktif</div>
                            </div>
                            <div style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--danger); font-weight: 700; cursor: pointer;">
                                    <input type="checkbox" name="reset_pkl" value="1" style="accent-color: var(--danger); width: 14px; height: 14px;">
                                    Kembalikan ke Banner Default (Bawaan)
                                </label>
                            </div>
                        @else
                            <div style="width: 100%; height: 120px; border-radius: 8px; border: 1px dashed var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg-alt); color: var(--text-muted); font-size: 0.82rem; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span>Menggunakan banner bawaan (Background warna gradasi hijau Klinik Komputer)</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Unggah Gambar Banner Baru</label>
                        <input type="file" name="pkl_banner" accept="image/jpeg,image/png,image/webp,image/jpg" class="form-control" style="padding: 8px; border-radius: 8px; border-style: dashed;">
                        <small class="form-text text-muted" style="display: block; margin-top: 6px; font-size: 0.76rem; color: var(--text-muted);">Format: JPG, JPEG, PNG, WebP. Maksimal ukuran file: 4MB. Resolusi disarankan: 1920x600 px untuk hasil terbaik.</small>
                    </div>

                </div>
            </div>

            <!-- Card 3: Banner Halaman Katalog Produk Axioo -->
            <div class="dash-card" style="margin-bottom: 24px; border: 1px solid var(--border-light); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div class="dash-card-header" style="background: var(--bg-card); padding: 20px 24px; border-bottom: 1px solid var(--border-light);">
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">3. Banner Halaman Katalog Produk Axioo</h3>
                </div>
                <div class="dash-card-body" style="padding: 24px; background: var(--bg-card);">
                    
                    @php
                        $prodBanner = '/images/products-hero.jpg';
                        $prodExists = file_exists(public_path($prodBanner));
                    @endphp

                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-weight: 700; display: block; margin-bottom: 8px; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Pratinjau Banner Saat Ini</label>
                        @if($prodExists)
                            <div style="position: relative; width: 100%; height: 160px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); background: #eee;">
                                <img src="{{ $prodBanner }}?v={{ filemtime(public_path($prodBanner)) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Banner Produk Axioo">
                                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.8rem; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">Custom Banner Aktif</div>
                            </div>
                            <div style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--danger); font-weight: 700; cursor: pointer;">
                                    <input type="checkbox" name="reset_products" value="1" style="accent-color: var(--danger); width: 14px; height: 14px;">
                                    Kembalikan ke Banner Default (Bawaan)
                                </label>
                            </div>
                        @else
                            <div style="width: 100%; height: 120px; border-radius: 8px; border: 1px dashed var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg-alt); color: var(--text-muted); font-size: 0.82rem; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span>Menggunakan banner bawaan (Background warna solid)</span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.82rem; color: var(--text-secondary); text-transform: uppercase;">Unggah Gambar Banner Baru</label>
                        <input type="file" name="products_banner" accept="image/jpeg,image/png,image/webp,image/jpg" class="form-control" style="padding: 8px; border-radius: 8px; border-style: dashed;">
                        <small class="form-text text-muted" style="display: block; margin-top: 6px; font-size: 0.76rem; color: var(--text-muted);">Format: JPG, JPEG, PNG, WebP. Maksimal ukuran file: 4MB. Resolusi disarankan: 1920x600 px untuk hasil terbaik.</small>
                    </div>

                </div>
            </div>

            <!-- Action Submit -->
            <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700; font-size: 0.85rem; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(95, 138, 99, 0.2);">Simpan Perubahan Banner</button>
                <a href="{{ route('dashboard.admin') }}" class="btn btn-outline" style="padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">Kembali</a>
            </div>

        </form>

    </div>

</div>
@endsection
