@extends('layouts.dashboard')
@section('title', 'Pengadaan Sekolah — Super Admin')
@section('page_title', 'Pengadaan Sekolah')
@section('page_subtitle', 'Kelola order pengadaan laptop institusi pendidikan')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pengajuan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $stats['pending'] }}</div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="5" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $stats['dibayar'] }}</div>
            <div class="stat-label">Sudah Dibayar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red" style="background: rgba(239, 68, 68, 0.08); color: #dc2626;">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num" style="color: #dc2626;">{{ $stats['dibatalkan'] }}</div>
            <div class="stat-label">Dibatalkan</div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 16px;">{{ session('success') }}</div>
@endif

<!-- Filter & Export Panel -->
<div class="dash-card" style="margin-bottom: 20px;">
    <div class="dash-card-body" style="padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <select name="status" class="form-control" style="width: auto; padding: 8px 12px; font-size: 0.8rem; border-radius: 6px;">
                <option value="">Semua Status</option>
                <option value="pending"             {{ request('status')==='pending'             ? 'selected':'' }}>Menunggu Konfirmasi</option>
                <option value="diproses"            {{ request('status')==='diproses'            ? 'selected':'' }}>Sedang Diproses</option>
                <option value="konfirmasi_harga"    {{ request('status')==='konfirmasi_harga'    ? 'selected':'' }}>Penawaran Harga</option>
                <option value="menunggu_pembayaran" {{ request('status')==='menunggu_pembayaran' ? 'selected':'' }}>Menunggu Pembayaran</option>
                <option value="dibayar"             {{ request('status')==='dibayar'             ? 'selected':'' }}>Sudah Dibayar</option>
                <option value="diproses_pengiriman" {{ request('status')==='diproses_pengiriman' ? 'selected':'' }}>Dalam Pengiriman</option>
                <option value="selesai"             {{ request('status')==='selesai'             ? 'selected':'' }}>Selesai</option>
                <option value="dibatalkan"          {{ request('status')==='dibatalkan'          ? 'selected':'' }}>Dibatalkan</option>
            </select>
            <select name="school_type" class="form-control" style="width: auto; padding: 8px 12px; font-size: 0.8rem; border-radius: 6px;">
                <option value="">Semua Jenis</option>
                <option value="sd"               {{ request('school_type')==='sd'               ? 'selected':'' }}>SD / MI</option>
                <option value="smp"              {{ request('school_type')==='smp'              ? 'selected':'' }}>SMP / MTs</option>
                <option value="sma"              {{ request('school_type')==='sma'              ? 'selected':'' }}>SMA / MA</option>
                <option value="smk"              {{ request('school_type')==='smk'              ? 'selected':'' }}>SMK</option>
                <option value="perguruan_tinggi" {{ request('school_type')==='perguruan_tinggi' ? 'selected':'' }}>Perguruan Tinggi</option>
                <option value="instansi_lain"    {{ request('school_type')==='instansi_lain'    ? 'selected':'' }}>Instansi Lain</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 16px; border-radius: 6px;">Filter</button>
            <a href="{{ route('admin.procurement') }}" class="btn btn-outline btn-sm" style="padding: 8px 16px; border-radius: 6px;">Reset</a>
        </form>
        
        <a href="{{ route('admin.procurement.export') }}" class="btn btn-success btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-weight: 700; border-radius: 6px; text-decoration: none;">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none" style="margin-top: 1px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export Excel (CSV)
        </a>
    </div>
</div>

<!-- Table 1: Active Submissions (Only visible if status is not filtered specifically to cancelled) -->
@if(!request('status') || request('status') !== 'dibatalkan')
<div class="dash-card" style="margin-bottom: 24px; border-left: 4px solid var(--primary);">
    <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1rem; color: var(--primary);">Daftar Pengajuan Aktif ({{ $activeOrders instanceof \Illuminate\Support\Collection ? $activeOrders->count() : $activeOrders->total() }})</h3>
        <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600;">Menampilkan antrean aktif perbaikan & pengiriman</span>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @if($activeOrders->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Institusi</th>
                        <th>PIC</th>
                        <th>Unit</th>
                        <th>Laptop</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeOrders as $order)
                    <tr>
                        <td>
                            <div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $order->order_number }}</div>
                            @if($order->is_tkdn)
                                <span class="badge badge-success" style="font-size: 0.6rem; padding: 2px 6px; margin-top: 3px;">TKDN</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.6rem; padding: 2px 6px; margin-top: 3px;">RETAIL</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $order->school_name }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->school_city }} &bull; {{ $order->school_type_label }}</div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;">{{ $order->pic_name }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->pic_phone }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; font-size: 1rem; color: var(--primary);">{{ $order->total_units }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">unit</div>
                        </td>
                        <td style="font-size: 0.82rem;">{{ $order->axioo_model }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="status-dot {{ $order->status }}"></div>
                                <span class="badge badge-{{ $order->status_color }}" style="font-size: 0.68rem;">{{ $order->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.78rem; color: var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.procurement.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(!($activeOrders instanceof \Illuminate\Support\Collection))
        <div style="padding: 12px 16px;">{{ $activeOrders->appends(request()->query())->links() }}</div>
        @endif
        @else
        <div class="empty-state" style="padding: 40px 20px;">
            <h3>Tidak ada pengajuan aktif</h3>
            <p>Semua order pengadaan telah diselesaikan atau dipindahkan.</p>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Table 2: Cancelled Submissions (Only visible if status is not filtered specifically to active statuses) -->
@if(!request('status') || request('status') === 'dibatalkan')
<div class="dash-card" style="border-left: 4px solid #dc2626;">
    <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1rem; color: #dc2626;">Daftar Pengajuan Dibatalkan (Cancel) ({{ $cancelledOrders instanceof \Illuminate\Support\Collection ? $cancelledOrders->count() : $cancelledOrders->total() }})</h3>
        <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600;">Data rekap transaksi batal/lost deal untuk evaluasi</span>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @if($cancelledOrders->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Institusi</th>
                        <th>PIC</th>
                        <th>Unit</th>
                        <th>Laptop</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cancelledOrders as $order)
                    <tr>
                        <td>
                            <div style="font-family: monospace; font-weight: 700; color: #dc2626; font-size: 0.82rem;">{{ $order->order_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $order->school_name }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->school_city }} &bull; {{ $order->school_type_label }}</div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem;">{{ $order->pic_name }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->pic_phone }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; font-size: 1rem; color: #dc2626;">{{ $order->total_units }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">unit</div>
                        </td>
                        <td style="font-size: 0.82rem;">{{ $order->axioo_model }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div class="status-dot dibatalkan" style="background-color: #dc2626;"></div>
                                <span class="badge badge-danger" style="font-size: 0.68rem;">{{ $order->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.78rem; color: var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.procurement.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(!($cancelledOrders instanceof \Illuminate\Support\Collection))
        <div style="padding: 12px 16px;">{{ $cancelledOrders->appends(request()->query())->links() }}</div>
        @endif
        @else
        <div class="empty-state" style="padding: 40px 20px;">
            <h3>Tidak ada pengajuan dibatalkan</h3>
            <p>Bagus! Belum ada pembatalan pengadaan terdaftar pada sistem.</p>
        </div>
        @endif
    </div>
</div>
@endif
@endsection
