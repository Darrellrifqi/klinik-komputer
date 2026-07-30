@extends('layouts.app')
@section('title', 'Aktivasi Membership & Laptop | Klinik Komputer')
@section('meta_description', 'Aktifkan keanggotaan member resmi Klinik Komputer melalui jalur pengadaan sekolah maupun pembelian mandiri.')

@section('content')
<div class="activation-wrapper-container" style="min-height: 100vh; padding: 120px 20px 80px; background: var(--bg-alt); display: flex; align-items: center; justify-content: center; width: 100%; box-sizing: border-box;">
    <div style="width: 100%; max-width: 1100px; display: grid; grid-template-columns: 1fr; gap: 30px; align-items: start; min-width: 0;">
        
        <!-- Main Grid for Premium Presentation -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 30px; width: 100%; min-width: 0;">
            @if(!isset($kit))
                <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; width: 100%; align-items: start;" class="activation-grid-wrapper">
            @else
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; width: 100%; align-items: start;" class="activation-grid-wrapper">
            @endif
                
                {{-- LEFT COLUMN: MEMBERSHIP BENEFITS --}}
                <div class="card" style="padding: 36px; border: 1px solid var(--border); background: var(--bg-card); box-shadow: var(--shadow-lg);">
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); display: inline-block; margin-bottom: 6px;">Klinik Komputer</span>
                        <h2 style="font-size: 1.6rem; color: var(--text-primary); margin-bottom: 8px; font-weight: 800;">Keuntungan Membership</h2>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.4;">Bergabunglah menjadi bagian dari member resmi Klinik Komputer dan nikmati berbagai keuntungan eksklusif untuk menjaga performa perangkat teknologi Anda.</p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <!-- Benefit 1 -->
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(95, 138, 99, 0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" style="margin-top: 1px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); margin-bottom: 4px; font-weight: 700;">Konsultasi 1 on 1</h4>
                                <p style="color: var(--text-secondary); font-size: 0.82rem; line-height: 1.4;">Akses konsultasi langsung secara personal dengan teknisi tersertifikasi kami untuk mendiagnosis kendala perangkat Anda.</p>
                            </div>
                        </div>

                        <!-- Benefit 2 -->
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(95, 138, 99, 0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); margin-bottom: 4px; font-weight: 700;">Onsite Service (Khusus Bandung)</h4>
                                <p style="color: var(--text-secondary); font-size: 0.82rem; line-height: 1.4;">Tidak perlu keluar rumah. Teknisi ahli kami siap datang langsung melakukan perbaikan di tempat tinggal atau instansi Anda.</p>
                            </div>
                        </div>

                        <!-- Benefit 3 -->
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: rgba(95, 138, 99, 0.08); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 700;">
                                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); margin-bottom: 4px; font-weight: 700;">Layanan Remote Assistance</h4>
                                <p style="color: var(--text-secondary); font-size: 0.82rem; line-height: 1.4;">Solusi cepat untuk kendala software, instalasi driver, maupun optimasi sistem melalui koneksi remote jarak jauh yang aman.</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 30px; padding: 14px; background: var(--bg-alt); border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.78rem; color: var(--text-muted); line-height: 1.4;">
                        <strong>Garansi 1 Tahun:</strong> Setiap unit laptop hasil perakitan pengadaan sekolah maupun retail berhak mendapatkan garansi service dan komponen resmi selama 12 bulan sejak masa aktivasi.
                    </div>
                </div>

                {{-- RIGHT COLUMN: ACTIVATION / REGISTRATION FORM PANEL --}}
                <div>
                    {{-- Case A: Valid School Procurement ID Member Form --}}
                    @if(isset($kit))
                        <div class="card" style="padding: 36px; border: 1px solid var(--border); background: var(--bg-card); box-shadow: var(--shadow-lg);">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <h3 style="font-size: 1.3rem; font-weight: 800; text-transform: uppercase; color: var(--primary); letter-spacing: 0.02em;">Aktivasi Laptop Sekolah</h3>
                                <p style="color: var(--text-secondary); font-size: 0.82rem; margin-top: 4px;">Daftarkan akun member Anda untuk melacak garansi laptop rakitan sekolah.</p>
                            </div>

                            <div class="alert alert-success" style="margin-bottom: 20px; align-items: center; justify-content: space-between; display: flex; padding: 12px 16px; border-radius: 6px;">
                                <div>
                                    <div style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700; color: var(--success); letter-spacing: 0.05em;">Serial Number Terverifikasi</div>
                                    <strong style="font-family: monospace; font-size: 1.05rem; color: var(--success);">{{ $kit->member_id }}</strong>
                                </div>
                                <span class="badge badge-success" style="font-size: 0.65rem; padding: 3px 6px;">Valid</span>
                            </div>

                            <!-- Laptop Info Card -->
                            <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: 6px; padding: 14px; margin-bottom: 20px;">
                                <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 8px;">Informasi Unit Laptop Anda</div>
                                <div style="display: flex; flex-direction: column; gap: 4px; font-size: 0.82rem;">
                                    @if($kit->student_name)
                                        <div><span style="color: var(--text-muted);">Nama Pemilik:</span> <strong>{{ $kit->student_name }}</strong></div>
                                    @endif
                                    <div><span style="color: var(--text-muted);">Institusi:</span> <strong>{{ $kit->order ? $kit->order->school_name : ($kit->institution ?? 'Mitra ACP') }}</strong></div>
                                    <div><span style="color: var(--text-muted);">Tipe Laptop:</span> <strong>{{ $kit->order ? $kit->order->axioo_model : ($kit->unit_model ?? 'Axioo Laptop') }}</strong></div>
                                </div>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-error" style="margin-bottom: 16px; font-size: 0.85rem; padding: 12px;">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('kit.activate.submit') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                                @csrf
                                <input type="hidden" name="member_id" value="{{ $kit->member_id }}">
                                <input type="hidden" name="name" value="{{ $kit->student_name }}">

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Alamat Email Aktif <span>*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Contoh: siswa@gmail.com" required style="padding: 10px 14px; border-radius: 6px;">
                                    <span class="form-hint" style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px; display: block;">Email ini akan digunakan untuk login dashboard.</span>
                                    @error('email')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Nomor WhatsApp Siswa <span>*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required style="padding: 10px 14px; border-radius: 6px;">
                                    @error('phone')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Password Akun <span>*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required autocomplete="new-password" style="padding: 10px 14px; border-radius: 6px;">
                                        @error('password')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Ulangi Password <span>*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password" required autocomplete="new-password" style="padding: 10px 14px; border-radius: 6px;">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 8px; padding: 12px; font-weight: 700; border-radius: 6px;">Aktifkan Member & Daftar Laptop</button>
                            </form>

                            <div style="text-align: center; margin-top: 20px; border-top: 1px solid var(--border-light); padding-top: 16px;">
                                <a href="{{ route('kit.activation') }}" style="font-size: 0.8rem; color: var(--primary); font-weight: 600;">Ganti Serial Number Verifikasi</a>
                            </div>
                        </div>

                    {{-- Case B: Toggle Switcher for School Path or Manual Path --}}
                    @else
                        <div class="card" style="padding: 0; border: 1px solid var(--border); background: var(--bg-card); box-shadow: var(--shadow-lg); overflow: hidden;">
                            {{-- Tab Headers --}}
                            <div style="display: flex; background: var(--bg-alt); border-bottom: 1px solid var(--border);">
                                <button id="tabBtnSchool" onclick="switchTab('school')" style="flex: 1; padding: 16px; border: none; background: var(--bg-card); font-weight: 700; font-size: 0.85rem; color: var(--primary); border-bottom: 2px solid var(--primary); cursor: pointer; transition: all 0.2s;">
                                    MEMBER PENGADAAN
                                </button>
                                <button id="tabBtnRegular" onclick="switchTab('regular')" style="flex: 1; padding: 16px; border: none; background: transparent; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
                                    MEMBER UMUM
                                </button>
                            </div>

                            <div style="padding: 36px;">
                                @if(isset($error))
                                    <div class="alert alert-error" style="margin-bottom: 20px;">
                                        <div>{{ $error }}</div>
                                    </div>
                                @endif
                                
                                @if(session('error'))
                                    <div class="alert alert-error" style="margin-bottom: 20px;">
                                        <div>{{ session('error') }}</div>
                                    </div>
                                @endif

                                {{-- Tab Panel 1: JALUR SEKOLAH --}}
                                <div id="panelSchool" style="display: block;">
                                    <div style="margin-bottom: 22px;">
                                        <h3 style="font-size: 1.15rem; color: var(--text-primary); font-weight: 800; margin-bottom: 6px;">Verifikasi Unit Laptop Pengadaan Sekolah</h3>
                                        <p style="color: var(--text-secondary); font-size: 0.82rem; line-height: 1.4;">Bagi Anda siswa/siswi sekolah mitra yang menerima unit laptop hasil pengadaan, silakan masukkan nomor Serial Number (SN) unit Anda untuk proses aktivasi.</p>
                                    </div>

                                    <form action="{{ route('kit.activation') }}" method="GET" style="display: flex; flex-direction: column; gap: 16px;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Masukkan Serial Number (SN) Unit <span>*</span></label>
                                            <input type="text" name="member_id" class="form-control" 
                                                   value="{{ $memberId }}"
                                                   placeholder="Contoh: SN-AXIOO-12345678" 
                                                   style="font-family: monospace; font-size: 1rem; letter-spacing: 1px; text-transform: uppercase; padding: 12px 14px; border-radius: 6px;"
                                                   required autocomplete="off">
                                            <span class="form-hint" style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; display: block;">Nomor Serial Number (SN) yang tertera pada bagian bawah casing laptop atau box kardus laptop Anda.</span>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; border-radius: 6px;">Verifikasi Serial Number (SN)</button>
                                    </form>

                                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-light); font-size: 0.78rem; color: var(--text-muted); text-align: center; line-height: 1.4;">
                                        Butuh bantuan atau Serial Number tidak valid? Hubungi guru pendamping atau 
                                        <a href="https://wa.me/6285103051000" target="_blank" style="color: var(--primary); font-weight: 700;">WhatsApp Support</a>.
                                    </div>
                                </div>

                                {{-- Tab Panel 2: JALUR RETAIL --}}
                                <div id="panelRegular" style="display: none;">
                                    <div style="margin-bottom: 22px;">
                                        <h3 style="font-size: 1.15rem; color: var(--text-primary); font-weight: 800; margin-bottom: 6px;">Registrasi Member Mandiri (Umum)</h3>
                                        <p style="color: var(--text-secondary); font-size: 0.84rem; line-height: 1.5;">Daftarkan keanggotaan Member Umum Klinik Komputer untuk menikmati benefit spesial: <strong>2x Tune-Up gratis per tahun</strong> dan layanan <strong>Onsite Servis Bandung Raya</strong> (perbaikan langsung di tempat Anda). Pendaftaran keanggotaan dapat dilakukan dengan datang langsung ke kantor Klinik Komputer atau hubungi CS via WhatsApp.</p>
                                    </div>

                                    <form action="{{ route('kit.activate.regular') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 14px;">
                                        @csrf

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">NIK (Nomor Induk Kependudukan) <span>*</span></label>
                                            <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" placeholder="16 digit Nomor Induk Kependudukan" required style="padding: 10px 14px; border-radius: 6px; font-family: monospace;">
                                            @error('nik')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Nama Lengkap <span>*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required style="padding: 10px 14px; border-radius: 6px;">
                                            @error('name')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Alamat Email <span>*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Contoh: customer@gmail.com" required style="padding: 10px 14px; border-radius: 6px;">
                                            @error('email')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Nomor WhatsApp Aktif <span>*</span></label>
                                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required style="padding: 10px 14px; border-radius: 6px;">
                                            @error('phone')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                        </div>

                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label class="form-label">Password <span>*</span></label>
                                                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required autocomplete="new-password" style="padding: 10px 14px; border-radius: 6px;">
                                                @error('password')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                            </div>

                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label class="form-label">Ulangi Password <span>*</span></label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required autocomplete="new-password" style="padding: 10px 14px; border-radius: 6px;">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 14px; padding: 12px; font-weight: 700; border-radius: 6px;">Daftar Member Mandiri</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 16px;">
            <a href="{{ route('home') }}" style="color: var(--text-muted); font-size: 0.82rem; font-weight: 500; transition: var(--transition);">Kembali ke Beranda</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(type) {
        const btnSchool = document.getElementById('tabBtnSchool');
        const btnRegular = document.getElementById('tabBtnRegular');
        const panelSchool = document.getElementById('panelSchool');
        const panelRegular = document.getElementById('panelRegular');
        
        if (type === 'school') {
            btnSchool.style.color = 'var(--primary)';
            btnSchool.style.borderBottomColor = 'var(--primary)';
            btnSchool.style.fontWeight = '700';
            btnSchool.style.background = 'var(--bg-card)';
            
            btnRegular.style.color = 'var(--text-muted)';
            btnRegular.style.borderBottomColor = 'transparent';
            btnRegular.style.fontWeight = '600';
            btnRegular.style.background = 'transparent';
            
            panelSchool.style.display = 'block';
            panelRegular.style.display = 'none';
        } else {
            btnRegular.style.color = 'var(--primary)';
            btnRegular.style.borderBottomColor = 'var(--primary)';
            btnRegular.style.fontWeight = '700';
            btnRegular.style.background = 'var(--bg-card)';
            
            btnSchool.style.color = 'var(--text-muted)';
            btnSchool.style.borderBottomColor = 'transparent';
            btnSchool.style.fontWeight = '600';
            btnSchool.style.background = 'transparent';
            
            panelSchool.style.display = 'none';
            panelRegular.style.display = 'block';
        }
    }

    // Auto-switch tab if redirected with validation errors of regular path
    @if($errors->has('nik') || old('nik'))
        window.onload = function() {
            switchTab('regular');
        }
    @endif
</script>
@endpush

<style>
    @media (max-width: 900px) {
        .activation-grid-wrapper {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
