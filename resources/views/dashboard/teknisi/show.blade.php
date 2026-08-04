@extends('layouts.dashboard')
@section('title', 'Detail Tiket | Teknisi')
@section('page_title', 'Detail Tiket')
@section('page_subtitle', $ticket->ticket_number)

@section('sidebar_nav')
<a href="{{ route('dashboard.teknisi') }}" class="active"><span class="nav-icon">Daftar Tiket</span></a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('home') }}"><span class="nav-icon">Beranda</span></a>
@endsection

@section('content')
<div style="max-width:800px;">
    <a href="{{ route('dashboard.teknisi') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px;">Kembali</a>

    <div class="dash-card" style="margin-bottom:16px;">
        <div class="dash-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <div>
                <div style="font-family:monospace; font-size:1.05rem; font-weight:800; color:var(--primary);">{{ $ticket->ticket_number }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }} &bull; Masuk {{ $ticket->created_at->format('d M Y') }}</div>
            </div>
            <div>
                <span class="badge badge-{{ $ticket->status_color }}" style="padding:8px 14px; font-size:0.8rem;">
                    {{ $ticket->full_status_label }}
                </span>
            </div>
        </div>
        <div class="dash-card-body">

            <!-- Panel Aksi Update Teknisi -->
            @if($ticket->status !== 'done' && $ticket->status !== 'sudah_diambil' && $ticket->status !== 'cancelled')
            <div style="background:var(--bg-alt); border:1px solid var(--border); border-radius:var(--radius-sm); padding:12px 14px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; border-left:4px solid var(--primary);">
                <div>
                    <div style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.06em; color:var(--primary);">Aksi Progres Teknisi</div>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">
                        Pilih tindakan di bawah untuk memperbarui progres perbaikan unit.
                    </div>
                </div>
                <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    @if($ticket->status === 'waiting')
                        <button onclick="openModal('modal-start')" class="btn btn-primary btn-sm" style="padding:7px 14px; font-weight:700;">Mulai Cek Perangkat</button>
                    @elseif($ticket->status === 'checking')
                        <button onclick="openModal('modal-finish')" class="btn btn-primary btn-sm" style="padding:7px 14px; font-weight:700;">Selesai Cek & Teruskan CS</button>
                    @elseif(in_array($ticket->status, ['konfirmasi_user', 'checked', 'proses_service', 'rma', 'in_service']))
                        @if($ticket->sub_status !== 'menunggu_part')
                            <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="margin:0;">
                                @csrf
                                <input type="hidden" name="action" value="set_menunggu_part">
                                <button type="submit" class="btn btn-sm btn-outline" style="padding:6px 14px; font-weight:600; font-size:0.8rem;">Menunggu Part</button>
                            </form>
                        @endif

                        @if($ticket->sub_status !== 'pengerjaan_unit')
                            <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="margin:0;">
                                @csrf
                                <input type="hidden" name="action" value="set_pengerjaan_unit">
                                <button type="submit" class="btn btn-sm btn-primary" style="padding:6px 14px; font-weight:600; font-size:0.8rem;">Pengerjaan Unit</button>
                            </form>
                        @endif

                        <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="margin:0;">
                            @csrf
                            <input type="hidden" name="action" value="done">
                            <button type="submit" class="btn btn-sm btn-success" style="padding:6px 16px; font-weight:700; font-size:0.8rem;" onclick="return confirm('Tandai proses pengerjaan telah selesai dan status menjadi Siap Diambil?')">
                                ✓ Selesai Servis
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Customer</div>
                    <div style="font-size:0.85rem; display:flex; flex-direction:column; gap:6px;">
                        <div><span style="color:var(--text-muted);">Nama:</span> <strong>{{ $ticket->customer_name }}</strong></div>
                        <div><span style="color:var(--text-muted);">Telp:</span> <a href="https://wa.me/62{{ ltrim($ticket->customer_phone,'0') }}" target="_blank" style="color:var(--primary); font-weight:600;">{{ $ticket->customer_phone }}</a></div>
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Unit Perangkat</div>
                    <div style="font-size:0.85rem; display:flex; flex-direction:column; gap:6px;">
                        <div><span style="color:var(--text-muted);">Tipe:</span> <strong style="text-transform:uppercase;">{{ $ticket->unit_type }}</strong></div>
                        <div><span style="color:var(--text-muted);">Merek:</span> <strong>{{ $ticket->brand }}</strong></div>
                        <div><span style="color:var(--text-muted);">Model:</span> <strong>{{ $ticket->model }}</strong></div>
                    </div>
                </div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Keluhan Awal</div>
                <p style="font-size:0.85rem; color:var(--text-secondary); line-height:1.6;">{{ $ticket->damage_description }}</p>
            </div>
            @if($ticket->start_check_date)
            <div style="margin-top:14px; padding:12px; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Laporan Pengecekan</div>
                <div style="font-size:0.82rem; display:flex; gap:20px; flex-wrap:wrap;">
                    <div><span style="color:var(--text-muted);">Mulai:</span> <strong>{{ $ticket->start_check_date->format('d M Y') }}</strong></div>
                    <div><span style="color:var(--text-muted);">PJ:</span> <strong>{{ $ticket->pic_name }}</strong></div>
                </div>
            </div>
            @endif
            @if($ticket->components_issue)
            <div style="margin-top:14px; padding:12px; background:rgba(59, 142, 202, 0.05); border:1px solid rgba(59, 142, 202, 0.15); border-radius:var(--radius-sm);">
                <div style="font-size:0.7rem; color:var(--info); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Hasil Diagnosa</div>
                <div style="margin-bottom:8px; display:flex; flex-wrap:wrap; gap:4px;">
                    @foreach($ticket->components_issue as $c)<span class="badge badge-danger" style="font-size:0.65rem;">{{ $c }}</span>@endforeach
                </div>
                @if($ticket->cause)<p style="font-size:0.8rem; color:var(--text-secondary); margin-top:6px;">{{ $ticket->cause }}</p>@endif
                @if($ticket->estimated_cost)
                <div style="margin-top:8px; font-size:0.95rem; font-weight:800; color:var(--accent);">Estimasi Biaya: Rp {{ number_format($ticket->estimated_cost,0,',','.') }}</div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- History -->
    <div class="dash-card">
        <div class="dash-card-header"><h3>Riwayat Status</h3></div>
        <div class="dash-card-body">
            @forelse($ticket->histories as $h)
            <div style="display:flex; gap:10px; margin-bottom:12px;">
                <div style="width:6px; height:6px; border-radius:50%; background:var(--primary); margin-top:6px; flex-shrink:0;"></div>
                <div style="flex:1; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); padding:10px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:2px; flex-wrap:wrap; gap:6px;">
                        <span class="badge badge-primary" style="font-size:0.65rem;">{{ $h->status_label ?? ucfirst($h->new_status) }}</span>
                        <span style="font-size:0.75rem; color:var(--text-muted);">{{ $h->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($h->notes)<p style="font-size:0.8rem; color:var(--text-secondary); margin-top:2px;">{{ $h->notes }}</p>@endif
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:16px;"><p>Belum ada riwayat update.</p></div>
            @endforelse
        </div>
    </div>
</div>

@if($ticket->status === 'waiting')
<!-- Modal: Start Check -->
<div class="modal-overlay" id="modal-start">
    <div class="modal">
        <div class="modal-header">
            <h3>Mulai Pengecekan</h3>
            <button class="modal-close" onclick="closeModal('modal-start')">✕</button>
        </div>
        <p style="color:var(--text-secondary); margin-bottom:16px; font-size:0.85rem;">
            Tiket: <strong>{{ $ticket->ticket_number }}</strong> | {{ $ticket->brand }} {{ $ticket->model }}
        </p>
        <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
            @csrf
            <input type="hidden" name="action" value="start_check">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Tanggal Pemeriksaan <span>*</span></label>
                <input type="date" name="start_check_date" class="form-control"
                       value="{{ date('Y-m-d') }}" required style="padding: 10px 12px; border-radius: 6px;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Penanggung Jawab <span>*</span></label>
                <input type="text" name="pic_name" class="form-control"
                       value="{{ auth()->user()->name }}" placeholder="Nama teknisi PJ" required style="padding: 10px 12px; border-radius: 6px;">
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modal-start')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;">Mulai Proses</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($ticket->status === 'checking')
<!-- Modal: Finish Check -->
<div class="modal-overlay" id="modal-finish">
    <div class="modal">
        <div class="modal-header">
            <h3>Hasil Pemeriksaan</h3>
            <button class="modal-close" onclick="closeModal('modal-finish')">✕</button>
        </div>
        <p style="color:var(--text-secondary); margin-bottom:16px; font-size:0.85rem;">
            Tiket: <strong>{{ $ticket->ticket_number }}</strong> | {{ $ticket->brand }} {{ $ticket->model }}
        </p>
        <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
            @csrf
            <input type="hidden" name="action" value="finish_check">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Komponen Bermasalah <span>*</span></label>
                <textarea name="components_issue" class="form-control" rows="3"
                          placeholder="Tuliskan komponen bermasalah (Satu komponen per baris).&#10;Contoh:&#10;Layar LCD&#10;Baterai Kembung" required style="padding: 10px 12px; border-radius: 6px;"></textarea>
                <span class="form-hint">Tuliskan satu komponen per baris.</span>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Penyebab Kerusakan <span>*</span></label>
                <textarea name="cause" class="form-control" rows="2"
                          placeholder="Penyebab kerusakan..." required style="padding: 10px 12px; border-radius: 6px;"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modal-finish')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;">Simpan & Teruskan ke CS</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if(e.target===m) closeModal(m.id); });
});
</script>
@endpush
