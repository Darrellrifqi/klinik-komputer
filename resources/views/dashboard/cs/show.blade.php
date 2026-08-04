@extends('layouts.dashboard')
@section('title', 'Detail Tiket | CS')
@section('page_title', 'Detail Tiket')
@section('page_subtitle', $ticket->ticket_number)

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')
<div style="max-width:800px;">
    <div style="display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
        <a href="{{ route('dashboard.cs') }}" class="btn btn-outline btn-sm">Kembali</a>
        <div style="display:flex; gap:8px; align-items:center;">
            <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}"
               target="_blank" class="btn btn-outline btn-sm">Lihat Tracking Publik</a>
            <form action="{{ route('dashboard.cs.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket {{ $ticket->ticket_number }} ini secara permanen dari database?');" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 12px; font-weight:700;">Hapus Tiket</button>
            </form>
        </div>
    </div>

    <!-- Ticket Header -->
    <div class="dash-card" style="margin-bottom:16px;">
        <div class="dash-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-family:monospace; font-size:1.1rem; font-weight:800; color:var(--primary);">{{ $ticket->ticket_number }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">
                    Antrian #{{ $ticket->queue_number }} &bull; Dibuat {{ $ticket->created_at->format('d M Y, H:i') }}
                    @if($ticket->airtable_service_number)
                        &bull; <span style="color:var(--primary); font-weight:700; font-family:monospace;">Airtable: {{ $ticket->airtable_service_number }}</span>
                    @endif
                </div>
            </div>
            <div style="text-align:right;">
                <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.78rem; padding:8px 14px;">
                    {{ $ticket->full_status_label }}
                </span>
            </div>
        </div>
        <div class="dash-card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Data Customer</div>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.85rem;">
                        <div><span style="color:var(--text-muted);">Nama:</span> <strong>{{ $ticket->customer_name }}</strong></div>
                        <div><span style="color:var(--text-muted);">Telp:</span> <strong>{{ $ticket->customer_phone }}</strong></div>
                        <div><span style="color:var(--text-muted);">Dibuat oleh:</span> <strong>{{ $ticket->creator?->name ?? 'Customer Online' }}</strong></div>
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Spesifikasi Unit</div>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.85rem;">
                        <div><span style="color:var(--text-muted);">Tipe:</span> <strong style="text-transform:uppercase;">{{ $ticket->unit_type }}</strong></div>
                        <div><span style="color:var(--text-muted);">Merek:</span> <strong>{{ $ticket->brand }}</strong></div>
                        <div><span style="color:var(--text-muted);">Model:</span> <strong>{{ $ticket->model }}</strong></div>
                    </div>
                </div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Jadwal Penyerahan Unit ke Kantor</div>
                <div style="font-size:0.9rem; font-weight:700; color:var(--primary);">{{ $ticket->dropoff_schedule ?: 'Tidak dicantumkan' }}</div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Deskripsi Kerusakan</div>
                <p style="font-size:0.85rem; line-height:1.6; color:var(--text-secondary);">{{ $ticket->damage_description }}</p>
            </div>
            @if($ticket->technician || $ticket->pic_name)
            <div style="margin-top:14px; padding:10px; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); font-size:0.8rem;">
                <span style="color:var(--text-muted);">Ditangani oleh:</span>
                <strong style="margin-left:6px;">{{ $ticket->technician?->name ?? $ticket->pic_name }}</strong>
                @if($ticket->pic_name && $ticket->technician)<span style="color:var(--text-muted); font-size:0.8rem;"> (PJ: {{ $ticket->pic_name }})</span>@endif
            </div>
            @endif
        </div>
    </div>

    <!-- Card Laporan Diagnosa Teknisi (Tampil di CS Dashboard) -->
    @if($ticket->start_check_date || $ticket->components_issue || $ticket->cause)
    <div class="dash-card" style="margin-bottom:16px; border-left: 4px solid var(--info); background: rgba(59, 142, 202, 0.04);">
        <div class="dash-card-header" style="padding-bottom: 8px; border-bottom:1px solid rgba(59, 142, 202, 0.15);">
            <h3 style="margin:0; font-size:0.92rem; color:var(--info); font-weight:800; display:flex; align-items:center; gap:8px;">
                📋 Laporan Diagnosa Teknisi
            </h3>
            @if($ticket->pic_name)
                <span style="font-size:0.78rem; color:var(--text-muted);">PJ: <strong>{{ $ticket->pic_name }}</strong></span>
            @endif
        </div>
        <div class="dash-card-body" style="padding-top:12px;">
            @if($ticket->start_check_date)
                <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:10px;">
                    Tanggal Pemeriksaan: <strong>{{ $ticket->start_check_date->format('d M Y') }}</strong>
                </div>
            @endif

            @if($ticket->components_issue && count($ticket->components_issue) > 0)
                <div style="margin-bottom:12px;">
                    <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; margin-bottom:6px;">Komponen Bermasalah / Butuh Part</div>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @foreach($ticket->components_issue as $c)
                            <span class="badge badge-danger" style="font-size:0.72rem; padding:4px 10px;">{{ $c }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ticket->cause)
                <div style="margin-bottom:10px;">
                    <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; font-weight:700; margin-bottom:4px;">Penyebab Kerusakan</div>
                    <p style="font-size:0.85rem; color:var(--text-secondary); margin:0; line-height:1.5;">{{ $ticket->cause }}</p>
                </div>
            @endif

            @if($ticket->estimated_cost)
                <div style="margin-top:12px; padding:8px 12px; background:rgba(40, 167, 69, 0.08); border-radius:6px; display:inline-block; border:1px solid rgba(40, 167, 69, 0.2);">
                    <span style="font-size:0.75rem; color:var(--text-muted);">Estimasi Biaya Saat Ini:</span>
                    <strong style="color:var(--success); font-size:0.95rem; margin-left:6px;">Rp {{ number_format($ticket->estimated_cost, 0, ',', '.') }}</strong>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- CS Status Update Form Card -->
    <div class="dash-card" style="margin-bottom:24px; border-left: 4px solid var(--primary);">
        <div class="dash-card-header" style="padding-bottom: 0; border-bottom: none;">
            <h3 style="margin:0; font-size:0.92rem; color:var(--primary); font-weight:800; letter-spacing:0.02em;">Update Status Servis (CS Panel)</h3>
        </div>
        <div class="dash-card-body" style="padding-top: 14px;">
            <form action="{{ route('dashboard.cs.tickets.update_status', $ticket) }}" method="POST">
                @csrf

                {{-- Member Info Panel (Hanya tampil untuk Member) --}}
                @if($ticket->customer && ($ticket->customer->is_member || $ticket->laptopKit))
                @php
                    $linkedUser    = $ticket->customer;
                    $memberPeriod  = $linkedUser->tuneUpPeriod();
                    $cleaningCount = $linkedUser->tuneUpCount();
                    $cleaningFull  = $cleaningCount >= 2;
                    $osCount       = $linkedUser->osInstallCount();
                    $osFull        = $osCount >= 2;
                @endphp
                <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; margin-bottom: 16px;">
                    <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: var(--primary); margin-bottom: 8px;">Member Terhubung &amp; Kuota Manfaat</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; flex-direction: column; gap: 3px;">
                            <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">{{ $linkedUser->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                @if($ticket->laptopKit)
                                    <span class="badge badge-primary" style="font-size:0.6rem;">Pengadaan</span>
                                    <span style="font-family: monospace;">{{ $ticket->laptopKit->member_id }}</span>
                                @else
                                    <span class="badge badge-primary" style="font-size:0.6rem;">Member Mandiri</span>
                                    <span style="font-family: monospace;">NIK: {{ $linkedUser->nik ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                            {{-- Progres 1: Deep Care Cleaning --}}
                            <div style="text-align: right;">
                                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 3px;">Deep Care Cleaning</div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.92rem; color: {{ $cleaningFull ? '#dc2626' : 'var(--primary)' }};">{{ $cleaningCount }}/2</span>
                                    <div style="width: 44px; height: 6px; background: var(--border-light); border-radius: 3px; overflow: hidden;">
                                        <div style="width: {{ min(100, $cleaningCount/2*100) }}%; height: 100%; background: {{ $cleaningFull ? '#dc2626' : 'var(--primary)' }}; border-radius: 3px;"></div>
                                    </div>
                                </div>
                                <div style="font-size: 0.62rem; color: var(--text-muted); margin-top: 2px;">Reset: {{ $memberPeriod['end']->format('d M Y') }}</div>
                            </div>

                            {{-- Progres 2: Essential Instalasi OS --}}
                            <div style="text-align: right;">
                                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 3px;">Essential Instalasi OS</div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.92rem; color: {{ $osFull ? '#dc2626' : '#1e40af' }};">{{ $osCount }}/2</span>
                                    <div style="width: 44px; height: 6px; background: var(--border-light); border-radius: 3px; overflow: hidden;">
                                        <div style="width: {{ min(100, $osCount/2*100) }}%; height: 100%; background: {{ $osFull ? '#dc2626' : '#1e40af' }}; border-radius: 3px;"></div>
                                    </div>
                                </div>
                                <div style="font-size: 0.62rem; color: var(--text-muted); margin-top: 2px;">Reset: {{ $memberPeriod['end']->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Status Utama --}}
                <div style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px;">Status Utama Tiket <span>*</span></label>
                    <select name="status" id="statusSelect" class="form-control" required style="padding:10px 12px; font-size:0.88rem; font-weight:600;" onchange="updateFormFields()">
                        <option value="waiting"         {{ $ticket->status === 'waiting'         ? 'selected':'' }}>Menunggu</option>
                        <option value="checking"        {{ $ticket->status === 'checking'        ? 'selected':'' }}>Pengecekan Teknisi</option>
                        <option value="konfirmasi_user" {{ in_array($ticket->status, ['konfirmasi_user','checked']) ? 'selected':'' }}>Konfirmasi User</option>
                        <option value="proses_service"  {{ in_array($ticket->status, ['proses_service','rma','in_service']) ? 'selected':'' }}>Proses Service</option>
                        <option value="siap_diambil"    {{ in_array($ticket->status, ['siap_diambil','done'])    ? 'selected':'' }}>Siap Diambil</option>
                        <option value="sudah_diambil"   {{ in_array($ticket->status, ['sudah_diambil','taken'])   ? 'selected':'' }}>Sudah Diambil</option>
                        <option value="cancelled"       {{ $ticket->status === 'cancelled'       ? 'selected':'' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Field: No. Servis Airtable (Hanya tampil saat status Menunggu) --}}
                <div id="fieldAirtable" style="margin-bottom: 14px; display: none;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px; color:var(--primary);">No. Servis Airtable <span>*</span></label>
                    <input type="text" name="airtable_service_number"
                           value="{{ old('airtable_service_number', $ticket->airtable_service_number) }}"
                           class="form-control" placeholder="Contoh: AX0-1108"
                           style="padding:10px 12px; font-size:0.88rem; font-family: monospace;">
                    <span class="form-hint" style="font-size:0.72rem; color:var(--text-muted);">Masukkan nomor servis dari Airtable saat unit pertama kali diterima.</span>
                </div>

                {{-- Field: Sub-Status / Cabang --}}
                <div id="fieldSubStatus" style="margin-bottom: 14px; display: none;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px; color:var(--primary);">Pilih Cabang / Sub-Status</label>
                    <select name="sub_status" id="subStatusSelect" class="form-control" style="padding:10px 12px; font-size:0.88rem;" onchange="updateFormFields()">
                        <option value="">-- Tanpa Sub-Status --</option>
                        <optgroup label="Konfirmasi User" id="optKonfirmasi">
                            <option value="pembelian_part" {{ $ticket->sub_status === 'pembelian_part' ? 'selected':'' }}>Pembelian Part</option>
                            <option value="klaim_garansi"  {{ $ticket->sub_status === 'klaim_garansi'  ? 'selected':'' }}>Klaim Garansi / RMA</option>
                        </optgroup>
                        <optgroup label="Proses Service" id="optProses">
                            <option value="menunggu_part"  {{ $ticket->sub_status === 'menunggu_part'  ? 'selected':'' }}>Menunggu Part</option>
                            <option value="pengerjaan_unit" {{ $ticket->sub_status === 'pengerjaan_unit' ? 'selected':'' }}>Pengerjaan Unit</option>
                        </optgroup>
                    </select>
                </div>

                {{-- Field: Estimasi Biaya Perbaikan (Rp) --}}
                <div id="fieldEstimatedCost" style="margin-bottom: 14px; display: none;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px; color:var(--primary);">Estimasi Biaya Perbaikan (Rp)</label>
                    <input type="number" name="estimated_cost"
                           value="{{ old('estimated_cost', $ticket->estimated_cost) }}"
                           class="form-control" placeholder="Contoh: 450000" min="0"
                           style="padding:10px 12px; font-size:0.88rem;">
                    <span class="form-hint" style="font-size:0.72rem; color:var(--text-muted);">Diisi oleh CS setelah berkoordinasi dengan customer.</span>
                </div>

                {{-- Member ID Input --}}
                <div style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px;">ID Member <span style="font-weight:400; color:var(--text-muted);">(NIK untuk Umum / SN untuk Pengadaan)</span></label>
                    <input type="text" name="member_id_input"
                           value="{{ old('member_id_input', $ticket->customer?->nik ?? $ticket->laptopKit?->member_id) }}"
                           class="form-control" placeholder="Kosongkan jika bukan member"
                           style="padding:8px 10px; font-size:0.85rem; font-family: monospace;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                    <label style="font-size: 0.84rem; font-weight: 700; color: #92400e; display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; padding: 10px 14px; background: rgba(180, 83, 9, 0.05); border: 1px solid rgba(180, 83, 9, 0.2); border-radius: var(--radius-sm);">
                        <input type="checkbox" name="is_tune_up" value="1"
                               {{ old('is_tune_up', $ticket->is_tune_up) ? 'checked' : '' }}
                               style="transform: scale(1.1); accent-color: #b45309;">
                        Layanan Deep Care Cleaning Gratis
                        <span style="font-weight: 400; color: var(--text-muted); font-size: 0.78rem;">(mengurangi 1 kuota member)</span>
                    </label>
                    <label style="font-size: 0.84rem; font-weight: 700; color: #1e40af; display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; padding: 10px 14px; background: rgba(30, 64, 175, 0.05); border: 1px solid rgba(30, 64, 175, 0.2); border-radius: var(--radius-sm);">
                        <input type="checkbox" name="is_os_install" value="1"
                               {{ old('is_os_install', $ticket->is_os_install) ? 'checked' : '' }}
                               style="transform: scale(1.1); accent-color: #1e40af;">
                        Essential Instalasi OS Gratis
                        <span style="font-weight: 400; color: var(--text-muted); font-size: 0.78rem;">(mengurangi 1 kuota member)</span>
                    </label>
                </div>

                @php
                    $rawPhone = preg_replace('/[^0-9]/', '', $ticket->customer_phone);
                    if (str_starts_with($rawPhone, '0')) {
                        $waPhone = '62' . substr($rawPhone, 1);
                    } elseif (str_starts_with($rawPhone, '8')) {
                        $waPhone = '62' . $rawPhone;
                    } else {
                        $waPhone = $rawPhone;
                    }
                    
                    $noService = $ticket->airtable_service_number ?: $ticket->ticket_number;
                    
                    $compText = 'Sparepart perangkat';
                    if ($ticket->components_issue) {
                        if (is_array($ticket->components_issue)) {
                            $compText = implode(', ', $ticket->components_issue);
                        } else {
                            $compText = $ticket->components_issue;
                        }
                    }
                    
                    // 1. Konfirmasi Pembelian Sparepart
                    $formattedCost = $ticket->estimated_cost ? 'Rp' . number_format($ticket->estimated_cost, 0, ',', '.') : 'Rp-';
                    $msgKonfirmasiPembelianPart = "UPDATE SERVICE - KLINIK KOMPUTER\n"
                        . "Halo Kak, mau info terkait unit Kakak dengan No. Service {$noService}.\n\n"
                        . "Setelah dilakukan pengecekan oleh Tim Teknisi Kami, unit membutuhkan penggantian part berikut:\n\n"
                        . "Part: {$compText}\n"
                        . "Estimasi Biaya: {$formattedCost}\n\n"
                        . "Mohon konfirmasinya ya Kak, apakah kami bisa lanjutkan pembelian part tersebut? 🙏\n\n"
                        . "Jika Kakak setuju, sebagai prosedur di kami, diperlukan DP minimal 50% dari total estimasi biaya. Jika Kakak Setuju, akan kami proses invoice-nya melalui Admin kami untuk pembayaran DP tersebut.\n\n"
                        . "Update status service bisa dicek juga di: https://klinik-komputer.com/service/track";

                    // 2. Konfirmasi Klaim Garansi
                    $msgKlaimGaransi = "UPDATE SERVICE - KLINIK KOMPUTER\n"
                        . "Halo Kak, mau info terkait unit Kakak dengan No. Service {$noService}.\n\n"
                        . "Kabar baik! Kerusakan pada unit Kakak masuk dalam cakupan garansi, sehingga tidak dikenakan biaya tambahan sama sekali.\n\n"
                        . "Kami akan proses klaim garansi ke pihak Axioo Pusat, dan akan kami update kembali progressnya ya Kak\n\n"
                        . "Estimasi proses klaim: 2 - 4 Hari Kerja *diluar hari Sabtu-Minggu.\n\n"
                        . "Pesan ini adalah Pesan Otomatis, jika ada suatu hal yang dapat kami bantu, dapat membalas Pesan ini😊\n\n"
                        . "Update status service bisa dicek juga di: https://klinik-komputer.com/service/track";
                        
                    // 3. Proses Service - Menunggu Sparepart
                    $msgMenungguPart = "UPDATE SERVICE - KLINIK KOMPUTER\n"
                        . "Halo Kak, update untuk unit dengan No. Service {$noService}.\n"
                        . "Saat ini unit Kakak dalam status menunggu kedatangan sparepart. \n\n"
                        . "Part yang dibutuhkan: {$compText}\n"
                        . "Estimasi kedatangan part: 2 - 4 Hari Kerja *Diluar Hari Sabtu - Minggu\n\n"
                        . "Kami akan segera lanjutkan proses pengerjaan begitu part sudah datang. Terima kasih atas kesabarannya! 🙏\n\n"
                        . "Pesan ini adalah Pesan Otomatis, jika ada suatu hal yang dapat kami bantu, dapat membalas Pesan ini😊\n\n"
                        . "Pantau statusnya kapan saja di: https://klinik-komputer.com/service/track";
                        
                    // 4. Proses Service - Dalam Pengerjaan
                    $msgProsesPengerjaan = "UPDATE SERVICE - KLINIK KOMPUTER\n"
                        . "Halo Kak, update untuk unit dengan No. Service {$noService}.\n"
                        . "Unit Kakak saat ini sedang dalam proses pengerjaan oleh teknisi kami. \n\n"
                        . "Estimasi selesai: 1 Hari Kerja\n\n"
                        . "Kami akan informasikan kembali begitu unit selesai dikerjakan dan siap diambil.\n\n"
                        . "Pesan ini adalah Pesan Otomatis, jika ada suatu hal yang dapat kami bantu, dapat membalas Pesan ini😊\n\n"
                        . "Cek progress terkini di: https://klinik-komputer.com/service/track";

                    // 5. Unit Siap Diambil
                    $msgSiapDiambil = "UPDATE SERVICE - KLINIK KOMPUTER\n"
                        . "Halo Kak, kabar gembira!!!\n"
                        . "Unit Kakak dengan No. Service {$noService} sudah selesai dikerjakan dan siap untuk diambil. \n\n"
                        . "Lokasi pengambilan: Ruko Segitiga Mas Kosambi, Jl A.Yani Blok E8, Klinik Komputer, Kota Bandung\n"
                        . "Jam operasional: *Senin - Jumat : 09.00 - 17.00 WIB | Sabtu : 09.00 - 14.00 WIB*\n\n"
                        . "Mohon konfirmasi kesediaan waktu pengambilan unitnya ya Kak, agar kami bisa siapkan terlebih dahulu.\n"
                        . "Terima kasih atas kepercayaan Kakak kepada Klinik Komputer\n\n"
                        . "Pesan ini adalah Pesan Otomatis, jika ada suatu hal yang dapat kami bantu, dapat membalas Pesan ini😊";
                @endphp

                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px; flex-wrap: wrap;">
                    <div id="containerWaButton" style="flex: 1; min-width: 200px; display: none;">
                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($msgKonfirmasiPembelianPart) }}"
                           target="_blank" rel="noopener noreferrer"
                           id="btnWaKonfirmasiPembelianPart"
                           class="btn"
                           style="display: none; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 14px; background: #15803d; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: var(--radius-sm); text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(21,128,61,0.25);">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA: Konfirmasi Pembelian Part
                        </a>

                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($msgKlaimGaransi) }}"
                           target="_blank" rel="noopener noreferrer"
                           id="btnWaKlaimGaransi"
                           class="btn"
                           style="display: none; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 14px; background: #15803d; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: var(--radius-sm); text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(21,128,61,0.25);">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA: Konfirmasi Klaim Garansi
                        </a>

                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($msgMenungguPart) }}"
                           target="_blank" rel="noopener noreferrer"
                           id="btnWaMenungguPart"
                           class="btn"
                           style="display: none; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 14px; background: #15803d; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: var(--radius-sm); text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(21,128,61,0.25);">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA: Menunggu Sparepart
                        </a>

                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($msgProsesPengerjaan) }}"
                           target="_blank" rel="noopener noreferrer"
                           id="btnWaProsesPengerjaan"
                           class="btn"
                           style="display: none; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 14px; background: #15803d; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: var(--radius-sm); text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(21,128,61,0.25);">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA: Proses Pengerjaan
                        </a>

                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode($msgSiapDiambil) }}"
                           target="_blank" rel="noopener noreferrer"
                           id="btnWaSiapDiambil"
                           class="btn"
                           style="display: none; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 9px 14px; background: #15803d; color: #ffffff; font-weight: 700; font-size: 0.85rem; border-radius: var(--radius-sm); text-decoration: none; border: none; box-shadow: 0 2px 6px rgba(21,128,61,0.25);">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA: Unit Siap Diambil
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary" style="font-weight:700; padding:9px 22px; font-size:0.85rem; white-space:nowrap;">
                        Simpan Perubahan
                    </button>
                </div>

    <!-- History Timeline -->
    <div class="dash-card" style="margin-top: 24px;">
        <div class="dash-card-header"><h3>Log Riwayat Status</h3></div>
        <div class="dash-card-body">
            @if($ticket->histories->count() > 0)
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($ticket->histories as $h)
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <div style="width:6px; height:6px; border-radius:50%; background:var(--primary); margin-top:6px; flex-shrink:0;"></div>
                    <div style="flex:1; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); padding:10px 14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; flex-wrap:wrap; gap:6px;">
                            <div style="display:flex; align-items:center; gap:6px;">
                                @if($h->old_status)<span class="badge badge-secondary" style="font-size:0.65rem;">{{ ucfirst($h->old_status) }}</span><span style="color:var(--text-muted); font-size:0.8rem;">&rarr;</span>@endif
                                <span class="badge badge-primary" style="font-size:0.65rem;">{{ ucfirst($h->new_status) }}</span>
                            </div>
                            <span style="font-size:0.75rem; color:var(--text-muted);">{{ $h->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($h->notes)
                        <p style="font-size:0.8rem; color:var(--text-secondary); margin:0;">{{ $h->notes }}</p>
                        @endif
                        @if($h->user)
                        <p style="font-size:0.75rem; color:var(--text-muted); margin:4px 0 0;">Diperbarui oleh: {{ $h->user->name }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="padding:20px;">
                <h3>Belum ada riwayat update</h3>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateFormFields() {
    const statusSelect = document.getElementById('statusSelect');
    const subStatusSelect = document.getElementById('subStatusSelect');
    if (!statusSelect) return;
    const status = statusSelect.value;
    const subStatus = subStatusSelect ? subStatusSelect.value : '';

    const fieldAirtable = document.getElementById('fieldAirtable');
    const fieldSubStatus = document.getElementById('fieldSubStatus');
    const fieldEstimatedCost = document.getElementById('fieldEstimatedCost');
    const optGroupKonfirmasi = document.getElementById('optKonfirmasi');
    const optGroupProses = document.getElementById('optProses');

    // Default: Sembunyikan field kontekstual
    if (fieldAirtable) fieldAirtable.style.display = 'none';
    if (fieldSubStatus) fieldSubStatus.style.display = 'none';
    if (fieldEstimatedCost) fieldEstimatedCost.style.display = 'none';
    if (optGroupKonfirmasi) optGroupKonfirmasi.style.display = 'none';
    if (optGroupProses) optGroupProses.style.display = 'none';

    if (status === 'waiting') {
        // Status Menunggu: Hanya tampilkan No. Servis Airtable
        if (fieldAirtable) fieldAirtable.style.display = 'block';
    } else if (status === 'konfirmasi_user' || status === 'checked') {
        // Status Konfirmasi User: Tampilkan Sub-Status Konfirmasi & Estimasi Biaya (Tanpa No. Airtable)
        if (fieldSubStatus) fieldSubStatus.style.display = 'block';
        if (optGroupKonfirmasi) optGroupKonfirmasi.style.display = 'block';
        if (fieldEstimatedCost) fieldEstimatedCost.style.display = 'block';
    } else if (status === 'proses_service' || status === 'rma' || status === 'in_service') {
        // Status Proses Service: Tampilkan Sub-Status Proses & Estimasi Biaya (Tanpa No. Airtable)
        if (fieldSubStatus) fieldSubStatus.style.display = 'block';
        if (optGroupProses) optGroupProses.style.display = 'block';
        if (fieldEstimatedCost) fieldEstimatedCost.style.display = 'block';
    }

    // Dynamic single WA Template button visibility
    const containerWa = document.getElementById('containerWaButton');
    const btnKonfirmasiPart = document.getElementById('btnWaKonfirmasiPembelianPart');
    const btnKlaim = document.getElementById('btnWaKlaimGaransi');
    const btnMenunggu = document.getElementById('btnWaMenungguPart');
    const btnProses = document.getElementById('btnWaProsesPengerjaan');
    const btnSiap = document.getElementById('btnWaSiapDiambil');

    if (btnKonfirmasiPart) btnKonfirmasiPart.style.display = 'none';
    if (btnKlaim) btnKlaim.style.display = 'none';
    if (btnMenunggu) btnMenunggu.style.display = 'none';
    if (btnProses) btnProses.style.display = 'none';
    if (btnSiap) btnSiap.style.display = 'none';

    let activeBtn = null;

    if (status === 'konfirmasi_user' || status === 'checked') {
        if (subStatus === 'pembelian_part') {
            activeBtn = btnKonfirmasiPart;
        } else if (subStatus === 'klaim_garansi') {
            activeBtn = btnKlaim;
        }
    } else if (status === 'proses_service' || status === 'rma' || status === 'in_service') {
        if (subStatus === 'menunggu_part' || subStatus === 'pembelian_part') {
            activeBtn = btnMenunggu;
        } else {
            activeBtn = btnProses;
        }
    } else if (status === 'siap_diambil' || status === 'done') {
        activeBtn = btnSiap;
    }

    if (activeBtn && containerWa) {
        containerWa.style.display = 'block';
        activeBtn.style.display = 'inline-flex';
    } else if (containerWa) {
        containerWa.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', updateFormFields);
</script>
@endpush
