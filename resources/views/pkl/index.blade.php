@extends('layouts.app')
@section('title', 'Internship | Klinik Komputer')

@section('content')
@php
    $pklHeroFile = '/images/pkl-hero.jpg';
    $pklHeroUrl = file_exists(public_path($pklHeroFile)) 
        ? $pklHeroFile . '?v=' . filemtime(public_path($pklHeroFile)) 
        : null;
@endphp

<!-- 1. Hero Section Banner -->
<div style="background: {{ $pklHeroUrl ? 'linear-gradient(rgba(13, 30, 17, 0.7), rgba(13, 30, 17, 0.88)), url(\'' . $pklHeroUrl . '\') no-repeat center center' : 'linear-gradient(135deg, var(--primary) 0%, #1e3a22 100%)' }}; background-size: cover; color: #fff; padding: 75px 20px 65px; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; opacity: 0.06; background-image: radial-gradient(circle, #fff 2px, transparent 2px); background-size: 30px 30px;"></div>
    <div style="max-width: 820px; margin: 0 auto; position: relative; z-index: 2; padding-top: 40px;">
        <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #a3e635; letter-spacing: 0.15em; background: rgba(163,230,53,0.14); padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(163,230,53,0.3);">
            Axioo Class Program (ACP) Partner
        </span>
        <h1 style="color: #fff; font-size: clamp(2rem, 4vw, 2.75rem); font-weight: 800; margin-top: 14px; letter-spacing: -0.5px; line-height: 1.2;">
            Program Praktik Kerja Lapangan
        </h1>
        <p style="color: rgba(255,255,255,0.92); font-size: 1.05rem; margin-top: 14px; line-height: 1.6; max-width: 720px; margin-left: auto; margin-right: auto; font-weight: 500;">
            Bangun karier cemerlang di industri teknologi. Kembangkan potensi, asah keterampilan praktis, dan rasakan pengalaman kerja nyata bersama tim ahli Klinik Komputer melalui program kemitraan resmi Axioo Class Program (ACP).
        </p>
        <div style="margin-top: 26px; display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="#form-internship" class="btn" style="background: #a3e635; color: #142c16; border-color: #a3e635; font-weight: 700; border-radius: 6px; padding: 11px 24px; box-shadow: 0 4px 14px rgba(163, 230, 53, 0.3); transition: all 0.2s;">
                Form Pengajuan Sekolah
            </a>
            <a href="{{ route('pkl.create') }}" class="btn" style="background: rgba(255, 255, 255, 0.14); color: #ffffff; border: 1.5px solid rgba(255, 255, 255, 0.45); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); font-weight: 700; border-radius: 6px; padding: 11px 24px; transition: all 0.2s; box-shadow: 0 4px 15px rgba(0,0,0,0.12);">
                Form PKL
            </a>
        </div>
    </div>
</div>

<!-- 2. Form Section: Pengajuan Internship (Khusus Sekolah ACP) -->
<div id="form-internship" style="background: var(--bg-card); padding: 60px 20px; border-bottom: 1px solid var(--border-light);">
    <div style="max-width: 760px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 32px;">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.1em;">Pendaftaran Sekolah</span>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 6px;">Pengajuan Internship (Khusus Sekolah ACP)</h2>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">
                Silakan pilih sekolah asal Anda yang telah terdaftar sebagai mitra Axioo Class Program.
            </p>
        </div>

        @if(session('success_application'))
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; border-radius: 10px;">
                <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 4px;">Pengajuan Berhasil Terkirim!</div>
                <div>{{ session('success_application') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 24px; padding: 14px; border-radius: 10px;">
                <ul style="margin: 0; padding-left: 20px; font-size: 0.88rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card" style="padding: 30px; border-radius: 14px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
            <form action="{{ route('internship.apply') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- 1. Searchable Autocomplete Sekolah ACP --}}
                <div style="margin-bottom: 22px; position: relative;" id="acpSearchWrapper">
                    <label class="form-label" style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                        Cari / Ketik Nama Sekolah Asal <span style="color: var(--danger);">*</span>
                    </label>
                    
                    <input type="text" id="acpSchoolInput" class="form-control" autocomplete="off"
                           placeholder="Ketik nama sekolah mitra ACP (contoh: SMK Negeri 1 Surabaya)..."
                           style="padding: 11px 14px; font-size: 0.92rem; border-radius: 8px;">
                    
                    <input type="hidden" name="acp_partner_school_id" id="acpPartnerSchoolId" value="{{ old('acp_partner_school_id') }}">

                    <div id="acpSchoolDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #ffffff; border: 1px solid var(--border); border-radius: 8px; max-height: 220px; overflow-y: auto; z-index: 100; box-shadow: 0 10px 25px rgba(0,0,0,0.1); margin-top: 4px;">
                        @foreach($acpSchools as $school)
                            <div class="acp-school-option" data-id="{{ $school->id }}" data-name="{{ $school->name }}" style="padding: 10px 14px; font-size: 0.88rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $school->name }}</div>
                                @if($school->city || $school->province)
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ implode(', ', array_filter([$school->city, $school->province])) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 6px;">
                        Ketik nama sekolah Anda. Hanya sekolah yang terdaftar dalam mitra ACP yang dapat mengajukan.
                    </div>
                </div>

                {{-- Non-ACP School Warning Card --}}
                <div id="nonAcpWarning" style="display: none; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 10px; padding: 20px; margin-bottom: 22px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div>
                            <h4 style="margin: 0; color: #be123c; font-size: 0.98rem; font-weight: 700;">Sekolah Belum Terdaftar Mitra ACP</h4>
                            <p style="font-size: 0.85rem; color: #9f1239; margin: 6px 0 12px 0; line-height: 1.5;">
                                Mohon maaf, Klinik Komputer saat ini membuka pendaftaran Internship & Prakerin khusus untuk Sekolah/Instansi Mitra Axioo Class Program (ACP).
                            </p>
                            <a href="https://wa.me/6285103051000?text=Halo%20Klinik%20Komputer,%20sekolah%20kami%20tertarik%20untuk%20bermitra%20dengan%20Axioo%20Class%20Program%20(ACP)%20dan%20Internship" 
                               target="_blank" class="btn" style="background: #be123c; color: #ffffff; font-size: 0.82rem; padding: 8px 18px; border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                                Hubungi Tim ACP via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Fields Container (Shown when ACP School is selected) --}}
                <div id="acpFormFields" style="display: none;">
                    
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.85rem; color: #166534; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        Sekolah Terverifikasi Mitra Axioo Class Program (ACP). Silakan lengkapi formulir pengajuan di bawah ini.
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Nama Pembimbing / PJ Sekolah <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control" placeholder="Contoh: Drs. Budi Santoso, M.Pd" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">No. WhatsApp / Telepon <span style="color: var(--danger);">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contoh: 08123456789" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Email Instansi / Pembimbing</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Contoh: smkn1@sekolah.sch.id" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Jumlah Kuota Siswa <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="student_count" value="{{ old('student_count', 2) }}" min="1" max="100" class="form-control" placeholder="Jumlah siswa" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Estimasi Tanggal Mulai <span style="color: var(--danger);">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Estimasi Tanggal Selesai <span style="color: var(--danger);">*</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control" style="padding: 9px 12px; font-size: 0.88rem;">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Upload Surat Pengantar / Proposal (Opsional)</label>
                        <input type="file" name="proposal_file" class="form-control" accept=".pdf,.doc,.docx" style="padding: 8px 12px; font-size: 0.85rem;">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Format PDF, DOC, atau DOCX (Maks 10MB).</div>
                    </div>

                    <div style="margin-bottom: 22px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Pengajuan 2 siswa jurusan TKJ untuk posisi Teknisi & Support." style="padding: 9px 12px; font-size: 0.85rem;">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; font-size: 0.95rem;">
                        Kirim Pengajuan Internship
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- 3. Bottom Section: Riwayat & Daftar Siswa Internship (Anak PKL) -->
<div id="galeri-pkl" style="background: var(--bg-main); padding: 60px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.1em;">Cerita Mereka</span>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 6px;">Mengenal Lebih Dekat Siswa Internship Terpilih</h2>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 4px;">
                Kenali perjalanan beberapa siswa PKL yang mewarnai program internship kami, lengkap dengan pengalaman dan pembelajaran mereka.
            </p>
        </div>

        <!-- Filter Tabs & Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--border-light); padding-bottom: 16px; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; gap: 8px; flex-wrap: wrap;" id="filterTabs">
                <button class="filter-tab active" data-filter="all" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s ease;">
                    Semua Kuartal
                </button>
                <button class="filter-tab" data-filter="Q1" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s ease; background: transparent; color: var(--text-muted); line-height: 1;">
                    Q1
                </button>
                <button class="filter-tab" data-filter="Q2" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s ease; background: transparent; color: var(--text-muted); line-height: 1;">
                    Q2
                </button>
                <button class="filter-tab" data-filter="Q3" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s ease; background: transparent; color: var(--text-muted); line-height: 1;">
                    Q3
                </button>
                <button class="filter-tab" data-filter="Q4" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s ease; background: transparent; color: var(--text-muted); line-height: 1;">
                    Q4
                </button>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <select id="yearFilter" style="padding: 8px 16px; font-weight: 700; font-size: 0.8rem; border-radius: 6px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-primary); cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; outline: none; transition: all 0.2s ease;">
                    <option value="all">Semua Tahun</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}">Tahun {{ $year }}</option>
                    @endforeach
                </select>
                
                <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">
                    Menampilkan <span id="studentCount" style="color: var(--primary); font-weight: 800;">{{ $students->count() }}</span> alumni PKL
                </div>
            </div>
        </div>

        @if($students->count() > 0)
        <!-- Students Carousel Wrapper -->
        <div style="position: relative;">
            <!-- Carousel Navigation Arrows -->
            <button type="button" id="prevPklBtn" onclick="scrollPklCarousel(-1)" aria-label="Previous"
                    style="position: absolute; left: -14px; top: 50%; transform: translateY(-50%); z-index: 15; width: 40px; height: 40px; border-radius: 50%; background: var(--bg-card); border: 1px solid var(--border-light); box-shadow: 0 4px 14px rgba(0,0,0,0.12); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s ease;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>

            <button type="button" id="nextPklBtn" onclick="scrollPklCarousel(1)" aria-label="Next"
                    style="position: absolute; right: -14px; top: 50%; transform: translateY(-50%); z-index: 15; width: 40px; height: 40px; border-radius: 50%; background: var(--bg-card); border: 1px solid var(--border-light); box-shadow: 0 4px 14px rgba(0,0,0,0.12); cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-primary); transition: all 0.2s ease;">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>

            <div class="students-grid" id="studentsGrid" style="display: flex; gap: 18px; align-items: stretch; overflow-x: auto; scroll-behavior: smooth; scroll-snap-type: x mandatory; padding: 12px 4px 20px; -webkit-overflow-scrolling: touch; scrollbar-width: thin;">
                @foreach($students as $student)
                @php
                    $divColor = 'rgba(95, 138, 99, 0.08)';
                    $divTextColor = 'var(--primary)';
                    if ($student->division === 'Teknisi') {
                        $divColor = 'rgba(217, 119, 6, 0.08)';
                        $divTextColor = '#d97706';
                    } elseif (in_array($student->division, ['CS', 'Customer Service'])) {
                        $divColor = 'rgba(2, 132, 199, 0.08)';
                        $divTextColor = '#0284c7';
                    } elseif ($student->division === 'Produksi') {
                        $divColor = 'rgba(16, 185, 129, 0.08)';
                        $divTextColor = '#10b981';
                    } elseif ($student->division === 'Digital Sales Media') {
                        $divColor = 'rgba(139, 92, 246, 0.08)';
                        $divTextColor = '#8b5cf6';
                    } elseif ($student->division === 'Sales & Marketing') {
                        $divColor = 'rgba(236, 72, 153, 0.08)';
                        $divTextColor = '#ec4899';
                    } elseif ($student->division === 'Admin') {
                        $divColor = 'rgba(100, 116, 139, 0.08)';
                        $divTextColor = '#475569';
                    }
                @endphp
                <div class="student-card" data-quarter="{{ $student->period->quarter }}" data-year="{{ $student->period->year }}" style="flex: 0 0 250px; width: 250px; min-width: 250px; scroll-snap-align: start; background: var(--bg-card); border: 1px solid var(--border-light); border-radius: 14px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.03); transition: all 0.25s ease; display: flex; flex-direction: column;">
                    
                    <div style="position: relative; height: 80px; display: flex; justify-content: center; align-items: flex-end; flex-shrink: 0;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(13, 30, 17, 0.45), rgba(13, 30, 17, 0.75)), url('/images/pkl-banner.png') no-repeat center center; background-size: cover; border-top-left-radius: 13px; border-top-right-radius: 13px;"></div>
                        
                        <div style="position: relative; z-index: 2; margin-bottom: -36px;">
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" 
                                 style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid var(--bg-card); box-shadow: 0 4px 10px rgba(0,0,0,0.12); background: #fff;"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=5f8a63&color=fff';">
                        </div>
                    </div>

                    <div style="padding: 44px 16px 16px; text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="font-weight: 800; font-size: 0.92rem; color: var(--text-primary); line-height: 1.3; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $student->name }}">
                                {{ $student->name }}
                            </div>
                            <div style="font-size: 0.76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $student->school }}">
                                {{ $student->school }}
                            </div>
                            
                            <div style="display: inline-block; padding: 3px 12px; background: {{ $divColor }}; color: {{ $divTextColor }}; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;">
                                {{ $student->division }}
                            </div>

                            @if($student->testimonial)
                            <div style="margin-top: 12px; font-size: 0.74rem; color: var(--text-secondary); font-style: italic; line-height: 1.45; background: rgba(95, 138, 99, 0.04); padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(95, 138, 99, 0.12); text-align: center; word-break: break-word; overflow-wrap: anywhere; word-wrap: break-word;">
                                "{{ $student->testimonial }}"
                            </div>
                            @endif
                        </div>

                        <div style="margin-top: 14px; padding-top: 10px; border-top: 1px dashed var(--border-light); font-size: 0.74rem; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center;">
                            <span>Periode {{ $student->period->quarter }} {{ $student->period->year }}</span>
                            <span style="color: var(--primary); font-weight: 700;">Lulus</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
                    <div style="text-align: center; padding: 50px 20px; background: var(--bg-card); border-radius: 12px; border: 1px dashed var(--border);">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">Belum ada data siswa PKL terpublikasi</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">Riwayat anak PKL akan muncul di sini setelah diverifikasi oleh Admin.</p>
        </div>
        @endif

    </div>
</div>

<script>
    const acpInput = document.getElementById('acpSchoolInput');
    const acpHiddenId = document.getElementById('acpPartnerSchoolId');
    const acpDropdown = document.getElementById('acpSchoolDropdown');
    const options = document.querySelectorAll('.acp-school-option');
    const warningBox = document.getElementById('nonAcpWarning');
    const formFields = document.getElementById('acpFormFields');

    function selectSchool(id, name) {
        if (acpHiddenId) acpHiddenId.value = id;
        if (acpInput) acpInput.value = name;
        if (acpDropdown) acpDropdown.style.display = 'none';

        if (id) {
            if (warningBox) warningBox.style.display = 'none';
            if (formFields) formFields.style.display = 'block';
        } else {
            if (warningBox) warningBox.style.display = 'block';
            if (formFields) formFields.style.display = 'none';
        }
    }

    if (acpInput && acpDropdown) {
        acpInput.addEventListener('focus', function() {
            acpDropdown.style.display = 'block';
        });

        acpInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            acpDropdown.style.display = 'block';
            let matchCount = 0;
            let exactMatch = null;

            options.forEach(opt => {
                const schoolName = opt.dataset.name.toLowerCase();
                if (query === '' || schoolName.includes(query)) {
                    opt.style.display = 'block';
                    matchCount++;
                    if (schoolName === query) {
                        exactMatch = opt;
                    }
                } else {
                    opt.style.display = 'none';
                }
            });

            if (exactMatch) {
                acpHiddenId.value = exactMatch.dataset.id;
                warningBox.style.display = 'none';
                formFields.style.display = 'block';
            } else if (query.length > 0 && matchCount === 0) {
                acpHiddenId.value = '';
                warningBox.style.display = 'block';
                formFields.style.display = 'none';
            } else {
                acpHiddenId.value = '';
                warningBox.style.display = 'none';
                formFields.style.display = 'none';
            }
        });

        options.forEach(opt => {
            opt.addEventListener('click', function() {
                selectSchool(this.dataset.id, this.dataset.name);
            });
        });

        document.addEventListener('click', function(e) {
            if (!acpInput.contains(e.target) && !acpDropdown.contains(e.target)) {
                acpDropdown.style.display = 'none';
                if (acpInput.value.trim() !== '' && !acpHiddenId.value) {
                    warningBox.style.display = 'block';
                    formFields.style.display = 'none';
                }
            }
        });
    }

    // PKL Carousel Scroll Script
    window.scrollPklCarousel = function(direction) {
        const grid = document.getElementById('studentsGrid');
        if (grid) {
            const scrollStep = 268 * 2 * direction; // Scroll width of 2 cards
            grid.scrollBy({ left: scrollStep, behavior: 'smooth' });
        }
    };

    // Filter Tabs Script for PKL Students
    const filterTabs = document.querySelectorAll('.filter-tab');
    const yearFilter = document.getElementById('yearFilter');
    const studentCards = document.querySelectorAll('.student-card');
    const studentCount = document.getElementById('studentCount');

    let currentQuarter = 'all';
    let currentYear = 'all';

    function filterStudents() {
        let count = 0;
        studentCards.forEach(card => {
            const cardQuarter = card.dataset.quarter;
            const cardYear = card.dataset.year;

            const matchQuarter = (currentQuarter === 'all' || cardQuarter === currentQuarter);
            const matchYear = (currentYear === 'all' || cardYear === currentYear);

            if (matchQuarter && matchYear) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        if (studentCount) studentCount.textContent = count;

        const grid = document.getElementById('studentsGrid');
        if (grid) grid.scrollTo({ left: 0, behavior: 'smooth' });
    }

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => {
                t.classList.remove('active');
                t.style.background = 'transparent';
                t.style.color = 'var(--text-muted)';
            });
            this.classList.add('active');
            this.style.background = 'var(--primary)';
            this.style.color = '#ffffff';

            currentQuarter = this.dataset.filter;
            filterStudents();
        });
    });

    if (yearFilter) {
        yearFilter.addEventListener('change', function() {
            currentYear = this.value;
            filterStudents();
        });
    }
});
</script>
@endsection
