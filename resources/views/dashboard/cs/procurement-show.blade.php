@extends('layouts.dashboard')
@section('title', 'Detail Pengadaan — CS')
@section('page_title', 'Detail Pengadaan')
@section('page_subtitle', $order->order_number . ' | ' . $order->school_name)

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')
<div style="max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('dashboard.cs') }}#procurement-section" class="btn btn-outline btn-sm">Kembali ke Dashboard</a>
        @if($order->kits->count() > 0)
        <a href="{{ route('procurement.export-kits', $order) }}" class="btn-export">
            EXPORT
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom: 16px;">{{ $errors->first() }}</div>
    @endif

    {{-- Header --}}
    <div class="dash-card" style="margin-bottom: 20px; border-left: 4px solid var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px;">
            <div>
                <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Nomor Pengadaan</span>
                <div style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--primary); letter-spacing: 0.5px;">{{ $order->order_number }}</div>
                <div style="font-size: 0.76rem; color: var(--text-muted); margin-top: 4px;">
                    Diterima {{ $order->created_at->format('d F Y, H:i') }} &bull; <span style="font-weight: 600; color: var(--text-secondary);">{{ $order->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <span class="badge badge-{{ $order->status_color }}" style="padding: 8px 18px; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; border-radius: 20px;">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    {{-- ── KONTAK PIC ── --}}
    <div class="dash-card" style="margin-bottom: 20px; border: 1px solid rgba(34, 197, 94, 0.15); background: linear-gradient(180deg, rgba(34, 197, 94, 0.01) 0%, rgba(34, 197, 94, 0.03) 100%);">
        <div style="padding: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div style="flex: 1; min-width: 280px;">
                <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #16a34a; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #16a34a; display: inline-block;"></span>
                    Kontak PIC Sekolah
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 4px 0;">{{ $order->pic_name }}</h3>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 16px 0; font-weight: 500;">
                    {{ $order->pic_position }} &bull; <span style="color: var(--text-secondary); font-weight: 600;">{{ $order->school_name }}</span>
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 0.85rem; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 14px;">
                    <div>
                        <div style="color: var(--text-muted); font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.02em;">Nomor WhatsApp</div>
                        <a href="https://wa.me/62{{ ltrim($order->pic_phone, '0') }}" target="_blank" style="font-size: 0.95rem; color: var(--text-primary); font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                            {{ $order->pic_phone }}
                        </a>
                    </div>
                    <div>
                        <div style="color: var(--text-muted); font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.02em;">Alamat Email</div>
                        <a href="mailto:{{ $order->pic_email }}" style="font-size: 0.9rem; color: var(--primary); font-weight: 600; text-decoration: none; display: inline-block; margin-top: 2px;">
                            {{ $order->pic_email }}
                        </a>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 10px; min-width: 220px; width: 100%; max-width: 260px;">
                @php
                $waText = 'Halo ' . $order->pic_name . ', saya ' . auth()->user()->name . ' dari Klinik Komputer Bandung. Kami menghubungi terkait pengajuan pengadaan laptop Axioo dengan nomor referensi *' . $order->order_number . '* dari ' . $order->school_name . '.' . "\n\n" . 'Berikut ringkasan pengajuan Anda:' . "\n" . '- Laptop: ' . $order->axioo_model . "\n" . '- Jumlah: ' . $order->total_units . ' unit' . "\n\n" . 'Mohon dikonfirmasi apakah pengajuan ini benar dari Anda? Kami akan segera tindak lanjuti. Terima kasih.';
                @endphp
                <a href="https://wa.me/62{{ ltrim($order->pic_phone, '0') }}?text={{ urlencode($waText) }}"
                   target="_blank"
                   class="btn"
                   style="background: #25D366; border: 1px solid #25D366; color: #fff; text-align: center; font-weight: 700; padding: 10px 16px; border-radius: 6px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.82rem; transition: all 0.2s ease;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Hubungi via WhatsApp
                </a>

                @php
                $emailSubject = 'Konfirmasi Pengajuan Pengadaan Laptop Axioo — ' . $order->order_number;
                $emailBody  = 'Yth. ' . $order->pic_name . ',' . "\n" . $order->pic_position . ' — ' . $order->school_name . "\n\n" . 'Saya ' . auth()->user()->name . ' dari Klinik Komputer Bandung.' . "\n\n" . 'Kami menerima pengajuan pengadaan laptop dari institusi Anda dengan detail sebagai berikut:' . "\n\n" . '  No. Referensi : ' . $order->order_number . "\n" . '  Institusi     : ' . $order->school_name . ' (' . $order->school_type_label . ')' . "\n" . '  Kota          : ' . $order->school_city . "\n" . '  Laptop        : ' . $order->axioo_model . "\n" . '  Jumlah        : ' . $order->total_units . ' unit' . "\n" . '  Tujuan        : ' . $order->usage_purpose_label . "\n\n" . 'Mohon konfirmasi apakah pengajuan ini benar dari Anda, agar kami dapat segera memproses lebih lanjut dan menyiapkan surat penawaran harga resmi.' . "\n\n" . 'Apabila ada pertanyaan, Anda dapat menghubungi kami melalui:' . "\n" . '  WhatsApp : 085103051000' . "\n" . '  Alamat   : Komplek Ruko Segitiga Emas Kosambi, Jl. A. Yani Blok E8, Kota Bandung' . "\n\n" . 'Terima kasih atas kepercayaan Anda.' . "\n\n" . 'Hormat kami,' . "\n" . auth()->user()->name . "\n" . 'Customer Service — Klinik Komputer Bandung';
                @endphp
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ urlencode($order->pic_email) }}&su={{ urlencode($emailSubject) }}&body={{ urlencode($emailBody) }}"
                   target="_blank"
                   class="btn"
                   style="background: #fff; border: 1px solid var(--border); color: var(--text-primary); text-align: center; font-weight: 700; padding: 10px 16px; border-radius: 6px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.82rem; transition: all 0.2s ease;">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Kirim via Gmail
                </a>
            </div>
        </div>
    </div>

    {{-- ── UPDATE STATUS oleh CS ── --}}
    <div class="dash-card" style="margin-bottom: 20px; border-color: var(--primary);">
        <div class="dash-card-header">
            <h3 style="color: var(--primary); margin:0; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Update Status Pengadaan</h3>
        </div>
        <div class="dash-card-body" style="padding: 24px;">
            {{-- Progress Bar --}}
            @if($order->status === 'dibatalkan')
                <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; margin-bottom: 24px; color: #dc2626;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <div>
                        <strong style="font-size: 0.9rem; display: block; margin-bottom: 2px;">Pengadaan Dibatalkan</strong>
                        <span style="font-size: 0.8rem; opacity: 0.85;">Status pengajuan pengadaan laptop ini telah diperbarui menjadi dibatalkan. Data tetap disimpan di database untuk kebutuhan rekap dan evaluasi kuartal.</span>
                    </div>
                </div>
            @else
                @php
                $csStatuses = ['pending','diproses','siap_kirim','diproses_pengiriman'];
                $currentIdx = array_search($order->status, $csStatuses);
                @endphp
                <div style="display:flex; align-items:center; gap:0; margin-bottom:24px; overflow-x:auto; padding-bottom:8px;">
                    @php
                    $steps = [
                        'pending'             => "Menunggu\nKonfirmasi",
                        'diproses'            => "Unit\nDiproses",
                        'siap_kirim'          => "Siap\nDikirim",
                        'diproses_pengiriman' => "Unit\nDikirim",
                    ];
                    @endphp
                    @foreach($steps as $val => $label)
                    @php
                        $stepIdx = array_search($val, $csStatuses);
                        $isDone    = $currentIdx !== false && $stepIdx < $currentIdx;
                        $isCurrent = $order->status === $val;
                    @endphp
                    <div style="display:flex; align-items:center; flex:1; min-width:100px;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:6px; flex:1;">
                            <div style="width:34px; height:34px; border-radius:50%; border:2px solid {{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--border)') }}; background:{{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : '#fff') }}; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; color:{{ ($isCurrent || $isDone) ? '#fff' : 'var(--text-muted)' }}; box-shadow: {{ $isCurrent ? '0 0 0 3px rgba(95,138,99,0.15)' : 'none' }};">
                                @if($isDone) ✓ @else {{ $loop->iteration }} @endif
                            </div>
                            <div style="font-size:0.68rem; font-weight:{{ $isCurrent ? '700' : '600' }}; color:{{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--text-muted)') }}; text-align:center; white-space:pre-line; line-height:1.25;">{{ $label }}</div>
                        </div>
                        @if(!$loop->last)
                        <div style="height:2px; flex:1; background:{{ ($currentIdx !== false && $stepIdx < $currentIdx) ? 'var(--success)' : 'var(--border)' }}; margin-bottom:18px;"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif

            {{-- Form Update --}}
            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 16px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); color: #dc2626; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('dashboard.cs.procurement.update', $order) }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.72rem; color: var(--text-secondary); text-transform: uppercase;">Status Baru <span>*</span></label>
                        <select name="status" class="form-control" required style="padding: 10px 12px; border-radius: 6px;">
                            <option value="pending"             {{ $order->status==='pending'             ? 'selected':'' }}>Menunggu Konfirmasi</option>
                            <option value="diproses"            {{ $order->status==='diproses'            ? 'selected':'' }}>Unit Diproses</option>
                            <option value="siap_kirim"          {{ $order->status==='siap_kirim'          ? 'selected':'' }}>Unit Siap Dikirim</option>
                            <option value="diproses_pengiriman" {{ $order->status==='diproses_pengiriman' ? 'selected':'' }}>Unit Dikirim</option>
                            <option value="dibatalkan"          {{ $order->status==='dibatalkan'          ? 'selected':'' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.72rem; color: var(--text-secondary); text-transform: uppercase;">Catatan Internal (opsional)</label>
                        <input type="text" name="cs_notes" class="form-control"
                               placeholder="Contoh: Sudah dihubungi, unit siap minggu depan" style="padding: 10px 12px; border-radius: 6px;">
                    </div>
                </div>
                <div style="margin-bottom: 16px; background: var(--bg-alt); border: 1px solid var(--border-light); border-radius: 8px; padding: 12px 14px;">
                    <label style="font-weight: 700; font-size: 0.72rem; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 8px;">Sub-Tahap Unit Diproses:</label>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_penawaran" value="1" {{ $order->substep_penawaran_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            📄 1. Penawaran
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_invoice" value="1" {{ $order->substep_invoice_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            🧾 2. Proses Invoice
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_pembayaran" value="1" {{ $order->substep_pembayaran_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            💳 3. Pembayaran
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 700; font-size: 0.8rem; border-radius: 6px;">Simpan Perubahan Status</button>
            </form>
        </div>
    </div>

    {{-- ── INFO SEKOLAH + PENGADAAN ── --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="dash-card" style="box-shadow: 0 4px 10px rgba(0,0,0,0.02); height: 100%;">
            <div class="dash-card-header">
                <h3 style="margin:0; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Informasi Institusi</h3>
            </div>
            <div class="dash-card-body" style="padding: 20px 24px 24px;">
                <div style="display:flex; flex-direction:column; gap:12px; font-size:0.9rem; line-height: 1.5;">
                    <div><span style="color:var(--text-muted); width: 75px; display: inline-block;">Nama:</span> <strong style="color: var(--text-primary);">{{ $order->school_name }}</strong></div>
                    <div><span style="color:var(--text-muted); width: 75px; display: inline-block;">Jenis:</span> <span style="font-weight: 600; color: var(--text-secondary);">{{ $order->school_type_label }}</span></div>
                    <div><span style="color:var(--text-muted); width: 75px; display: inline-block;">Kota:</span> <span style="font-weight: 600; color: var(--text-secondary);">{{ $order->school_city }}</span></div>
                    <div><span style="color:var(--text-muted); width: 75px; display: inline-block; vertical-align: top;">Alamat:</span> <span style="color:var(--text-secondary); display: inline-block; width: calc(100% - 85px); line-height: 1.5;">{{ $order->school_address }}</span></div>
                </div>
            </div>
        </div>
        
        <div class="dash-card" style="box-shadow: 0 4px 10px rgba(0,0,0,0.02); height: 100%;">
            <div class="dash-card-header">
                <h3 style="margin:0; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Detail Pengadaan</h3>
            </div>
            <div class="dash-card-body" style="padding: 20px 24px 24px;">
                <div style="display:flex; flex-direction:column; gap:12px; font-size:0.9rem; line-height: 1.5;">
                    <div style="display: flex; align-items: center;">
                        <span style="color:var(--text-muted); width: 85px; display: inline-block;">Seri Laptop:</span>
                        <span class="badge {{ $order->axioo_series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size:0.65rem; border-radius: 4px; padding: 4px 8px; font-weight: 700; text-transform: uppercase;">{{ $order->axioo_series }}</span>
                    </div>
                    <div><span style="color:var(--text-muted); width: 85px; display: inline-block;">Model:</span> <strong style="color: var(--text-primary);">{{ $order->axioo_model }}</strong></div>
                    <div>
                        <span style="color:var(--text-muted); width: 85px; display: inline-block;">Jumlah:</span>
                        <strong style="color:var(--primary); font-size:1.1rem;">{{ number_format($order->total_units) }} unit</strong>
                    </div>
                    <div><span style="color:var(--text-muted); width: 85px; display: inline-block;">Tujuan:</span> <span style="font-weight: 600; color: var(--text-secondary);">{{ $order->usage_purpose_label }}</span></div>
                    @if($order->quoted_price)
                    <div><span style="color:var(--text-muted); width: 85px; display: inline-block;">Penawaran:</span> <strong style="color:var(--accent); font-size: 1.05rem;">{{ $order->formatted_quoted_price }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── CATATAN ── --}}
    @if($order->notes || $order->admin_notes)
    <div class="dash-card" style="margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
        <div class="dash-card-header">
            <h3 style="margin:0; font-size: 0.85rem; font-weight: 700; text-transform: uppercase;">Catatan & Log Aktivitas</h3>
        </div>
        <div class="dash-card-body" style="padding: 24px;">
            @if($order->notes)
            <div style="margin-bottom:20px;">
                <div style="font-size:0.75rem; color:var(--text-secondary); font-weight:700; margin-bottom:8px; text-transform: uppercase;">Catatan Dari Sekolah:</div>
                <p style="font-size:0.9rem; color:var(--text-primary); line-height:1.6; background:var(--bg-alt); padding:14px 16px; border-radius:6px; border-left: 3px solid var(--primary); margin:0;">{{ $order->notes }}</p>
            </div>
            @endif
            @if($order->admin_notes)
            <div>
                <div style="font-size:0.75rem; color:var(--primary); font-weight:700; margin-bottom:8px; text-transform: uppercase;">Log Aktivitas / Riwayat CS & Admin:</div>
                <div style="font-size:0.88rem; color:var(--text-secondary); line-height:1.8; background:var(--bg-alt); padding:14px 16px; border-radius:6px; border-left: 3px solid var(--accent); white-space:pre-line; margin:0;">{{ $order->admin_notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── PANDUAN CS ── --}}
    <div class="dash-card" style="background:var(--bg-alt); border: 1px dashed var(--border); border-radius: 8px; margin-bottom: 20px; box-shadow: none; padding: 24px;">
        <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--primary); margin-bottom:16px; display: flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            Panduan Alur Tindak Lanjut CS
        </div>
        <ol style="font-size:0.85rem; color:var(--text-secondary); line-height:1.8; padding-left:18px; margin:0; display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px;">
            <li><strong>Menunggu Konfirmasi</strong> — Hubungi PIC via WA/Gmail untuk memverifikasi pesanan.</li>
            <li><strong>Unit Diproses</strong> — Perbarui status setelah harga disepakati & perakitan dimulai.</li>
            <li><strong>Unit Siap Dikirim</strong> — Hubungi PIC untuk konfirmasi jadwal & alamat pengiriman.</li>
            <li><strong>Unit Dikirim</strong> — Perbarui setelah barang diserahterimakan ke sekolah.</li>
        </ol>
    </div>

    {{-- ── DAFTAR KIT LAPTOP & AKTIVASI SISWA ── --}}
    @if($order->kits->count() > 0)
    <div class="dash-card" style="margin-top: 16px;">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h3 style="color: var(--primary); margin: 0;">Daftar Kit Laptop & Aktivasi Siswa ({{ $order->kits->count() }} unit)</h3>
            <a href="{{ route('procurement.export-kits', $order) }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 700; border-radius: 6px; padding: 6px 12px;">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export Excel
            </a>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            <div style="max-height: 400px; overflow-y: auto; border: 1px solid var(--border-light); border-radius: 6px; margin: 0 16px 16px;">
                <div class="table-wrap" style="border: none; border-radius: 0; margin: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-light); background: var(--bg-alt); position: sticky; top: 0; z-index: 1;">
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">ID Member / Owner</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Detail Komponen (SN)</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Status Aktivasi</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Tanggal Aktivasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->kits->reverse() as $kit)
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 16px 20px;">
                                    <div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.95rem; letter-spacing: 0.5px;">{{ $kit->member_id }}</div>
                                    @if($kit->status === 'activated')
                                    <div style="margin-top: 8px; font-size: 0.85rem; line-height: 1.45;">
                                        <strong style="color: var(--text-primary);">{{ $kit->student_name }}</strong>
                                        <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 4px;">
                                            Telp: {{ optional($kit->customer)->phone ?? '—' }} <br>
                                            Email: {{ optional($kit->customer)->email ?? '—' }}
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td style="padding: 16px 20px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; max-width: 480px;">
                                    @foreach(['Motherboard' => 'M', 'RAM' => 'R', 'SSD' => 'S', 'Screen' => 'L', 'Battery' => 'B'] as $fullName => $shortName)
                                        @php $comp = $kit->components->where('component_name', $fullName)->first(); @endphp
                                        <span style="font-family: monospace; font-size: 0.78rem; padding: 5px 9px; background: var(--bg-alt); border: 1px solid var(--border-light); border-radius: 4px; white-space: nowrap;" title="{{ $fullName }}">
                                            <span style="color: var(--text-muted); font-weight: 600; margin-right: 3px;">{{ $shortName }}:</span>{{ $comp ? $comp->serial_number : '—' }}
                                        </span>
                                    @endforeach
                                    </div>
                                </td>
                                <td style="padding: 16px 20px;">
                                    <span class="badge badge-{{ $kit->status === 'activated' ? 'success' : 'secondary' }}" style="font-size: 0.75rem; padding: 4px 8px;">
                                        {{ $kit->status === 'activated' ? 'Aktif' : 'Belum Diaktivasi' }}
                                    </span>
                                </td>
                                <td style="padding: 16px 20px; font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $kit->status === 'activated' && $kit->updated_at ? $kit->updated_at->format('d M Y, H:i') : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
