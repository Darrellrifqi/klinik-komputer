@extends('layouts.dashboard')
@section('title', 'Dashboard Teknisi')
@section('page_title', 'Dashboard Teknisi')
@section('page_subtitle', 'Kelola dan update status tiket servis')

@section('sidebar_nav')
<a href="{{ route('dashboard.teknisi') }}" class="active">
    <span class="nav-icon">Daftar Tiket</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('service.track') }}"><span class="nav-icon">Tracking Publik</span></a>
<a href="{{ route('home') }}"><span class="nav-icon">Beranda</span></a>
@endsection

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon yellow">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $activeTickets->where('status','waiting')->count() }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $activeTickets->where('status','checking')->count() }}</div>
            <div class="stat-label">Sedang Dicek</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $activeTickets->whereIn('status',['checked','rma'])->count() }}</div>
            <div class="stat-label">Selesai Cek / RMA</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $doneTickets->where('status','done')->count() }}</div>
            <div class="stat-label">Selesai Hari Ini</div>
        </div>
    </div>
</div>

<!-- Active Tickets -->
<div class="dash-card" style="margin-bottom:20px;">
    <div class="dash-card-header">
        <h3>Tiket Aktif</h3>
        <span class="badge badge-warning">{{ $activeTickets->count() }} unit</span>
    </div>
    <div class="dash-card-body" style="padding:0;">
        @if($activeTickets->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Kerusakan</th>
                        <th>Status</th>
                        <th>Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeTickets as $ticket)
                    <tr>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $ticket->ticket_number }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">#{{ $ticket->queue_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->customer_name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ticket->customer_phone }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td style="max-width:160px;">
                            <div style="font-size:0.8rem; color:var(--text-secondary); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $ticket->damage_description }}">
                                {{ $ticket->damage_description }}
                            </div>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <div class="status-dot {{ $ticket->status }}"></div>
                                <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.68rem;">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $ticket->created_at->format('d M') }}</td>
                        <td>
                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                <a href="{{ route('dashboard.teknisi.show', $ticket) }}" class="btn btn-outline btn-sm" style="padding: 6px 10px;">Detail</a>
                                @if($ticket->status === 'waiting')
                                    <button onclick="openModal('modal-start-{{ $ticket->id }}')" class="btn btn-primary btn-sm">Mulai Cek</button>
                                @elseif($ticket->status === 'checking')
                                    <button onclick="openModal('modal-finish-{{ $ticket->id }}')" class="btn btn-primary btn-sm">Selesai Cek</button>
                                @elseif($ticket->status === 'checked')
                                    <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="rma">
                                        <button type="submit" class="btn btn-sm btn-accent" onclick="return confirm('Tandai tiket ini masuk proses RMA?')">RMA</button>
                                    </form>
                                    <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="done">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Tandai unit sebagai selesai servis?')">Selesai</button>
                                    </form>
                                @elseif($ticket->status === 'rma')
                                    <form action="{{ route('dashboard.teknisi.update', $ticket) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="done">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Tandai unit sebagai selesai servis?')">Selesai</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal: Start Check -->
                    <div class="modal-overlay" id="modal-start-{{ $ticket->id }}">
                        <div class="modal">
                            <div class="modal-header">
                                <h3>Mulai Pengecekan</h3>
                                <button class="modal-close" onclick="closeModal('modal-start-{{ $ticket->id }}')">✕</button>
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
                                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modal-start-{{ $ticket->id }}')">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;">Mulai Proses</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal: Finish Check -->
                    <div class="modal-overlay" id="modal-finish-{{ $ticket->id }}">
                        <div class="modal">
                            <div class="modal-header">
                                <h3>Hasil Pemeriksaan</h3>
                                <button class="modal-close" onclick="closeModal('modal-finish-{{ $ticket->id }}')">✕</button>
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
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Estimasi Biaya Perbaikan (Rp)</label>
                                    <input type="number" name="estimated_cost" class="form-control"
                                           placeholder="Contoh: 450000" min="0" style="padding: 10px 12px; border-radius: 6px;">
                                </div>
                                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modal-finish-{{ $ticket->id }}')">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px;">Simpan Laporan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <h3>Antrian bersih</h3>
            <p>Tidak ada unit komputer aktif dalam antrian Anda saat ini.</p>
        </div>
        @endif
    </div>
</div>

<!-- Done Tickets -->
@if($doneTickets->count() > 0)
<div class="dash-card">
    <div class="dash-card-header">
        <h3>Riwayat Servis Selesai</h3>
        <span class="badge badge-success">Terbaru</span>
    </div>
    <div class="dash-card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doneTickets as $ticket)
                    <tr>
                        <td style="font-family:monospace; font-size:0.8rem; color:var(--text-secondary);">{{ $ticket->ticket_number }}</td>
                        <td style="font-size:0.85rem;">{{ $ticket->customer_name }}</td>
                        <td style="font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</td>
                        <td><span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.68rem;">{{ $ticket->status_label }}</span></td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $ticket->updated_at->format('d M Y') }}</td>
                        <td><a href="{{ route('dashboard.teknisi.show', $ticket) }}" class="btn btn-outline btn-sm">Detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
