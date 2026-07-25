@extends('layouts.dashboard')
@section('title', 'Dashboard Produksi')
@section('page_title', 'Dashboard Produksi')
@section('page_subtitle', 'Manajemen scan serial number komponen laptop pengadaan')

@section('sidebar_nav')
<a href="{{ route('dashboard.produksi') }}" class="active">
    <span class="nav-icon">Daftar Project</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
@endsection

@section('content')
<div class="dash-card">
    <div class="dash-card-header">
        <h3>Project Pengadaan Laptop Sekolah</h3>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @if($orders->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Sekolah / Institusi</th>
                        <th>Seri & Model</th>
                        <th>Target Unit</th>
                        <th>Kits Dibungkus</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $isFullyScanned = $order->kits_count >= $order->total_units;
                    @endphp
                    <tr @if(!$isFullyScanned && $order->status === 'diproses') style="background: rgba(95,138,99,0.03);" @endif>
                        <td>
                            <div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $order->order_number }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.85rem;">{{ $order->school_name }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->school_city }} &bull; {{ $order->school_type_label }}</div>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600;">{{ $order->axioo_model }}</div>
                            <span class="badge {{ $order->axioo_series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size: 0.62rem;">{{ strtoupper($order->axioo_series) }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">{{ $order->total_units }}</div>
                            <div style="font-size: 0.68rem; color: var(--text-muted);">unit</div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="font-weight: 800; font-size: 1.1rem; color: {{ $isFullyScanned ? 'var(--success)' : 'var(--primary)' }};">
                                    {{ $order->kits_count }}
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">/ {{ $order->total_units }} unit</div>
                            </div>
                            <!-- Mini Progress Bar -->
                            @php
                                $percent = min(100, ($order->kits_count / $order->total_units) * 100);
                            @endphp
                            <div style="width: 100px; height: 4px; background: var(--border); border-radius: 2px; margin-top: 4px; overflow: hidden;">
                                <div style="width: {{ $percent }}%; height: 100%; background: {{ $isFullyScanned ? 'var(--success)' : 'var(--primary)' }};"></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status_color }}" style="font-size: 0.68rem;">{{ $order->status_label }}</span>
                        </td>
                        <td>
                            @if($isFullyScanned)
                            <a href="{{ route('dashboard.produksi.scan', $order) }}" class="btn btn-outline btn-sm" style="color: var(--success); border-color: var(--success);">Lihat SN Scanned</a>
                            @elseif($order->status === 'diproses')
                            <a href="{{ route('dashboard.produksi.scan', $order) }}" class="btn btn-primary btn-sm">Mulai Scan SN</a>
                            @else
                            <a href="{{ route('dashboard.produksi.scan', $order) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 12px 16px;">{{ $orders->links() }}</div>
        @else
        <div class="empty-state">
            <h3>Belum ada pengajuan pengadaan</h3>
            <p>Pengajuan pengadaan laptop dari sekolah yang disetujui akan muncul di sini untuk di-scan komponennya.</p>
        </div>
        @endif
    </div>
</div>
@endsection
