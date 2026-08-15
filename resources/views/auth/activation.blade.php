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
                        <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); display: inline-block; margin-bottom: 6px;">KLINIK KOMPUTER - PRIORITY MEMBER</span>
                        <h2 style="font-size: 1.45rem; color: var(--text-primary); margin-bottom: 8px; font-weight: 800; line-height: 1.3;">Upgrade Perawatan Perangkatmu, Lebih Hemat & Lebih Prioritas</h2>
                        <p style="color: var(--text-secondary); font-size: 0.83rem; line-height: 1.5;">Dari sekadar servis rutin jadi bebas antre, bebas mikir jadwal. Pilih paket Priority Member sesuai kebutuhan unit kamu, dan nikmati perawatan berkala tanpa drama selama masa keanggotaan.</p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <!-- 1. BASIC PRIORITY -->
                        <div style="padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); font-weight: 800; margin: 0;">BASIC PRIORITY</h4>
                                <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">Rp 199.000 / 12 Bln</span>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary); margin: 3px 0 4px;">Mulai Rawat Rutin, Harga Ramah Kantong</div>
                            <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.5; margin: 0;">Cocok untuk kamu yang baru mau coba rasakan bedanya rawat rutin. Dapatkan 2x Essential Cleaning dan 1x Instalasi OS dalam setahun, plus konsultasi & remote assistance tanpa batas selama masa aktif member.</p>
                        </div>

                        <!-- 2. SILVER PRIORITY -->
                        <div style="padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); font-weight: 800; margin: 0;">SILVER PRIORITY</h4>
                                <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">Rp 279.000 / 18 Bln</span>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary); margin: 3px 0 4px;">Perawatan Lebih Menyeluruh, Worry-Free</div>
                            <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.5; margin: 0;">Naik level ke Deep Care Cleaning - pembersihan menyeluruh sampai bongkar total, plus repasta thermal paste. 2x Deep Care Cleaning & 2x Instalasi OS selama 18 bulan, konsultasi dan remote assistance unlimited.</p>
                        </div>

                        <!-- 3. GOLD PRIORITY -->
                        <div style="padding-bottom: 16px; border-bottom: 1px solid var(--border-light);">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <h4 style="font-size: 0.95rem; color: var(--text-primary); font-weight: 800; margin: 0;">GOLD PRIORITY</h4>
                                    <span style="background: rgba(95, 138, 99, 0.12); color: var(--primary); font-size: 0.62rem; font-weight: 800; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">Rekomendasi</span>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">Rp 399.000 / 18 Bln</span>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary); margin: 3px 0 4px;">Paket Andalan, Onsite Service Gratis!</div>
                            <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.5; margin: 0;">Pilihan paling lengkap dan paling banyak dipilih member kami. 3x Deep Care Cleaning, 3x Instalasi OS, dan yang paling ditunggu - Onsite Service GRATIS ke lokasi kamu (radius berlaku). Konsultasi & remote assistance tetap unlimited.</p>
                        </div>

                        <!-- 4. PLATINUM PRIORITY -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                                <h4 style="font-size: 0.95rem; color: var(--text-primary); font-weight: 800; margin: 0;">PLATINUM PRIORITY</h4>
                                <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);">Rp 599.000 / 18 Bln</span>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary); margin: 3px 0 4px;">Prioritas Utama, Untuk yang Serius Jaga Performa</div>
                            <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.5; margin: 0;">Buat kamu yang enggak mau kompromi. 4x Deep Care Cleaning, 4x Instalasi OS, Onsite Service gratis dengan prioritas jadwal di atas member lain, plus konsultasi & remote assistance unlimited.</p>
                            <div style="margin-top: 4px; font-size: 0.72rem; color: var(--text-muted); font-style: italic;">*Bisa digunakan oleh 2 tipe unit yang Berbeda</div>
                        </div>
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
                                    <div style="margin-bottom: 22px; text-align: center;">
                                        <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--primary); letter-spacing: 0.08em; background: rgba(95, 138, 99, 0.1); padding: 4px 12px; border-radius: 12px; display: inline-block; margin-bottom: 6px;">PROGRAM PRIORITY MEMBER</span>
                                        <h3 style="font-size: 1.25rem; color: var(--text-primary); font-weight: 800; margin-bottom: 4px;">Pilih Paket Keanggotaan Priority</h3>
                                        <p style="color: var(--text-secondary); font-size: 0.84rem; line-height: 1.5; margin: 0;">Bebas Lemot, Bebas Drama. Pilih tier yang paling sesuai dengan kebutuhan unit Anda</p>
                                    </div>

                                    <form action="{{ route('kit.activate.regular') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                                        @csrf

                                        {{-- 1. Pilihan Paket Tier Priority Member --}}
                                        <div style="margin-bottom: 8px;">
                                            <label class="form-label" style="font-size: 0.82rem; font-weight: 700; margin-bottom: 10px; display: block;">1. PILIH PAKET MEMBER PRIORITY <span style="color: var(--danger);">*</span></label>
                                            
                                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 12px;">
                                                
                                                <!-- Basic Priority -->
                                                <div class="priority-plan-card" onclick="selectPlan('Basic Priority', 199000, 12, this)" id="plan-card-basic" style="border: 2px solid var(--border); border-radius: 10px; padding: 14px; background: var(--bg-card); cursor: pointer; transition: all 0.2s ease; position: relative;">
                                                    <div style="font-size: 0.7rem; font-weight: 800; color: #475569; text-transform: uppercase;">BASIC PRIORITY</div>
                                                    <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 4px 0;">Rp 199.000 <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 500;">/ 12 Bln</span></div>
                                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px;">Paket hemat pelajar</div>
                                                    <ul style="font-size: 0.72rem; color: var(--text-secondary); padding-left: 14px; margin: 0; line-height: 1.5;">
                                                        <li><strong>2x</strong> Essential Cleaning</li>
                                                        <li><strong>1x</strong> Instalasi OS Essential</li>
                                                        <li><strong>Unlimited</strong> Konsultasi 1 on 1</li>
                                                        <li><strong>Unlimited</strong> Remote Assistance</li>
                                                        <li>Onsite Service Reguler</li>
                                                    </ul>
                                                </div>

                                                <!-- Silver Priority -->
                                                <div class="priority-plan-card" onclick="selectPlan('Silver Priority', 279000, 18, this)" id="plan-card-silver" style="border: 2px solid var(--border); border-radius: 10px; padding: 14px; background: var(--bg-card); cursor: pointer; transition: all 0.2s ease; position: relative;">
                                                    <div style="font-size: 0.7rem; font-weight: 800; color: #0284c7; text-transform: uppercase;">SILVER PRIORITY</div>
                                                    <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 4px 0;">Rp 279.000 <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 500;">/ 18 Bln</span></div>
                                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px;">Perawatan rutin berkala</div>
                                                    <ul style="font-size: 0.72rem; color: var(--text-secondary); padding-left: 14px; margin: 0; line-height: 1.5;">
                                                        <li><strong>2x</strong> Deep Care Cleaning</li>
                                                        <li><strong>2x</strong> Instalasi OS Essential</li>
                                                        <li><strong>Unlimited</strong> Konsultasi 1 on 1</li>
                                                        <li><strong>Unlimited</strong> Remote Assistance</li>
                                                        <li>Onsite Service Reguler</li>
                                                    </ul>
                                                </div>

                                                <!-- Gold Priority (Default Selected) -->
                                                <div class="priority-plan-card selected-plan" onclick="selectPlan('Gold Priority', 399000, 18, this)" id="plan-card-gold" style="border: 2px solid var(--primary); border-radius: 10px; padding: 14px; background: var(--bg-card); cursor: pointer; transition: all 0.2s ease; position: relative; box-shadow: 0 4px 15px rgba(95, 138, 99, 0.18);">
                                                    <div style="position: absolute; top: -10px; right: 10px; background: #d97706; color: #fff; font-size: 0.62rem; font-weight: 800; padding: 2px 8px; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.03em;">Rekomendasi</div>
                                                    <div style="font-size: 0.7rem; font-weight: 800; color: #d97706; text-transform: uppercase;">GOLD PRIORITY</div>
                                                    <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 4px 0;">Rp 399.000 <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 500;">/ 18 Bln</span></div>
                                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px;">Paket terpopuler & terbaik</div>
                                                    <ul style="font-size: 0.72rem; color: var(--text-secondary); padding-left: 14px; margin: 0; line-height: 1.5;">
                                                        <li><strong>3x</strong> Deep Care Cleaning</li>
                                                        <li><strong>3x</strong> Instalasi OS Essential</li>
                                                        <li><strong>Unlimited</strong> Konsultasi 1 on 1</li>
                                                        <li><strong>Unlimited</strong> Remote Assistance</li>
                                                        <li><strong style="color: #059669;">GRATIS</strong> Onsite Service</li>
                                                    </ul>
                                                </div>

                                                <!-- Platinum Priority -->
                                                <div class="priority-plan-card" onclick="selectPlan('Platinum Priority', 599000, 18, this)" id="plan-card-platinum" style="border: 2px solid var(--border); border-radius: 10px; padding: 14px; background: var(--bg-card); cursor: pointer; transition: all 0.2s ease; position: relative;">
                                                    <div style="font-size: 0.7rem; font-weight: 800; color: #7c3aed; text-transform: uppercase;">PLATINUM PRIORITY</div>
                                                    <div style="font-size: 1.15rem; font-weight: 800; color: var(--primary); margin: 4px 0;">Rp 599.000 <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 500;">/ 18 Bln</span></div>
                                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px;">Perlindungan maksimal VIP</div>
                                                    <ul style="font-size: 0.72rem; color: var(--text-secondary); padding-left: 14px; margin: 0; line-height: 1.5;">
                                                        <li><strong>4x</strong> Deep Care Cleaning</li>
                                                        <li><strong>4x</strong> Instalasi OS Essential</li>
                                                        <li><strong>Unlimited</strong> Konsultasi 1 on 1</li>
                                                        <li><strong>Unlimited</strong> Remote Assistance</li>
                                                        <li><strong style="color: #059669;">GRATIS</strong> Onsite + Prioritas</li>
                                                    </ul>
                                                </div>

                                            </div>

                                            <input type="hidden" name="membership_plan" id="membership_plan_input" value="{{ old('membership_plan', 'Gold Priority') }}">
                                            @error('membership_plan')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
                                        </div>

                                        {{-- 2. Form Pengisian Data Diri --}}
                                        <div style="border-top: 1px solid var(--border-light); padding-top: 14px;">
                                            <label class="form-label" style="font-size: 0.82rem; font-weight: 700; margin-bottom: 12px; display: block;">2. ISI DATA DIRI PENDAFTARAN</label>

                                            <div style="display: flex; flex-direction: column; gap: 14px;">
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

                                                {{-- Form Input Serial Number (SN) Perangkat untuk Basic, Silver, Gold --}}
                                                <div class="form-group" id="sn_group_container" style="margin-bottom: 0; display: {{ old('membership_plan', 'Gold Priority') === 'Platinum Priority' ? 'none' : 'block' }};">
                                                    <label class="form-label">Serial Number (SN) Perangkat / Laptop <span>*</span></label>
                                                    <input type="text" name="registered_sn" id="registered_sn_input" class="form-control" value="{{ old('registered_sn') }}" placeholder="Masukkan Serial Number (SN) perangkat terdaftar" {{ old('membership_plan', 'Gold Priority') === 'Platinum Priority' ? '' : 'required' }} style="padding: 10px 14px; border-radius: 6px; font-family: monospace;">
                                                    <span class="form-hint" style="font-size: 0.72rem; color: var(--text-muted); margin-top: 3px; display: block;">*Paket <span id="hint_plan_name">{{ old('membership_plan', 'Gold Priority') }}</span> hanya berlaku untuk 1 Serial Number (SN) perangkat yang terdaftar.</span>
                                                    @error('registered_sn')<span class="form-error" style="color: var(--danger); font-size: 0.72rem; margin-top: 2px; display: block;">{{ $message }}</span>@enderror
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
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 14px; padding: 12px; font-weight: 700; border-radius: 6px;">Daftar Member Priority</button>
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

    function selectPlan(name, price, duration, el) {
        document.querySelectorAll('.priority-plan-card').forEach(card => {
            card.style.borderColor = 'var(--border)';
            card.style.boxShadow = 'none';
        });
        el.style.borderColor = 'var(--primary)';
        el.style.boxShadow = '0 4px 15px rgba(95, 138, 99, 0.2)';
        
        document.getElementById('membership_plan_input').value = name;

        const snContainer = document.getElementById('sn_group_container');
        const snInput = document.getElementById('registered_sn_input');
        const hintPlanName = document.getElementById('hint_plan_name');

        if (name === 'Platinum Priority') {
            if (snContainer) snContainer.style.display = 'none';
            if (snInput) {
                snInput.required = false;
            }
        } else {
            if (snContainer) snContainer.style.display = 'block';
            if (snInput) snInput.required = true;
            if (hintPlanName) hintPlanName.innerText = name;
        }
    }

    // Auto-switch tab if redirected with validation errors of regular path
    @if($errors->has('nik') || $errors->has('membership_plan') || $errors->has('registered_sn') || old('nik'))
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
