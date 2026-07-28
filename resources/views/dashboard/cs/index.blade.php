@extends('layouts.dashboard')
@section('title', 'Dashboard CS')
@section('page_title', 'Dashboard CS')
@section('page_subtitle', 'Manajemen tiket servis & pengadaan sekolah')

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('topbar_actions')
<a href="{{ route('dashboard.cs.create') }}" class="btn btn-primary btn-sm">Buat Tiket</a>
@endsection

@section('content')

{{-- ════════ STATS ════════ --}}
<div class="stats-grid" style="margin-bottom: 20px;">
    {{-- Tiket --}}
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $tickets->total() }}</div>
            <div class="stat-label">Total Tiket</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $tickets->getCollection()->where('status','waiting')->count() }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $tickets->getCollection()->whereIn('status',['checking','checked','rma'])->count() }}</div>
            <div class="stat-label">Diproses</div>
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
            <div class="stat-num">{{ $tickets->getCollection()->where('status','done')->count() }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
</div>

{{-- ════════ TIKET TABLE ════════ --}}
<div class="dash-card" style="margin-bottom: 20px;">
    <div class="dash-card-header">
        <h3>Daftar Semua Tiket</h3>
        <a href="{{ route('dashboard.cs.create') }}" class="btn btn-primary btn-sm">Buat Tiket Walk-in</a>
    </div>
    <div class="dash-card-body" style="padding:0;">
        @if($tickets->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket / Antrian</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Teknisi</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $ticket->ticket_number }}</div>
                            @if($ticket->airtable_service_number)
                                <div style="font-family:monospace; font-size:0.72rem; color:var(--success); font-weight:700; margin-top:2px;">
                                    AT: {{ $ticket->airtable_service_number }}
                                </div>
                            @endif
                            <div style="font-size:0.72rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->customer_name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ticket->customer_phone }}</div>
                            @if($ticket->dropoff_schedule)
                            <div style="font-size:0.72rem; color:var(--primary); font-weight:600; margin-top:2px;">
                                📅 Penyerahan: {{ $ticket->dropoff_schedule }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td style="font-size:0.85rem; color:var(--text-secondary);">
                            {{ $ticket->technician?->name ?? '—' }}
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <div class="status-dot {{ $ticket->status }}"></div>
                                <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.72rem;">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td style="white-space: nowrap;">
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('dashboard.cs.show', $ticket) }}" class="btn btn-outline btn-sm">Detail</a>
                                <form action="{{ route('dashboard.cs.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket {{ $ticket->ticket_number }} ini secara permanen dari database?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.72rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;">{{ $tickets->links() }}</div>
        @else
        <div class="empty-state">
            <h3>Belum ada tiket</h3>
            <p>Buat tiket baru untuk customer yang datang langsung.</p>
            <a href="{{ route('dashboard.cs.create') }}" class="btn btn-primary" style="margin-top:16px;">Buat Tiket Pertama</a>
        </div>
        @endif
    </div>
</div>

{{-- ════════ PROCUREMENT SECTION ════════ --}}
<div class="dash-card" id="procurement-section">
    <div class="dash-card-header">
        <div>
            <h3 style="display:flex; align-items:center; gap:10px;">
                Pengajuan Pengadaan Sekolah
                @if($procurementStats['pending'] > 0)
                <span class="badge badge-warning" style="font-size:0.65rem;">{{ $procurementStats['pending'] }} perlu ditindak</span>
                @endif
            </h3>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Hubungi PIC sekolah via WhatsApp untuk tindak lanjut pengajuan.</p>
        </div>
        {{-- Mini Stats Pengadaan --}}
        <div style="display:flex; gap:16px; text-align:right;">
            <div>
                <div style="font-weight:800; font-size:1.1rem; color:var(--primary);">{{ $procurementStats['total'] }}</div>
                <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em;">Total</div>
            </div>
            <div>
                <div style="font-weight:800; font-size:1.1rem; color:var(--warning);">{{ $procurementStats['pending'] }}</div>
                <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em;">Perlu Aksi</div>
            </div>
            <div>
                <div style="font-weight:800; font-size:1.1rem; color:var(--success);">{{ $procurementStats['dibayar'] }}</div>
                <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em;">Dibayar</div>
            </div>
            <div>
                <div style="font-weight:800; font-size:1.1rem; color:#dc2626;">{{ $procurementStats['dibatalkan'] }}</div>
                <div style="font-size:0.68rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.04em;">Dibatalkan</div>
            </div>
        </div>
    </div>
    <div class="dash-card-body" style="padding:0;">
        @if($procurementOrders->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Sekolah / Institusi</th>
                        <th>PIC</th>
                        <th>WhatsApp PIC</th>
                        <th>Unit & Laptop</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procurementOrders as $order)
                    <tr @if(in_array($order->status, ['pending','diproses'])) style="background:rgba(226,140,59,0.03);" @endif>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.8rem;">{{ $order->order_number }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $order->school_name }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">{{ $order->school_city }} &bull; {{ $order->school_type_label }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.85rem; font-weight:600;">{{ $order->pic_name }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">{{ $order->pic_position }}</div>
                        </td>
                        <td>
                            {{-- Tombol WA langsung --}}
                            <a href="https://wa.me/62{{ ltrim($order->pic_phone, '0') }}?text={{ urlencode('Halo ' . $order->pic_name . ', kami dari Klinik Komputer menghubungi terkait pengajuan pengadaan laptop Axioo dengan nomor referensi ' . $order->order_number . ' dari ' . $order->school_name . '. Apakah Anda yang mengajukan?') }}"
                               target="_blank"
                               class="btn btn-outline btn-sm"
                               style="font-size:0.72rem; display:inline-flex; align-items:center; gap:4px; border-color:var(--success); color:var(--success);">
                                WA {{ $order->pic_phone }}
                            </a>
                        </td>
                        <td>
                            <div style="font-size:0.82rem; font-weight:600;">{{ $order->total_units }} unit</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">{{ $order->axioo_model }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status_color }}" style="font-size:0.68rem;">{{ $order->status_label }}</span>
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $order->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('dashboard.cs.procurement.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;">{{ $procurementOrders->links() }}</div>
        @else
        <div class="empty-state">
            <h3>Belum ada pengajuan pengadaan</h3>
            <p>Pengajuan dari sekolah / institusi akan muncul di sini.</p>
        </div>
        @endif
    </div>
</div>

@endsection
