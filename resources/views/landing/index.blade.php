@extends('layouts.app')

@section('title', 'Klinik Komputer — Mitra Resmi Axioo Bandung')
@section('meta_description', 'Klinik Komputer hadir untuk kebutuhan IT Anda: penjualan laptop Axioo resmi, service komputer profesional, dan pengadaan laptop untuk sekolah.')

@section('content')

<!-- ── HERO CAROUSEL ─────────────────────────────────────────────── -->
<div class="hero-carousel" id="homeHero">
    <div class="carousel-track" id="carouselTrack">
        @if(isset($heroSlides) && $heroSlides->count() > 0)
            @foreach($heroSlides as $slide)
            <div class="carousel-slide">
                <img src="{{ $slide->image_url }}" alt="Hero Background" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    @if($slide->tag)
                        <div class="slide-tag">{{ $slide->tag }}</div>
                    @endif
                    @if($slide->title)
                        <h1>{{ $slide->title }}</h1>
                    @endif
                    @if($slide->description)
                        <p>{{ $slide->description }}</p>
                    @endif
                    <div class="slide-cta">
                        @if($slide->cta_text_primary)
                            <a href="{{ $slide->cta_url_primary }}" class="btn btn-primary btn-lg">{{ $slide->cta_text_primary }}</a>
                        @endif
                        @if($slide->cta_text_secondary)
                            <a href="{{ $slide->cta_url_secondary }}" class="btn btn-outline btn-lg">{{ $slide->cta_text_secondary }}</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- Default Slide 1 -->
            <div class="carousel-slide">
                <div class="slide-bg-fallback" style="background-color: #E2E7E2;"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="slide-tag">Mitra Resmi Axioo</div>
                    <h1>Solusi IT Terpercaya</h1>
                    <p>Klinik Komputer melayani penjualan resmi laptop Axioo, perbaikan komputer profesional, serta pengadaan perangkat teknologi untuk sekolah.</p>
                    <div class="slide-cta">
                        <a href="{{ route('service.booking') }}" class="btn btn-primary btn-lg">Booking Servis</a>
                        <a href="{{ route('products') }}" class="btn btn-outline btn-lg">Katalog Axioo</a>
                    </div>
                </div>
            </div>

            <!-- Default Slide 2 -->
            <div class="carousel-slide">
                <div class="slide-bg-fallback" style="background-color: #DFE3DF;"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="slide-tag">Layanan Reparasi</div>
                    <h1>Teknisi Profesional</h1>
                    <p>Perbaikan laptop dengan sistem antrian digital transparan. Pantau status unit Anda secara real-time langsung melalui website kami.</p>
                    <div class="slide-cta">
                        <a href="{{ route('service.booking') }}" class="btn btn-primary btn-lg">Mulai Booking</a>
                        <a href="#antrian-live" class="btn btn-outline btn-lg">Lihat Antrian</a>
                    </div>
                </div>
            </div>

            <!-- Default Slide 3 -->
            <div class="carousel-slide">
                <div class="slide-bg-fallback" style="background-color: #D6DDD6;"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <div class="slide-tag">Pengadaan Institusi</div>
                    <h1>Layanan E-Katalog</h1>
                    <p>Kami melayani pengadaan laptop berkualitas TKDN tinggi untuk institusi pendidikan, sekolah, dan perkantoran secara aman dan terpercaya.</p>
                    <div class="slide-cta">
                        <a href="https://wa.me/6285103051000" target="_blank" class="btn btn-primary btn-lg">Hubungi Sales</a>
                        <a href="#layanan" class="btn btn-outline btn-lg">Pelajari Layanan</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Controls -->
    <button class="carousel-btn prev" onclick="moveSlide(-1)">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
    </button>
    <button class="carousel-btn next" onclick="moveSlide(1)">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
    </button>

    <div class="carousel-dots" id="carouselDots">
        @if(isset($heroSlides) && $heroSlides->count() > 0)
            @foreach($heroSlides as $i => $slide)
            <button class="dot {{ $i === 0 ? 'active' : '' }}" onclick="setSlide({{ $i }})"></button>
            @endforeach
        @else
            <button class="dot active" onclick="setSlide(0)"></button>
            <button class="dot" onclick="setSlide(1)"></button>
            <button class="dot" onclick="setSlide(2)"></button>
        @endif
    </div>
</div>


<!-- ── STATS BAR ─────────────────────────────────────────────────── -->
<div class="stats-bar">
    <div class="stats-bar-item">
        <div class="num">500+</div>
        <div class="label">Case Service</div>
    </div>
    <div class="stats-bar-item">
        <div class="num">5+</div>
        <div class="label">Tahun Pengalaman</div>
    </div>
    <div class="stats-bar-item">
        <div class="num">98%</div>
        <div class="label">Tingkat Kepuasan</div>
    </div>
</div>

<!-- ── LIVE ANTRIAN TABLE ─────────────────────────────────────────── -->
<section class="section" id="antrian-live">
    <div class="section-inner">
        <div class="section-header center">
            <div class="label-line">Status Terkini</div>
            <h2>Antrian Servis Aktif</h2>
            <p>Daftar unit yang sedang dalam proses pengecekan dan perbaikan di workshop kami secara live.</p>
        </div>

        @if($activeQueue->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">No. Tiket</th>
                        <th style="width: 10%;">No. Antrian</th>
                        <th style="width: 25%;">Unit Perangkat</th>
                        <th style="width: 20%;">Teknisi PJ</th>
                        <th style="width: 15%;">Status Kerja</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeQueue as $ticket)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: var(--primary);">
                            {{ $ticket->ticket_number }}
                        </td>
                        <td style="font-weight: 600;">
                            #{{ $ticket->queue_number }}
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td>
                            {{ $ticket->pic_name ?? 'Alokasi Teknisi' }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span class="status-dot {{ $ticket->status }}"></span>
                                <span class="badge badge-{{ $ticket->status_color }}">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}" class="btn btn-outline btn-sm">Lacak Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 48px 24px; background: #ffffff; border: 1px solid var(--border-light); border-radius: 16px; box-shadow: 0 6px 24px rgba(0,0,0,0.03); max-width: 580px; margin: 10px auto 20px auto;">
            <div style="width: 72px; height: 72px; background: rgba(95, 138, 99, 0.08); border: 1px solid rgba(95, 138, 99, 0.18); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; color: var(--primary);">
                <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); margin: 0 0 10px 0; letter-spacing: -0.02em;">
                Belum Ada Antrian Aktif
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.9rem; max-width: 440px; margin: 0 auto 24px auto; line-height: 1.6;">
                Saat ini tidak ada unit komputer dalam antrian pengerjaan. Daftarkan servis Anda secara online untuk langsung mendapatkan nomor antrian digital.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('service.booking') }}" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; font-size: 0.8rem; border-radius: 8px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Booking Servis Baru
                </a>
                <a href="{{ route('service.track') }}" class="btn btn-outline" style="padding: 10px 20px; font-weight: 700; font-size: 0.8rem; border-radius: 8px;">
                    Cek Status Servis
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- ── ABOUT ─────────────────────────────────────────────────────── -->
<section class="section section-alt" id="tentang">
    <div class="section-inner">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;">
            <div>
                <div class="label-line">Profil Perusahaan</div>
                <h2 style="margin-bottom: 20px;">Klinik Komputer</h2>
                <p style="color: var(--text-secondary); margin-bottom: 16px;">
                    Mitra Abadi Computer System berdiri pada tahun 2001 di Kota Bandung dan merupakan suatu perusahaan yang bergerak pada bidang penjualan dan service notebook AXIOO dan lainya.
                </p>
                <p style="color: var(--text-secondary); margin-bottom: 24px;">
                    Pada tahun 2009 Mitra Abadi Computer System bertransformasi menjadi PT Mabito Karya (MK), sebagai perusahaan yang bergerak di bidang Pendidikan, jasa, retail IT, dan Klinik Komputer adalah bagian dari Mitra Abadi Computer pada bagian Retail IT.
                </p>
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="font-weight: 600; font-size: 0.82rem; text-transform: uppercase; color: var(--primary);">
                        Garansi Resmi Axioo
                    </div>
                    <div style="font-weight: 600; font-size: 0.82rem; text-transform: uppercase; color: var(--primary);">
                        Teknisi Profesional
                    </div>
                    <div style="font-weight: 600; font-size: 0.82rem; text-transform: uppercase; color: var(--primary);">
                        <!-- Komponen Orisinal -->
                    </div>
                </div>
            </div>
            <div>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="card" style="padding: 24px;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); margin-bottom: 8px;">Garansi Penuh</h4>
                        <p style="font-size: 0.8rem; color: var(--text-secondary);">Perlindungan resmi untuk unit laptop baru hingga 3 tahun.</p>
                    </div>
                    <div class="card" style="padding: 24px;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); margin-bottom: 8px;">Servis Terintegrasi</h4>
                        <p style="font-size: 0.8rem; color: var(--text-secondary);">Sistem pelacakan status unit secara online dari awal hingga akhir.</p>
                    </div>
                    <div class="card" style="padding: 24px;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); margin-bottom: 8px;">Suku Cadang Ready</h4>
                        <p style="font-size: 0.8rem; color: var(--text-secondary);">Ketersediaan suku cadang laptop yang cepat untuk berbagai tipe.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SERVICES ──────────────────────────────────────────────────── -->
<section class="section" id="layanan">
    <div class="section-inner">
        <div class="section-header center">
            <div class="label-line">Layanan Kami</div>
            <h2>Layanan Solusi Teknologi</h2>
            <p>Tiga solusi utama untuk menjawab berbagai kebutuhan operasional dan personal IT Anda.</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon-wrap">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <h3>Pengadaan Unit</h3>
                <p>Penyediaan unit laptop sekolah melalui LKPP e-katalog resmi. Produk berspesifikasi TKDN tinggi untuk menunjang ujian sekolah dan laboratorium kejuruan.</p>
                <div class="service-tags">
                    <span class="badge badge-primary">E-Katalog</span>
                    <span class="badge badge-primary">TKDN</span>
                </div>
            </div>
            <div class="service-card">
                <div class="service-icon-wrap">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <h3>Reparasi Laptop</h3>
                <p>Servis kerusakan laptop oleh tim teknisi berpengalaman. Penanganan transparan dari pengecekan awal hingga penggantian komponen dengan estimasi biaya presisi.</p>
                <div class="service-tags">
                    <span class="badge badge-primary">Lacak Online</span>
                    <span class="badge badge-primary">Garansi Servis</span>
                </div>
            </div>
            <div class="service-card">
                <div class="service-icon-wrap">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                </div>
                <h3>Dealer Resmi Axioo</h3>
                <p>Dapatkan lini produk laptop Axioo orisinal bergaransi resmi. Kami menyediakan seri Hype untuk produktivitas kerja harian dan seri gaming Pongo berkemampuan tinggi.</p>
                <div class="service-tags">
                    <span class="badge badge-primary">Orisinal</span>
                    <span class="badge badge-primary">Garansi Axioo</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURED PRODUCTS ─────────────────────────────────────────── -->
@if($featuredProducts->count() > 0)
<section class="section section-alt">
    <div class="section-inner">
        <div class="section-header center">
            <div class="label-line">Produk Pilihan</div>
            <h2>Laptop Axioo Unggulan</h2>
            <p>Lini produk laptop Axioo berkinerja tinggi yang paling diminati oleh pelanggan kami.</p>
        </div>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-img">
                    @if(count($product->images) > 0)
                        <img src="{{ asset('storage/'.$product->images[0]) }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-image">
                            <svg viewBox="0 0 24 24" width="36" height="36" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            <span>{{ $product->series_label }}</span>
                        </div>
                    @endif
                    <span class="series-badge badge {{ $product->series === 'pongo' ? 'badge-accent' : 'badge-primary' }}">
                        {{ $product->series_label }}
                    </span>
                </div>
                <div class="product-body">
                    <h3>{{ $product->name }}</h3>
                    <ul class="product-specs">
                        <li>
                            <span class="spec-dot"></span>
                            <span class="spec-key">CPU</span>
                            <span>{{ $product->processor }}</span>
                        </li>
                        <li>
                            <span class="spec-dot"></span>
                            <span class="spec-key">RAM</span>
                            <span>{{ $product->ram }}</span>
                        </li>
                        <li>
                            <span class="spec-dot"></span>
                            <span class="spec-key">SSD</span>
                            <span>{{ $product->storage }}</span>
                        </li>
                        <li>
                            <span class="spec-dot"></span>
                            <span class="spec-key">Screen</span>
                            <span>{{ $product->display }}</span>
                        </li>
                    </ul>
                    <div class="product-price">
                        {{ $product->formatted_price }}
                        <small>Harga sewaktu-waktu dapat berubah</small>
                    </div>
                    <a href="https://wa.me/6285103051000?text=Halo, saya ingin bertanya mengenai laptop {{ urlencode($product->name) }}"
                       target="_blank" class="btn btn-primary btn-block">Tanya Penawaran</a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('products') }}" class="btn btn-outline btn-lg">Lihat Semua Produk</a>
        </div>
    </div>
</section>
@endif

<!-- ── HOW IT WORKS ──────────────────────────────────────────────── -->
<section class="section">
    <div class="section-inner">
        <div class="section-header center">
            <div class="label-line">Alur Kerja</div>
            <h2>Prosedur Layanan Servis</h2>
            <p>Prosedur digital kami memudahkan pendaftaran unit dan monitoring kemajuan servis secara akurat.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">
            @php
            $steps = [
                ['title'=>'Pendaftaran Online','desc'=>'Tulis deskripsi unit dan keluhan perangkat melalui formulir booking online.'],
                ['title'=>'Nomor Antrian','desc'=>'Dapatkan nomor antrian digital unik untuk mempercepat proses penyerahan unit.'],
                ['title'=>'Penyerahan Unit','desc'=>'Bawa unit ke workshop kami dan tunjukkan nomor antrian kepada petugas.'],
                ['title'=>'Pemantauan Real-Time','desc'=>'Pantau status pengerjaan perangkat Anda secara online langsung dari web.'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="service-card" style="text-align: center;">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">Langkah {{ $i+1 }}</div>
                <h3 style="margin-bottom: 8px;">{{ $step['title'] }}</h3>
                <p>{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── CONTACT ────────────────────────────────────────────────────── -->
<section class="section section-alt" id="kontak">
    <div class="section-inner">
        <div class="section-header center">
            <div class="label-line">Lokasi</div>
            <h2>Hubungi Kami</h2>
            <p>Kunjungi kantor operasional kami di Bandung atau hubungi kami langsung.</p>
        </div>
        <div class="contact-grid">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div class="contact-item">
                    <div class="contact-icon-wrap">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Alamat</div>
                        <div style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5;">
                            Komplek Ruko Segitiga Emas Kosambi, Jl. A. Yani Blok E8, Merdeka, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40113
                        </div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon-wrap">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">WhatsApp</div>
                        <a href="https://wa.me/6285103051000" target="_blank" style="color: var(--primary); font-size: 0.95rem; font-weight: 700;">085103051000</a>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon-wrap">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Jam Kerja</div>
                        <div style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.4;">
                            Senin – Jumat: 09.00 – 17.00 WIB<br>
                            Sabtu: 09.00 – 14.00 WIB
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px;">Minggu: Tutup</div>
                    </div>
                </div>
                <a href="{{ route('service.booking') }}" class="btn btn-primary btn-lg" style="margin-top: 8px;">Booking Servis Sekarang</a>
            </div>
            <div>
                <div style="border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border); height: 350px; background: #EAEAEA;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.7986940661!2d107.6143!3d-6.9175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTUnMDMuMCJTIDEwN8KwMzYnNTEuNSJF!5e0!3m2!1sid!2sid!4v1234567890"
                        width="100%" height="100%"
                        style="border:0;"
                        allowfullscreen loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    const track = document.getElementById('carouselTrack');

    function updateCarousel() {
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }

    function moveSlide(dir) {
        currentSlide = (currentSlide + dir + slides.length) % slides.length;
        updateCarousel();
    }

    function setSlide(index) {
        currentSlide = index;
        updateCarousel();
    }

    // Auto play carousel
    setInterval(() => {
        moveSlide(1);
    }, 5000);
</script>
@endpush
