@extends('layouts.app')
@section('title', 'Katalog Laptop Axioo | Klinik Komputer')
@section('meta_description', 'Temukan laptop Axioo original dengan garansi resmi di Klinik Komputer Bandung. Seri Hype untuk produktivitas dan Pongo untuk gaming.')

@section('content')
<div style="padding-top: 64px;">

<!-- Page Header / Hero Banner -->
@php
    $prodBanner = '/images/products-hero.jpg';
    $hasProdBanner = file_exists(public_path($prodBanner));
@endphp

<div class="section" style="padding: 70px 0; position: relative; overflow: hidden; background: {{ $hasProdBanner ? 'url('.$prodBanner.'?v='.filemtime(public_path($prodBanner)).') center/cover no-repeat' : 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)' }}; border-bottom: 1px solid var(--border-light);">
    @if($hasProdBanner)
        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(15, 23, 42, 0.72) 100%);"></div>
    @endif
    <div class="section-inner" style="position: relative; z-index: 2;">
        <div class="section-header" style="margin-bottom: 0; text-align: left; max-width: 720px;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.3); padding: 6px 14px; border-radius: 99px; font-size: 0.76rem; font-weight: 700; color: #4ade80; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 16px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Authorized Axioo Partner
            </div>
            <h1 style="color: #ffffff; font-size: 2.4rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 12px;">Katalog Laptop Axioo</h1>
            <p style="color: rgba(255, 255, 255, 0.82); font-size: 1.05rem; line-height: 1.6; margin-bottom: 24px;">Produk orisinal bergaransi resmi Axioo Indonesia. Pilih laptop ideal untuk produktivitas harian, pendidikan, maupun gaming high-performance.</p>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.82rem; color: rgba(255,255,255,0.9); font-weight: 600;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #4ade80;">✓</span> 100% Garansi Resmi
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #4ade80;">✓</span> Service Center Certified
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="color: #4ade80;">✓</span> Ready Stock & Fast Delivery
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Catalog Content -->
<section class="section" style="background: var(--bg-body); padding: 48px 0 80px 0;">
    <div class="section-inner">
        
        <!-- Filter & Search Controls -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; background: #ffffff; padding: 16px 20px; border-radius: 16px; border: 1px solid var(--border-light); box-shadow: 0 2px 12px rgba(0,0,0,0.02);">
            
            <!-- Category Tabs -->
            <div class="filter-bar" style="margin-bottom: 0; padding: 0; background: none; border: none;">
                <a href="{{ route('products') }}" class="filter-btn {{ !request('series') ? 'active' : '' }}" style="border-radius: 99px; font-weight: 700; padding: 8px 18px;">
                    Semua Seri
                </a>
                <a href="{{ route('products', ['series'=>'hype']) }}" class="filter-btn {{ request('series')==='hype' ? 'active' : '' }}" style="border-radius: 99px; font-weight: 700; padding: 8px 18px;">
                    Axioo Hype
                </a>
                <a href="{{ route('products', ['series'=>'pongo']) }}" class="filter-btn {{ request('series')==='pongo' ? 'active' : '' }}" style="border-radius: 99px; font-weight: 700; padding: 8px 18px;">
                    Axioo Pongo
                </a>
            </div>

            <!-- Live Search Bar -->
            <div style="position: relative; width: 100%; max-width: 320px;">
                <input type="text" id="catalogSearchInput" placeholder="Cari tipe laptop, CPU, RAM..." style="width: 100%; padding: 10px 16px 10px 40px; border-radius: 99px; border: 1px solid var(--border); font-size: 0.85rem; background: var(--bg-alt); outline: none; transition: all 0.2s ease;">
                <svg viewBox="0 0 24 24" width="17" height="17" stroke="var(--text-muted)" stroke-width="2" fill="none" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>

        @if($products->count() > 0)
        <!-- Grid Catalog -->
        <div class="products-grid">
            @foreach($products as $product)
            @php
                $pImages = $product->images;
                $firstImg = count($pImages) > 0 ? asset('storage/'.$pImages[0]) : null;
            @endphp
            
            <div class="product-card" 
                 data-product="{{ json_encode($product) }}" 
                 data-formatted-price="{{ $product->formatted_price }}" 
                 data-has-discount="{{ $product->has_discount ? '1' : '0' }}"
                 data-formatted-discount="{{ $product->formatted_discount_price }}"
                 data-images="{{ json_encode(array_map(fn($img) => asset('storage/'.$img), $pImages)) }}" 
                 data-series-label="{{ $product->series_label }}"
                 style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); cursor: pointer; box-shadow: 0 4px 18px rgba(0,0,0,0.03);">
                
                <!-- Showcase Image Header -->
                <div style="position: relative; width: 100%; height: 230px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid var(--border-light);">
                    @if($firstImg)
                        <img src="{{ $firstImg }}" alt="{{ $product->name }}" style="max-width: 88%; max-height: 85%; width: auto; height: auto; object-fit: contain; transition: transform 0.35s ease;">
                    @else
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); gap: 6px;">
                            <svg viewBox="0 0 24 24" width="38" height="38" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            <span style="font-size: 0.75rem; font-weight: 600;">Belum Ada Foto</span>
                        </div>
                    @endif
                    
                    <!-- Overlays -->
                    <div style="position: absolute; top: 12px; left: 12px; right: 12px; display: flex; justify-content: space-between; align-items: center; pointer-events: none;">
                        <span class="badge {{ $product->series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; padding: 5px 12px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            {{ $product->series_label }}
                        </span>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            @if($product->has_discount)
                                <span style="font-size: 0.68rem; font-weight: 800; color: #ffffff; background: #dc2626; padding: 4px 10px; border-radius: 99px; box-shadow: 0 2px 6px rgba(220,38,38,0.3);">DISKON {{ $product->discount_percentage }}%</span>
                            @endif
                            @if($product->tokopedia_url)
                                <span style="font-size: 0.68rem; font-weight: 700; color: #16a34a; background: rgba(255,255,255,0.95); border: 1px solid rgba(22,163,74,0.3); padding: 4px 10px; border-radius: 99px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">✓ Online Shop</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Body Details -->
                <div style="padding: 20px; display: flex; flex-direction: column; flex: 1; gap: 12px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0; line-height: 1.3; letter-spacing: -0.3px;">{{ $product->name }}</h3>
                    
                    <!-- Spec Grid Chips -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.76rem; background: var(--bg-alt); padding: 10px 12px; border-radius: 10px; border: 1px solid var(--border-light);">
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary); overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                            <span style="color: var(--primary); font-weight: 700; font-size: 0.68rem; text-transform: uppercase;">CPU:</span>
                            <span style="font-weight: 600; text-overflow: ellipsis; overflow: hidden;" title="{{ $product->processor }}">{{ $product->processor }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary);">
                            <span style="color: var(--primary); font-weight: 700; font-size: 0.68rem; text-transform: uppercase;">RAM:</span>
                            <span style="font-weight: 600;">{{ $product->ram }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary);">
                            <span style="color: var(--primary); font-weight: 700; font-size: 0.68rem; text-transform: uppercase;">SSD:</span>
                            <span style="font-weight: 600;">{{ $product->storage }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary); overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                            <span style="color: var(--primary); font-weight: 700; font-size: 0.68rem; text-transform: uppercase;">Layar:</span>
                            <span style="font-weight: 600; text-overflow: ellipsis; overflow: hidden;" title="{{ $product->display }}">{{ $product->display }}</span>
                        </div>
                    </div>

                    @if($product->features)
                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                        @foreach(array_slice($product->features, 0, 3) as $f)
                        <span class="badge badge-secondary" style="font-size: 0.65rem; font-weight: 600; padding: 2px 8px;">{{ $f }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div style="margin-top: auto; padding-top: 10px;">
                        @if($product->has_discount)
                            <div style="font-size: 0.82rem; color: var(--text-muted); text-decoration: line-through; font-weight: 500; margin-bottom: 1px;">
                                {{ $product->formatted_price }}
                            </div>
                            <div style="font-size: 1.3rem; font-weight: 800; color: #dc2626; letter-spacing: -0.4px;">
                                {{ $product->formatted_discount_price }}
                            </div>
                        @else
                            <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary); letter-spacing: -0.4px;">
                                {{ $product->formatted_price }}
                            </div>
                        @endif
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">Garansi Resmi &amp; Ready Stock</div>
                    </div>

                    <!-- Buttons Row -->
                    <div style="margin-top: 6px;">
                        <button type="button" class="btn btn-primary btn-sm trigger-modal-btn" style="width: 100%; font-weight: 700; font-size: 0.8rem; border-radius: 8px; padding: 10px; justify-content: center; text-align: center;">Lihat Detail</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Hidden search empty state -->
        <div id="searchEmptyState" style="display: none; text-align: center; padding: 60px 24px; background: #ffffff; border: 1px solid var(--border-light); border-radius: 16px; max-width: 500px; margin: 30px auto;">
            <div style="margin-bottom: 12px; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="1.5" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-primary); margin-bottom: 8px;">Laptop Tidak Ditemukan</h3>
            <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 0;">Tidak ada produk yang cocok dengan pencarian Anda. Coba gunakan kata kunci lain.</p>
        </div>

        @else
        <div style="text-align: center; padding: 60px 24px; background: #ffffff; border: 1px solid var(--border-light); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); max-width: 600px; margin: 20px auto 40px auto;">
            <div style="width: 72px; height: 72px; background: rgba(22, 163, 74, 0.06); border: 1px solid rgba(22, 163, 74, 0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; color: var(--primary);">
                <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); margin: 0 0 10px 0; letter-spacing: -0.3px;">
                {{ request('series') ? 'Produk Seri '.strtoupper(request('series')).' Belum Tersedia' : 'Belum Ada Produk Dalam Katalog' }}
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem; max-width: 440px; margin: 0 auto 24px auto; line-height: 1.6;">
                {{ request('series') ? 'Produk laptop Axioo seri '.strtoupper(request('series')).' saat ini belum tersedia dalam katalog. Cobalah memilih kategori seri lain atau tanyakan ketersediaan ke Customer Service.' : 'Katalog laptop Axioo saat ini belum diinput. Hubungi tim Customer Service Klinik Komputer untuk informasi ketersediaan unit terbaru.' }}
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                @if(request('series'))
                    <a href="{{ route('products') }}" class="btn btn-outline" style="padding: 10px 20px; font-weight: 600;">Lihat Semua Produk</a>
                @endif
                <a href="https://wa.me/6285103051000?text={{ urlencode('Halo Klinik Komputer, saya ingin menanyakan ketersediaan stok laptop Axioo') }}" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 10px 22px;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
        @endif

        <!-- Value Proposition Feature Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 56px; padding-top: 36px; border-top: 1px solid var(--border-light);">
            <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(22,163,74,0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Garansi Resmi 100%</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">Perlindungan official Axioo Indonesia</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(22,163,74,0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Service Center Resmi</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">Dukungan teknisi sertifikasi resmi</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(22,163,74,0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Toko Resmi Tokopedia</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">Transaksi aman & pilihan cicilan</div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: 14px; padding: 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(22,163,74,0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Pengiriman Aman</div>
                    <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">Packing aman dengan proteksi asuransi</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- CTA Banner -->
<section class="section section-alt" style="padding: 60px 0;">
    <div class="section-inner">
        <div class="card" style="text-align: center; max-width: 680px; margin: 0 auto; padding: 40px 32px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.03);">
            <h2 style="margin-bottom: 12px; font-size: 1.6rem; font-weight: 800;">Butuh Penawaran Khusus Sekolah atau Perusahaan?</h2>
            <p style="color: var(--text-secondary); margin-bottom: 24px; font-size: 0.92rem; line-height: 1.6;">
                Hubungi sales kami untuk kebutuhan pengadaan laptop Axioo skala besar, unit laboratorium sekolah, instansi pemerintah, atau bundling paket garansi & servis khusus.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('procurement.index') }}" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700;">Tanya Pengadaan Unit</a>
                <a href="{{ route('service.booking') }}" class="btn btn-outline" style="padding: 12px 24px; font-weight: 600;">Booking Servis Perangkat</a>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Produk Dynamic Carousel -->
<div id="productModal" class="product-modal-overlay" style="display: none;">
    <div class="product-modal-card">
        
        <!-- Close Button -->
        <button id="closeModalBtn" type="button" class="product-modal-close" aria-label="Tutup">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <!-- Modal Left: Product Image Carousel -->
        <div class="product-modal-left">
            
            <!-- Main Showcase Image Frame -->
            <div class="product-modal-img-wrap">
                <img id="modalProductImg" src="" alt="Detail Produk">
                
                <!-- Fallback SVG Placeholder -->
                <div id="modalFallbackPlaceholder" class="product-modal-fallback">
                    <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <span>Belum Ada Foto Produk</span>
                </div>

                <!-- Navigation Controls -->
                <button id="modalPrevSlide" type="button" class="product-modal-nav prev">‹</button>
                <button id="modalNextSlide" type="button" class="product-modal-nav next">›</button>
                
                <!-- Slide Counter Badge -->
                <div id="modalSlideCounter" class="product-modal-counter">1 / 1</div>
            </div>

            <!-- Thumbnail Selector Strip -->
            <div id="modalThumbsContainer" class="product-modal-thumbs">
                {{-- Thumbnails generated dynamically by JS --}}
            </div>
        </div>

        <!-- Modal Right: Full Specifications -->
        <div class="product-modal-right">
            <div style="margin-bottom: 12px;">
                <span id="modalProductBadge" class="badge badge-primary" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 6px;">Axioo Hype</span>
            </div>

            <h2 id="modalProductTitle" class="product-modal-title">Laptop Axioo</h2>
            
            <div id="modalProductPrice" class="product-modal-price">
                Rp 0
            </div>

            <p id="modalProductDesc" class="product-modal-desc"></p>

            <div class="product-modal-specs-header">Spesifikasi Lengkap Unit</div>
            
            <!-- Dynamic Specs List -->
            <div id="modalProductSpecs" class="product-modal-specs">
                {{-- Specs populated dynamically by JS --}}
            </div>

            <div class="product-modal-actions">
                <a id="modalWaBtn" href="#" target="_blank" class="btn btn-primary product-modal-btn">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Tanya Stok via WA
                </a>
                
                <a id="modalTokopediaBtn" href="#" target="_blank" class="btn btn-online-shop product-modal-btn tokped" style="display: none;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    Online Shop
                </a>
            </div>
        </div>

    </div>
</div>

</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.96) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.products-grid .product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
}
.products-grid .product-card:hover img {
    transform: scale(1.05) !important;
}

/* Product Modal Styling */
.product-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    transition: opacity 0.25s ease;
}

.product-modal-card {
    background: var(--bg-card);
    width: 100%;
    max-width: 880px;
    border-radius: 20px;
    border: 1px solid var(--border);
    overflow: hidden;
    display: flex;
    flex-direction: row;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
    position: relative;
    max-height: 90vh;
    animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.product-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    z-index: 20;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--bg-alt);
    border: 1px solid var(--border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    transition: all 0.2s ease;
}
.product-modal-close:hover {
    background: #ffffff;
    transform: scale(1.05);
}

.product-modal-left {
    width: 48%;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 24px;
    border-right: 1px solid var(--border-light);
    position: relative;
    flex-shrink: 0;
}

.product-modal-img-wrap {
    width: 100%;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.product-modal-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: opacity 0.2s ease;
}

.product-modal-fallback {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    gap: 8px;
}

.product-modal-nav {
    display: none;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: 1px solid var(--border);
    color: var(--text-primary);
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 5;
}
.product-modal-nav.prev { left: 0; }
.product-modal-nav.next { right: 0; }

.product-modal-counter {
    display: none;
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(15,23,42,0.75);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 99px;
}

.product-modal-thumbs {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    overflow-x: auto;
    width: 100%;
    max-width: 100%;
    padding-bottom: 4px;
}

.product-modal-right {
    width: 52%;
    padding: 32px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.product-modal-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 6px;
    line-height: 1.25;
}

.product-modal-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 16px;
}

.product-modal-desc {
    font-size: 0.85rem;
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 20px;
    display: none;
    background: var(--bg-alt);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--border-light);
}

.product-modal-specs-header {
    font-weight: 700;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    margin-bottom: 10px;
}

.product-modal-specs {
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 0.85rem;
    margin-bottom: 24px;
}

.product-modal-actions {
    margin-top: auto;
    display: flex;
    flex-direction: row;
    gap: 10px;
    align-items: center;
}

.product-modal-btn {
    flex: 1;
    text-align: center;
    justify-content: center;
    font-weight: 700;
    padding: 12px;
    border-radius: 10px;
    white-space: nowrap;
    font-size: 0.84rem;
}

/* Mobile Devices Fix */
@media (max-width: 768px) {
    .product-modal-overlay {
        padding: 12px !important;
    }
    .product-modal-card {
        flex-direction: column !important;
        max-height: 90vh !important;
        overflow-y: auto !important;
        border-radius: 16px !important;
    }
    .product-modal-left {
        width: 100% !important;
        border-right: none !important;
        border-bottom: 1px solid var(--border-light) !important;
        padding: 20px 16px 14px !important;
    }
    .product-modal-img-wrap {
        height: 210px !important;
    }
    .product-modal-thumbs {
        flex-direction: row !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        max-height: none !important;
        overflow-x: auto !important;
        margin-top: 10px !important;
    }
    .product-modal-right {
        width: 100% !important;
        padding: 20px 18px 24px !important;
        overflow-y: visible !important;
    }
    .product-modal-title {
        font-size: 1.2rem !important;
    }
    .product-modal-price {
        font-size: 1.3rem !important;
        margin-bottom: 12px !important;
    }
    .product-modal-actions {
        flex-direction: column !important;
        gap: 8px !important;
        margin-top: 16px !important;
    }
    .product-modal-btn {
        width: 100% !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('productModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cards = document.querySelectorAll('.product-card');
    const prevBtn = document.getElementById('modalPrevSlide');
    const nextBtn = document.getElementById('modalNextSlide');
    const counterEl = document.getElementById('modalSlideCounter');
    const thumbsContainer = document.getElementById('modalThumbsContainer');

    let currentImages = [];
    let currentSlideIndex = 0;

    function showSlide(index) {
        if (!currentImages || currentImages.length === 0) return;
        if (index < 0) index = currentImages.length - 1;
        if (index >= currentImages.length) index = 0;

        currentSlideIndex = index;
        const imgEl = document.getElementById('modalProductImg');
        imgEl.style.opacity = '0.3';
        setTimeout(() => {
            imgEl.src = currentImages[currentSlideIndex];
            imgEl.style.opacity = '1';
        }, 80);

        counterEl.innerText = `${currentSlideIndex + 1} / ${currentImages.length}`;

        const thumbs = thumbsContainer.querySelectorAll('.modal-thumb-btn');
        thumbs.forEach((t, i) => {
            if (i === currentSlideIndex) {
                t.style.borderColor = '#16a34a';
                t.style.opacity = '1';
                t.style.boxShadow = '0 0 0 2px rgba(22,163,74,0.25)';
            } else {
                t.style.borderColor = 'var(--border)';
                t.style.opacity = '0.6';
                t.style.boxShadow = 'none';
            }
        });
    }

    if (prevBtn) prevBtn.onclick = () => showSlide(currentSlideIndex - 1);
    if (nextBtn) nextBtn.onclick = () => showSlide(currentSlideIndex + 1);

    // Live Search Handler
    const searchInput = document.getElementById('catalogSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const allCards = document.querySelectorAll('.products-grid .product-card');
            let countVisible = 0;

            allCards.forEach(card => {
                const product = JSON.parse(card.getAttribute('data-product'));
                const searchStr = `${product.name} ${product.processor} ${product.ram} ${product.storage} ${product.gpu || ''} ${product.display || ''}`.toLowerCase();
                
                if (searchStr.includes(query)) {
                    card.style.display = 'flex';
                    countVisible++;
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyEl = document.getElementById('searchEmptyState');
            if (emptyEl) {
                emptyEl.style.display = countVisible === 0 ? 'block' : 'none';
            }
        });
    }

    // Modal Trigger Handler
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Allow direct links to WA and Online Shop without blocking
            if (e.target.closest('a[target="_blank"]')) {
                return;
            }
            
            const product = JSON.parse(this.getAttribute('data-product'));
            const formattedPrice = this.getAttribute('data-formatted-price');
            const hasDiscount = this.getAttribute('data-has-discount') === '1';
            const formattedDiscount = this.getAttribute('data-formatted-discount');
            currentImages = JSON.parse(this.getAttribute('data-images')) || [];
            const seriesLabel = this.getAttribute('data-series-label');

            document.getElementById('modalProductTitle').innerText = product.name;
            
            const priceEl = document.getElementById('modalProductPrice');
            if (hasDiscount && formattedDiscount) {
                priceEl.innerHTML = `
                    <div style="font-size: 0.95rem; color: var(--text-muted); text-decoration: line-through; font-weight: 500;">${formattedPrice}</div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #dc2626;">${formattedDiscount}</div>
                `;
            } else {
                priceEl.innerHTML = `<div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">${formattedPrice}</div>`;
            }
            
            const descElement = document.getElementById('modalProductDesc');
            if (product.description) {
                descElement.innerText = product.description;
                descElement.style.display = 'block';
            } else {
                descElement.style.display = 'none';
            }

            // Setup Carousel Images
            const imgElement = document.getElementById('modalProductImg');
            const fallbackEl = document.getElementById('modalFallbackPlaceholder');
            thumbsContainer.innerHTML = '';
            currentSlideIndex = 0;

            if (currentImages.length > 0) {
                imgElement.src = currentImages[0];
                imgElement.style.display = 'block';
                fallbackEl.style.display = 'none';

                if (currentImages.length > 1) {
                    if (prevBtn) prevBtn.style.display = 'flex';
                    if (nextBtn) nextBtn.style.display = 'flex';
                    counterEl.style.display = 'block';
                    counterEl.innerText = `1 / ${currentImages.length}`;

                    currentImages.forEach((imgUrl, idx) => {
                        const tBtn = document.createElement('button');
                        tBtn.type = 'button';
                        tBtn.className = 'modal-thumb-btn';
                        tBtn.style.width = '52px';
                        tBtn.style.height = '52px';
                        tBtn.style.borderRadius = '10px';
                        tBtn.style.border = idx === 0 ? '2px solid #16a34a' : '1px solid var(--border)';
                        tBtn.style.padding = '3px';
                        tBtn.style.background = '#ffffff';
                        tBtn.style.cursor = 'pointer';
                        tBtn.style.opacity = idx === 0 ? '1' : '0.6';
                        tBtn.style.transition = 'all 0.2s';
                        tBtn.style.flexShrink = '0';
                        if (idx === 0) tBtn.style.boxShadow = '0 0 0 2px rgba(22,163,74,0.25)';

                        const tImg = document.createElement('img');
                        tImg.src = imgUrl;
                        tImg.style.width = '100%';
                        tImg.style.height = '100%';
                        tImg.style.objectFit = 'contain';
                        tImg.style.borderRadius = '6px';

                        tBtn.appendChild(tImg);
                        tBtn.addEventListener('click', () => showSlide(idx));
                        thumbsContainer.appendChild(tBtn);
                    });
                } else {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    counterEl.style.display = 'none';
                }
            } else {
                imgElement.style.display = 'none';
                fallbackEl.style.display = 'flex';
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                counterEl.style.display = 'none';
            }

            // Series Badge
            const badgeElement = document.getElementById('modalProductBadge');
            badgeElement.innerText = seriesLabel;
            if (product.series === 'pongo') {
                badgeElement.className = 'badge badge-accent';
            } else {
                badgeElement.className = 'badge badge-primary';
            }

            // Populate Specs List
            const specsContainer = document.getElementById('modalProductSpecs');
            const specsList = [
                { label: 'Processor', value: product.processor, icon: 'CPU' },
                { label: 'Memori RAM', value: product.ram, icon: 'RAM' },
                { label: 'Penyimpanan SSD', value: product.storage, icon: 'SSD' },
                { label: 'Kartu Grafis (GPU)', value: product.gpu, icon: 'GPU' },
                { label: 'Layar & Tampilan', value: product.display, icon: 'DISPLAY' },
                { label: 'Kapasitas Baterai', value: product.battery, icon: 'BATTERY' },
                { label: 'Bobot Perangkat', value: product.weight, icon: 'WEIGHT' },
            ];

            let html = '';
            specsList.forEach(spec => {
                if (spec.value) {
                    html += `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: var(--bg-alt); border-radius: 8px; border: 1px solid var(--border-light);">
                            <span style="color: var(--text-secondary); display: flex; align-items: center; gap: 8px; font-weight: 600;">
                                <span>${spec.icon}</span> ${spec.label}
                            </span>
                            <span style="font-weight: 700; color: var(--text-primary); text-align: right;">${spec.value}</span>
                        </div>
                    `;
                }
            });
            specsContainer.innerHTML = html;

            // WA Link
            const waText = `Halo Klinik Komputer, saya berminat dan ingin bertanya lebih lanjut mengenai laptop Axioo: ${product.name} (Harga: ${formattedPrice})`;
            document.getElementById('modalWaBtn').href = `https://wa.me/6285103051000?text=${encodeURIComponent(waText)}`;

            // Tokopedia / Online Shop Link
            const tokoBtn = document.getElementById('modalTokopediaBtn');
            if (product.tokopedia_url) {
                tokoBtn.href = product.tokopedia_url;
                tokoBtn.style.display = 'flex';
            } else {
                tokoBtn.style.display = 'none';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModal();
        }
    });
});
</script>
@endsection
