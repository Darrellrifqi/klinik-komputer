@extends('layouts.app')
@section('title', 'Pengadaan Unit Laptop | Klinik Komputer')
@section('meta_description', 'Layanan pengadaan laptop Axioo untuk sekolah, madrasah, dan institusi pendidikan. Produk TKDN tinggi, harga resmi, proses mudah melalui e-katalog.')
@section('content')
<div style="padding-top: 64px;">

    @php
        $procHeroFile = '/images/procurement-hero.jpg';
        $procHeroUrl = file_exists(public_path($procHeroFile)) 
            ? $procHeroFile . '?v=' . filemtime(public_path($procHeroFile)) 
            : null;
    @endphp

    <!-- Page Hero -->
    <div style="background: {{ $procHeroUrl ? 'linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.75)), url(\'' . $procHeroUrl . '\') no-repeat center center' : 'var(--bg-alt)' }}; background-size: cover; border-bottom: 1px solid var(--border); padding: 56px 0 48px; color: {{ $procHeroUrl ? '#fff' : 'inherit' }};">
        <div class="section-inner" style="max-width: 900px;">
            <div class="label-line" style="color: {{ $procHeroUrl ? '#a3e635' : 'var(--primary)' }};">Layanan Institusi</div>
            <h1 style="margin-bottom: 14px; color: {{ $procHeroUrl ? '#fff' : 'inherit' }};">Pengadaan Unit Laptop Sekolah & Institusi</h1>
            <p style="color: {{ $procHeroUrl ? 'rgba(255,255,255,0.85)' : 'var(--text-secondary)' }}; font-size: 1rem; max-width: 640px; line-height: 1.7;">
                Klinik Komputer menyediakan layanan pengadaan laptop Axioo resmi untuk institusi pendidikan
                dengan spesifikasi TKDN tinggi &amp; perlindungan <strong>ADP (Accidental Damage Protection) hingga 3 tahun</strong>. Cocok untuk Ujian CBT, laboratorium, dan pembelajaran.
            </p>
            <!-- Quick Stats -->
            <div style="display: flex; gap: 28px; margin-top: 28px; flex-wrap: wrap;">
                <div style="font-size: 0.82rem;">
                    <div style="font-weight: 800; font-size: 1.4rem; color: {{ $procHeroUrl ? '#a3e635' : 'var(--primary)' }};">TKDN</div>
                    <div style="color: {{ $procHeroUrl ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)' }}; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.72rem;">Sertifikasi Produk</div>
                </div>
                <div style="width: 1px; background: {{ $procHeroUrl ? 'rgba(255,255,255,0.15)' : 'var(--border)' }};"></div>
                <div style="font-size: 0.82rem;">
                    <div style="font-weight: 800; font-size: 1.4rem; color: {{ $procHeroUrl ? '#a3e635' : 'var(--primary)' }};">ADP</div>
                    <div style="color: {{ $procHeroUrl ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)' }}; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.72rem;">Accidental Damage</div>
                </div>
                <div style="width: 1px; background: {{ $procHeroUrl ? 'rgba(255,255,255,0.15)' : 'var(--border)' }};"></div>
                <div style="font-size: 0.82rem;">
                    <div style="font-weight: 800; font-size: 1.4rem; color: {{ $procHeroUrl ? '#a3e635' : 'var(--primary)' }};">Garansi Service</div>
                    <div style="color: {{ $procHeroUrl ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)' }}; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.72rem;">Up To 3 Tahun</div>
                </div>
                <div style="width: 1px; background: {{ $procHeroUrl ? 'rgba(255,255,255,0.15)' : 'var(--border)' }};"></div>
                <div style="font-size: 0.82rem;">
                    <div style="font-weight: 800; font-size: 1.4rem; color: {{ $procHeroUrl ? '#a3e635' : 'var(--primary)' }};">E-Katalog</div>
                    <div style="color: {{ $procHeroUrl ? 'rgba(255,255,255,0.6)' : 'var(--text-muted)' }}; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.72rem;">Terdaftar LKPP</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <section class="section">
        <div class="section-inner" style="max-width: 900px;">
            
            <style>
                .procurement-main-grid {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) 340px;
                    gap: 32px;
                    align-items: start;
                }
                .procurement-form-row {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 14px;
                }
                .procurement-category-box {
                    margin-bottom: 18px;
                    padding: 14px 18px;
                    background: var(--bg-alt);
                    border-radius: 10px;
                    border: 1px solid var(--border-light);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: 12px;
                }
                .procurement-add-row-wrap {
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-bottom: 0;
                }

                @media (max-width: 768px) {
                    .procurement-main-grid {
                        grid-template-columns: 1fr !important;
                        gap: 24px !important;
                    }
                    .procurement-form-row {
                        grid-template-columns: 1fr !important;
                        gap: 10px !important;
                    }
                    .procurement-category-box {
                        flex-direction: column !important;
                        align-items: flex-start !important;
                    }
                    .procurement-category-box > div:last-child {
                        width: 100% !important;
                        display: flex !important;
                        justify-content: space-between !important;
                    }
                    .procurement-add-row-wrap {
                        flex-direction: column !important;
                        align-items: flex-start !important;
                    }
                    .procurement-add-row-wrap span {
                        margin-left: 0 !important;
                    }
                    .card {
                        padding: 16px !important;
                    }
                    .procurement-sidebar-sticky {
                        position: static !important;
                    }
                    .table-wrap {
                        border: none !important;
                        background: transparent !important;
                        overflow: visible !important;
                    }
                    #itemsTable {
                        min-width: 100% !important;
                        width: 100% !important;
                        display: block !important;
                    }
                    #itemsTable thead {
                        display: none !important;
                    }
                    #itemsTable tbody {
                        display: flex !important;
                        flex-direction: column !important;
                        gap: 12px !important;
                    }
                    #itemsTable tr.item-row {
                        display: flex !important;
                        flex-direction: column !important;
                        background: var(--bg-alt) !important;
                        padding: 14px !important;
                        border-radius: 10px !important;
                        border: 1px solid var(--border-light) !important;
                        gap: 10px !important;
                    }
                    #itemsTable td {
                        display: block !important;
                        width: 100% !important;
                        padding: 0 !important;
                    }
                    #itemsTable td:last-child {
                        display: flex !important;
                        justify-content: flex-end !important;
                        margin-top: 4px !important;
                    }
                }
            </style>

            <div class="procurement-main-grid">

                <!-- Form -->
                <div>
                    <form action="{{ route('procurement.store') }}" method="POST" id="procurementForm">
                        @csrf

                        @if($errors->any())
                        <div class="alert alert-error" style="margin-bottom: 20px;">
                            <div>
                                <div style="font-weight: 700; margin-bottom: 4px;">Harap perbaiki kesalahan berikut:</div>
                                <ul style="margin: 0; padding-left: 16px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <!-- Bagian 1: Data Institusi -->
                        <div class="card" style="margin-bottom: 20px;">
                            <div style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
                                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 4px;">1. Informasi Institusi</div>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">Identitas sekolah / institusi yang mengajukan pengadaan.</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Sekolah / Institusi <span>*</span></label>
                                <input type="text" name="school_name" class="form-control"
                                       value="{{ old('school_name') }}"
                                       placeholder="Contoh: SMK Negeri 1 Bandung" required>
                                @error('school_name')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="procurement-form-row">
                                <div class="form-group">
                                    <label class="form-label">Jenis Institusi <span>*</span></label>
                                    <select name="school_type" class="form-control" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="sd"               {{ old('school_type')==='sd'               ? 'selected':'' }}>SD / MI</option>
                                        <option value="smp"              {{ old('school_type')==='smp'              ? 'selected':'' }}>SMP / MTs</option>
                                        <option value="sma"              {{ old('school_type')==='sma'              ? 'selected':'' }}>SMA / MA</option>
                                        <option value="smk"              {{ old('school_type')==='smk'              ? 'selected':'' }}>SMK</option>
                                        <option value="perguruan_tinggi" {{ old('school_type')==='perguruan_tinggi' ? 'selected':'' }}>Perguruan Tinggi</option>
                                        <option value="instansi_lain"    {{ old('school_type')==='instansi_lain'    ? 'selected':'' }}>Instansi Lainnya</option>
                                    </select>
                                    @error('school_type')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kota / Kabupaten <span>*</span></label>
                                    <input type="text" name="school_city" class="form-control"
                                           value="{{ old('school_city') }}"
                                           placeholder="Contoh: Bandung" required>
                                    @error('school_city')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Alamat Lengkap Institusi <span>*</span></label>
                                <textarea name="school_address" class="form-control" rows="2"
                                          placeholder="Jalan, Kecamatan, Kabupaten / Kota, Provinsi" required>{{ old('school_address') }}</textarea>
                                @error('school_address')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Bagian 2: Data PIC -->
                        <div class="card" style="margin-bottom: 20px;">
                            <div style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
                                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 4px;">2. Data Perwakilan (PIC)</div>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">Penanggung jawab pengadaan dari pihak sekolah. Kami akan menghubungi melalui WhatsApp.</p>
                            </div>

                            <div class="procurement-form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap PIC <span>*</span></label>
                                    <input type="text" name="pic_name" class="form-control"
                                           value="{{ old('pic_name') }}"
                                           placeholder="Nama guru / staff" required>
                                    @error('pic_name')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jabatan <span>*</span></label>
                                    <input type="text" name="pic_position" class="form-control"
                                           value="{{ old('pic_position') }}"
                                           placeholder="Contoh: Kepala Sekolah / Operator" required>
                                    @error('pic_position')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Nomor WhatsApp <span>*</span></label>
                                    <input type="tel" name="pic_phone" class="form-control"
                                           value="{{ old('pic_phone') }}"
                                           placeholder="Contoh: 081234567890" required>
                                    @error('pic_phone')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Alamat Email <span>*</span></label>
                                    <input type="email" name="pic_email" class="form-control"
                                           value="{{ old('pic_email') }}"
                                           placeholder="email@sekolah.sch.id" required>
                                    @error('pic_email')<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 3: Detail Pengadaan Unit -->
                        <div class="card" style="margin-bottom: 20px;">
                            <div style="margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 4px;">3. Detail Pengadaan Unit</div>
                                    <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Isi nama/seri unit laptop Axioo dan tentukan jumlah unit yang dibutuhkan.</p>
                                </div>
                                <div style="font-size: 0.82rem; font-weight: 700; color: var(--primary); background: rgba(95,138,99,0.1); padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(95,138,99,0.2);">
                                    Total Unit: <span id="grandTotalUnits">0</span> Unit
                                </div>
                            </div>

                            <!-- Opsi Pilih Kategori Pengadaan: Retail vs TKDN -->
                            <div class="procurement-category-box">
                                <div>
                                    <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 6px;">
                                        Kategori / Lisensi Pengadaan Unit:
                                    </label>
                                    <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 2px;">Pilih TKDN jika pengadaan membutuhkan unit laptop berlisensi resmi TKDN (Sekolah DAK / Instansi).</div>
                                </div>

                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="radio" name="is_tkdn" value="0" checked onchange="toggleTkdnMode(false)" style="display: none;">
                                        <span class="pill-label pill-retail">Retail (Umum)</span>
                                    </label>
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="radio" name="is_tkdn" value="1" onchange="toggleTkdnMode(true)" style="display: none;">
                                        <span class="pill-label pill-tkdn">TKDN (Pemerintah / DAK)</span>
                                    </label>
                                </div>
                            </div>

                            <style>
                                .pill-label {
                                    display: inline-block;
                                    padding: 8px 16px;
                                    border-radius: 8px;
                                    font-size: 0.82rem;
                                    font-weight: 700;
                                    border: 1px solid var(--border);
                                    background: #fff;
                                    color: var(--text-secondary);
                                    transition: all 0.2s ease;
                                    user-select: none;
                                }
                                input[name="is_tkdn"]:checked + .pill-label {
                                    background: var(--primary);
                                    color: #ffffff;
                                    border-color: var(--primary);
                                    box-shadow: 0 3px 8px rgba(95, 138, 99, 0.25);
                                }
                            </style>

                            <!-- Autocomplete Datalist Retail -->
                            <datalist id="retailLaptopModelsDatalist">
                                @if(isset($retailProducts) && count($retailProducts) > 0)
                                    @foreach($retailProducts as $rp)
                                        <option value="{{ $rp->full_name_label }}"></option>
                                    @endforeach
                                @else
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->name }} | {{ $prod->processor }} ({{ $prod->ram }}/{{ $prod->storage }})"></option>
                                    @endforeach
                                    <option value="Axioo Hype 1 | Intel Celeron N100"></option>
                                    <option value="Axioo Hype 3 | Intel Core i3-1005G1"></option>
                                    <option value="Axioo Hype 5 | Intel Core i5-1235U"></option>
                                    <option value="Axioo Hype 7 | AMD Ryzen 7 5700U"></option>
                                    <option value="Axioo Pongo 7 | Intel Core i7 / RTX 4060"></option>
                                @endif
                            </datalist>

                            <!-- Autocomplete Datalist TKDN -->
                            <datalist id="tkdnLaptopModelsDatalist">
                                @if(isset($tkdnProducts) && count($tkdnProducts) > 0)
                                    @foreach($tkdnProducts as $tp)
                                        <option value="{{ $tp->full_name_label }}"></option>
                                    @endforeach
                                @else
                                    <option value="Axioo Chromebook TKDN | Intel Celeron N4020"></option>
                                    <option value="Axioo Hype 3 TKDN | Intel Core i3-1005G1"></option>
                                    <option value="Axioo Hype 5 TKDN | Intel Core i5-1235U"></option>
                                    <option value="Axioo MyBook Pro TKDN | Intel Core i7"></option>
                                @endif
                            </datalist>

                            @if($errors->has('items'))
                            <div class="alert alert-error" style="margin-bottom: 16px;">
                                {{ $errors->first('items') }}
                            </div>
                            @endif

                            <!-- Items Multi-Row Table -->
                            <div class="table-wrap" style="margin-bottom: 16px; border: 1px solid var(--border-light); border-radius: 8px;">
                                <table style="width: 100%; border-collapse: collapse;" id="itemsTable">
                                    <thead>
                                        <tr style="background: var(--bg-alt); text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 1px solid var(--border);">
                                            <th style="padding: 10px 14px; text-align: left; width: 60%;">Nama / Model Unit Laptop <span style="color:var(--danger);">*</span></th>
                                            <th style="padding: 10px 14px; text-align: left; width: 28%;">Total Unit <span style="color:var(--danger);">*</span></th>
                                            <th style="padding: 10px 14px; text-align: center; width: 12%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTableBody">
                                        <!-- Row 1 Default -->
                                        <tr class="item-row" style="border-bottom: 1px solid var(--border-light);">
                                            <td style="padding: 10px 14px;">
                                                <input type="text" name="items[0][model]" class="form-control item-model-input"
                                                       list="retailLaptopModelsDatalist"
                                                       placeholder="Ketik seri Retail (misal: Hype 5)..."
                                                       required style="padding: 10px; font-size: 0.88rem; border-radius: 6px;">
                                            </td>
                                            <td style="padding: 10px 14px;">
                                                <input type="number" name="items[0][units]" class="form-control item-units-input"
                                                       placeholder="Jumlah unit..." min="1" max="10000" required
                                                       style="padding: 10px; font-size: 0.88rem; border-radius: 6px;"
                                                       oninput="calculateGrandTotal()">
                                            </td>
                                            <td style="padding: 10px 14px; text-align: center;">
                                                <button type="button" class="btn btn-outline remove-row-btn" onclick="removeRow(this)" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239,68,68,0.3);" title="Hapus Baris" disabled>
                                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add Row Button -->
                            <div class="procurement-add-row-wrap">
                                <style>
                                    .btn-add-unit {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 8px;
                                        font-weight: 700;
                                        font-size: 0.82rem;
                                        border-radius: 6px;
                                        padding: 10px 18px;
                                        border: 1.5px dashed var(--primary);
                                        background: var(--bg-alt);
                                        color: var(--primary) !important;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                    }
                                    .btn-add-unit:hover {
                                        background: rgba(95, 138, 99, 0.14) !important;
                                        color: var(--primary) !important;
                                        border-color: var(--primary) !important;
                                        transform: translateY(-1px);
                                    }
                                </style>
                                <button type="button" class="btn-add-unit" id="addRowBtn" onclick="addItemRow()">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    Tambah Unit Lainnya
                                </button>
                                <span style="font-size: 0.76rem; color: var(--text-muted);">Klik untuk menambahkan jenis unit laptop kedua/lainnya.</span>
                            </div>
                        </div>

                        <!-- Bagian 4: Catatan -->
                        <div class="card" style="margin-bottom: 24px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Catatan / Permintaan Khusus</label>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="Spesifikasi tambahan, preferensi warna, atau informasi lain yang perlu diketahui...">{{ old('notes') }}</textarea>
                                <span class="form-hint">Opsional. Semakin detail informasi, semakin akurat penawaran yang kami berikan.</span>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Kirim Pengajuan Pengadaan
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline">Batal</a>
                        </div>

                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 14px;">
                            Dengan mengirimkan formulir ini, Anda menyetujui bahwa tim Klinik Komputer akan menghubungi Anda melalui WhatsApp untuk informasi harga dan proses selanjutnya.
                        </p>
                    </form>
                </div>

                <!-- Sidebar Info -->
                <div class="procurement-sidebar-sticky" style="position: sticky; top: 84px;">
                    <!-- Benefit Garansi Pengadaan -->
                    <div class="card" style="margin-bottom: 16px; background: #ffffff; border: 1px solid var(--border-light); border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 12px;">Benefit Garansi Pengadaan</div>
                        <ul style="margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 10px;">
                            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.4;">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Garansi 3 Tahun Accidental Damage Protection (ADP)
                            </li>
                            <li style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.4;">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Garansi Service Klinik Komputer Up To 3 Tahun
                            </li>
                        </ul>
                    </div>

                    <!-- Process Timeline -->
                    <div class="card" style="margin-bottom: 16px;">
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 16px;">Alur Proses Pengadaan</div>
                        @php
                        $steps = [
                            ['num'=>'1','title'=>'Isi Formulir','desc'=>'Lengkapi data institusi dan spesifikasi unit.'],
                            ['num'=>'2','title'=>'Konfirmasi Tim','desc'=>'Tim kami menghubungi via WhatsApp dalam 1x24 jam.'],
                            ['num'=>'3','title'=>'Penawaran Harga','desc'=>'Kami kirim surat penawaran resmi.'],
                            ['num'=>'4','title'=>'Pembayaran','desc'=>'Selesaikan pembayaran sesuai kesepakatan.'],
                            ['num'=>'5','title'=>'Pengiriman Unit','desc'=>'Unit dikirim ke alamat institusi Anda.'],
                        ];
                        @endphp
                        @foreach($steps as $step)
                        <div style="display: flex; gap: 12px; margin-bottom: {{ $loop->last ? '0' : '14px' }}; align-items: flex-start;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $loop->first ? 'var(--primary)' : 'var(--bg-alt)' }}; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; color: {{ $loop->first ? '#fff' : 'var(--text-muted)' }}; flex-shrink: 0;">{{ $step['num'] }}</div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.03em;">{{ $step['title'] }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1px;">{{ $step['desc'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Contact Info -->
                    <div class="card" style="background: var(--bg-alt);">
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 12px;">Pertanyaan Cepat?</div>
                        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 12px;">Hubungi langsung tim pengadaan kami untuk konsultasi lebih lanjut.</p>
                        <a href="https://wa.me/6285103051000?text=Halo, saya ingin bertanya mengenai pengadaan laptop Axioo untuk sekolah"
                           target="_blank" class="btn btn-primary btn-block" style="font-size: 0.78rem;">
                            Hubungi via WhatsApp
                        </a>
                        <div style="margin-top: 10px; font-size: 0.75rem; color: var(--text-muted); text-align: center;">
                            Senin – Jumat: 09.00 – 17.00 WIB <br> Sabtu: 09.00 – 14.00 WIB
                        </div>
                    </div>

                    <!-- Available Products (Dynamically filters Retail vs TKDN) -->
                    <div class="card" style="margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary);">Produk Tersedia</div>
                            <span id="availableBadge" class="badge badge-primary" style="font-size: 0.6rem; text-transform: uppercase;">RETAIL</span>
                        </div>
                        
                        {{-- List Retail --}}
                        <div id="retailAvailableList">
                            @if(isset($retailProducts) && count($retailProducts) > 0)
                                @foreach($retailProducts->take(5) as $rp)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid var(--border-light); font-size: 0.8rem;">
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $rp->product_name }}</div>
                                        <div style="color: var(--text-muted); font-size: 0.72rem;">{{ $rp->cpu }} &bull; {{ $rp->ram }} / {{ $rp->storage }}</div>
                                    </div>
                                    <span class="badge badge-success" style="font-size: 0.6rem;">READY</span>
                                </div>
                                @endforeach
                            @else
                                @foreach($products->take(4) as $prod)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid var(--border-light); font-size: 0.8rem;">
                                    <div>
                                        <div style="font-weight: 600;">{{ $prod->name }}</div>
                                        <div style="color: var(--text-muted); font-size: 0.72rem;">{{ $prod->ram }} · {{ $prod->storage }}</div>
                                    </div>
                                    <span class="badge {{ $prod->series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size: 0.62rem;">{{ $prod->series_label }}</span>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- List TKDN --}}
                        <div id="tkdnAvailableList" style="display: none;">
                            @if(isset($tkdnProducts) && count($tkdnProducts) > 0)
                                @foreach($tkdnProducts->take(5) as $tp)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid var(--border-light); font-size: 0.8rem;">
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $tp->product_name }}</div>
                                        <div style="color: var(--text-muted); font-size: 0.72rem;">{{ $tp->cpu }} &bull; {{ $tp->ram }} / {{ $tp->storage }}</div>
                                    </div>
                                    <span class="badge badge-success" style="font-size: 0.6rem;">TKDN</span>
                                </div>
                                @endforeach
                            @else
                                <div style="font-size: 0.75rem; color: var(--text-muted); padding: 8px 0;">Unit TKDN tersedia via katalog resmi Axioo.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════
         TRACKING SECTION
    ══════════════════════════════════════════════════ --}}
    <section class="section" id="tracking" style="background: var(--bg-alt); border-top: 1px solid var(--border);">
        <div class="section-inner" style="max-width: 680px;">

            <div style="text-align:center; margin-bottom: 28px;">
                <div class="label-line" style="justify-content:center;">Sudah Mengajukan?</div>
                <h2 style="font-size:1.4rem; margin-bottom:8px;">Cek Status Pengadaan</h2>
                <p style="color:var(--text-secondary); font-size:0.88rem;">Masukkan nomor referensi yang diterima setelah pengajuan untuk melihat perkembangan terkini.</p>
            </div>

            {{-- Search Form --}}
            <form action="{{ route('procurement.index') }}" method="GET" style="display:flex; gap:10px; margin-bottom:24px;">
                <input type="text"
                       name="track"
                       class="form-control"
                       value="{{ request('track', isset($trackedOrder) ? $trackedOrder->order_number : '') }}"
                       placeholder="Contoh: PRC-20260716-0001"
                       style="font-family:monospace; font-size:0.95rem; letter-spacing:1px; text-transform:uppercase; flex:1;"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary" style="white-space:nowrap;">Cek Status</button>
            </form>

            {{-- Error --}}
            @if(isset($trackError))
            <div class="alert alert-error" style="margin-bottom:16px;">{{ $trackError }}</div>
            @endif

            {{-- ══ HASIL TRACKING ══ --}}
            @isset($trackedOrder)
            @php
            $o = $trackedOrder;
            $statusOrder = ['pending','diproses','siap_kirim','diproses_pengiriman','selesai'];
            $allSteps = [
                ['val'=>'pending',            'label'=>"Menunggu\nKonfirmasi"],
                ['val'=>'diproses',           'label'=>"Unit\nDiproses"],
                ['val'=>'siap_kirim',         'label'=>"Siap\nDikirim"],
                ['val'=>'diproses_pengiriman','label'=>"Unit\nDikirim"],
                ['val'=>'selesai',            'label'=>"Selesai"],
            ];
            $currentIdx  = array_search($o->status, $statusOrder);
            $isCancelled = $o->status === 'dibatalkan';
            @endphp

            {{-- Status Card --}}
            <div class="card" style="margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:20px;">
                    <div>
                        <div style="font-family:monospace; font-weight:800; font-size:1rem; color:var(--primary);">{{ $o->order_number }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $o->school_name }} &bull; {{ $o->created_at->format('d F Y') }}</div>
                    </div>
                    <span class="badge badge-{{ $o->status_color }}" style="padding:8px 16px; font-size:0.78rem;">{{ $o->status_label }}</span>
                </div>

                {{-- Progress Stepper --}}
                @if($isCancelled)
                <div style="text-align:center; padding:14px; background:rgba(209,79,79,0.05); border:1px solid rgba(209,79,79,0.15); border-radius:6px; font-size:0.85rem; color:var(--danger); font-weight:600;">
                    Pengajuan Dibatalkan. Hubungi Klinik Komputer untuk informasi lebih lanjut.
                </div>
                @else
                <div style="display:flex; align-items:center; overflow-x:auto; padding-bottom:4px;">
                    @foreach($allSteps as $i => $step)
                    @php
                        $si      = array_search($step['val'], $statusOrder);
                        $isDone  = $currentIdx !== false && $si < $currentIdx;
                        $isCur   = $o->status === $step['val'];
                    @endphp
                    <div style="display:flex; align-items:center; flex:1; min-width:64px;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:5px; flex:1;">
                            <div style="width:32px; height:32px; border-radius:50%;
                                border:2px solid {{ $isCur ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--border)') }};
                                background:{{ $isCur ? 'var(--primary)' : ($isDone ? 'var(--success)' : '#fff') }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:0.7rem; font-weight:800;
                                color:{{ ($isCur || $isDone) ? '#fff' : 'var(--text-muted)' }};
                                box-shadow:{{ $isCur ? '0 0 0 4px var(--primary-glow)' : 'none' }};">
                                {{ $isDone ? '✓' : ($i + 1) }}
                            </div>
                            <div style="font-size:0.62rem; font-weight:{{ $isCur ? '700' : '500' }};
                                color:{{ $isCur ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--text-muted)') }};
                                text-align:center; white-space:pre-line; line-height:1.2;">{{ $step['label'] }}</div>
                        </div>
                        @if(!$loop->last)
                        <div style="height:2px; flex:1; min-width:10px; background:{{ ($currentIdx !== false && $i < $currentIdx) ? 'var(--success)' : 'var(--border)' }}; margin-bottom:18px;"></div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Sub Bab "Menunggu Konfirmasi" Tracker (Hanya muncul saat status Menunggu Konfirmasi) --}}
                @if($o->status === 'pending')
                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--border-light);">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary);">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Sub-Tahap: Menunggu Konfirmasi
                        </span>
                        <span style="font-size: 0.65rem; color: var(--primary); font-weight: 700; background: rgba(22,163,74,0.08); padding: 2px 8px; border-radius: 99px;">Detail Proses</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                        {{-- Sub 1: Penawaran --}}
                        <div style="background: {{ $o->substep_penawaran_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $o->substep_penawaran_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 8px; padding: 10px 6px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: {{ $o->substep_penawaran_active ? '#16a34a' : '#ffffff' }}; color: {{ $o->substep_penawaran_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $o->substep_penawaran_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px auto; font-size: 0.7rem; font-weight: 800;">
                                @if($o->substep_penawaran_active) ✓ @else 1 @endif
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $o->substep_penawaran_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Penawaran</div>
                            <div style="font-size: 0.65rem; color: {{ $o->substep_penawaran_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 2px;">
                                {{ $o->substep_penawaran_active ? '✓ Selesai' : 'Sedang Diproses' }}
                            </div>
                        </div>

                        {{-- Sub 2: Proses Invoice --}}
                        <div style="background: {{ $o->substep_invoice_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $o->substep_invoice_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 8px; padding: 10px 6px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: {{ $o->substep_invoice_active ? '#16a34a' : '#ffffff' }}; color: {{ $o->substep_invoice_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $o->substep_invoice_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px auto; font-size: 0.7rem; font-weight: 800;">
                                @if($o->substep_invoice_active) ✓ @else 2 @endif
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $o->substep_invoice_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Proses Invoice</div>
                            <div style="font-size: 0.65rem; color: {{ $o->substep_invoice_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 2px;">
                                {{ $o->substep_invoice_active ? '✓ Diterbitkan' : 'Menunggu Invoice' }}
                            </div>
                        </div>

                        {{-- Sub 3: Pembayaran --}}
                        <div style="background: {{ $o->substep_pembayaran_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $o->substep_pembayaran_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 8px; padding: 10px 6px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: {{ $o->substep_pembayaran_active ? '#16a34a' : '#ffffff' }}; color: {{ $o->substep_pembayaran_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $o->substep_pembayaran_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 4px auto; font-size: 0.7rem; font-weight: 800;">
                                @if($o->substep_pembayaran_active) ✓ @else 3 @endif
                            </div>
                            <div style="font-size: 0.75rem; font-weight: 700; color: {{ $o->substep_pembayaran_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Pembayaran</div>
                            <div style="font-size: 0.65rem; color: {{ $o->substep_pembayaran_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 2px;">
                                {{ $o->substep_pembayaran_active ? '✓ Lunas' : 'Menunggu Pelunasan' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>

            {{-- Ringkasan --}}
            <div class="card" style="margin-bottom:16px;">
                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:12px;">Detail Pengajuan</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:0.82rem;">
                    <div><span style="color:var(--text-muted);">Laptop:</span> <strong>{{ $o->axioo_model }}</strong></div>
                    <div><span style="color:var(--text-muted);">Jumlah:</span> <strong style="color:var(--primary);">{{ number_format($o->total_units) }} unit</strong></div>
                    <div><span style="color:var(--text-muted);">PIC:</span> {{ $o->pic_name }}</div>
                    <div><span style="color:var(--text-muted);">Tujuan:</span> {{ $o->usage_purpose_label }}</div>
                    @if($o->quoted_price)
                    <div style="grid-column:1/-1;"><span style="color:var(--text-muted);">Harga Penawaran:</span> <strong style="color:var(--accent); font-size:1rem;">{{ $o->formatted_quoted_price }}</strong></div>
                    @endif
                </div>
            </div>

            {{-- Pesan kontekstual --}}
            @php
            $msg = match($o->status) {
                'pending'            => ['type'=>'warning', 'head'=>'Menunggu Dihubungi', 'body'=>'Tim CS Klinik Komputer akan menghubungi ' . $o->pic_name . ' via WhatsApp ' . $o->pic_phone . ' dalam 1×24 jam kerja.'],
                'diproses'           => ['type'=>'info',    'head'=>'Unit Sedang Diproses', 'body'=>'Tim kami sedang memproses ketersediaan unit. Kami akan segera menghubungi Anda.'],
                'siap_kirim'         => ['type'=>'warning', 'head'=>'Unit Siap Dikirim!', 'body'=>'Unit laptop sudah siap. Tim kami akan menghubungi Anda untuk koordinasi jadwal pengiriman ke ' . $o->school_city . '.'],
                'diproses_pengiriman'=> ['type'=>'info',    'head'=>'Unit Dalam Perjalanan', 'body'=>'Unit laptop sedang dalam pengiriman ke ' . $o->school_name . '. Harap siapkan penerima di lokasi.'],
                'selesai'            => ['type'=>'success', 'head'=>'Pengadaan Selesai', 'body'=>'Terima kasih! Pengadaan laptop untuk ' . $o->school_name . ' telah berhasil diselesaikan.'],
                'dibatalkan'         => ['type'=>'error',   'head'=>'Pengajuan Dibatalkan', 'body'=>'Hubungi Klinik Komputer untuk informasi lebih lanjut atau ajukan ulang.'],
                default              => ['type'=>'info',    'head'=>'Sedang Diproses', 'body'=>'Pengajuan Anda sedang ditangani oleh tim kami.'],
            };
            @endphp
            <div class="alert alert-{{ $msg['type'] }}" style="margin-bottom:14px;">
                <div>
                    <div style="font-weight:700; font-size:0.78rem; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:3px;">{{ $msg['head'] }}</div>
                    <div style="font-size:0.85rem; line-height:1.6;">{{ $msg['body'] }}</div>
                </div>
            </div>

            <div style="text-align:center;">
                <a href="https://wa.me/6285103051000?text={{ urlencode('Halo Klinik Komputer, saya ingin menanyakan status pengajuan pengadaan nomor ' . $o->order_number . ' dari ' . $o->school_name) }}"
                   target="_blank" class="btn btn-primary btn-sm">Tanya via WhatsApp</a>
            </div>
            @endisset

            @if(!isset($trackedOrder) && !isset($trackError))
            <div style="text-align:center; color:var(--text-muted); font-size:0.82rem; padding:8px 0;">
                Nomor referensi berbentuk <span style="font-family:monospace; background:var(--border-light); padding:2px 8px; border-radius:3px;">PRC-YYYYMMDD-XXXX</span> (dikirim via email setelah pengajuan).
            </div>
            @endif

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    let itemRowIndex = 1;
    let currentDatalistId = 'retailLaptopModelsDatalist';
    let currentPlaceholder = 'Ketik seri Retail (misal: Hype 5)...';

    function toggleTkdnMode(isTkdn) {
        const retailList  = document.getElementById('retailAvailableList');
        const tkdnList    = document.getElementById('tkdnAvailableList');
        const badge       = document.getElementById('availableBadge');

        if (isTkdn) {
            currentDatalistId  = 'tkdnLaptopModelsDatalist';
            currentPlaceholder = 'Ketik seri TKDN (misal: Chromebook)...';
            if (retailList) retailList.style.display = 'none';
            if (tkdnList)   tkdnList.style.display   = 'block';
            if (badge) {
                badge.innerText = 'TKDN';
                badge.className = 'badge badge-success';
            }
        } else {
            currentDatalistId  = 'retailLaptopModelsDatalist';
            currentPlaceholder = 'Ketik seri Retail (misal: Hype 5)...';
            if (retailList) retailList.style.display = 'block';
            if (tkdnList)   tkdnList.style.display   = 'none';
            if (badge) {
                badge.innerText = 'RETAIL';
                badge.className = 'badge badge-primary';
            }
        }

        const modelInputs = document.querySelectorAll('.item-model-input');
        modelInputs.forEach(input => {
            input.setAttribute('list', currentDatalistId);
            input.placeholder = currentPlaceholder;
        });
    }

    function addItemRow() {
        const tbody = document.getElementById('itemsTableBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.style.borderBottom = '1px solid var(--border-light)';
        tr.innerHTML = `
            <td style="padding: 10px 14px;">
                <input type="text" name="items[${itemRowIndex}][model]" class="form-control item-model-input"
                       list="${currentDatalistId}"
                       placeholder="${currentPlaceholder}"
                       required style="padding: 10px; font-size: 0.88rem; border-radius: 6px;">
            </td>
            <td style="padding: 10px 14px;">
                <input type="number" name="items[${itemRowIndex}][units]" class="form-control item-units-input"
                       placeholder="Contoh: 10" min="1" max="10000" required
                       style="padding: 10px; font-size: 0.88rem; border-radius: 6px;"
                       oninput="calculateGrandTotal()">
            </td>
            <td style="padding: 10px 14px; text-align: center;">
                <button type="button" class="btn btn-outline remove-row-btn" onclick="removeRow(this)" style="padding: 8px 10px; color: var(--danger); border-color: rgba(239,68,68,0.3);" title="Hapus Baris">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        itemRowIndex++;
        updateRemoveButtons();
        calculateGrandTotal();
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        const tbody = document.getElementById('itemsTableBody');
        if (tbody.querySelectorAll('tr').length > 1) {
            row.remove();
            updateRemoveButtons();
            calculateGrandTotal();
        }
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#itemsTableBody tr');
        const buttons = document.querySelectorAll('.remove-row-btn');
        buttons.forEach(btn => {
            btn.disabled = (rows.length <= 1);
        });
    }

    function calculateGrandTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('.item-units-input');
        inputs.forEach(input => {
            const val = parseInt(input.value) || 0;
            total += val;
        });
        const badge = document.getElementById('grandTotalUnits');
        if (badge) badge.innerText = total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateGrandTotal();

        // Auto-scroll to tracking result if present
        @if(isset($trackedOrder) || isset($trackError))
        const el = document.getElementById('tracking');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        @endif
    });
</script>
@endpush
