@extends('layouts.dashboard')
@section('title', 'Tiket Pengadaan Sekolah - CS')
@section('page_title', 'Tiket Pengadaan Sekolah')
@section('page_subtitle', 'Manajemen & pengajuan pengadaan laptop/unit sekolah')

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')

{{-- Stats Pengadaan --}}
<div class="stats-grid" style="margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $procurementStats['total'] }}</div>
            <div class="stat-label">Total Pengadaan</div>
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
            <div class="stat-num">{{ $procurementStats['pending'] }}</div>
            <div class="stat-label">Perlu Ditindak</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $procurementStats['dibayar'] }}</div>
            <div class="stat-label">Sudah Dibayar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $procurementStats['dibatalkan'] }}</div>
            <div class="stat-label">Dibatalkan</div>
        </div>
    </div>
</div>

{{-- Procurement Table --}}
<div class="dash-card">
    <div class="dash-card-header">
        <div>
            <h3>Daftar Tiket Pengadaan Sekolah</h3>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Daftar pengajuan paket laptop & pengadaan unit dari instansi sekolah.</p>
        </div>
    </div>
    <div class="dash-card-body" style="padding:0;">
        @if($procurementOrders->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Instansi Sekolah</th>
                        <th>PIC / Kontak</th>
                        <th>Paket Laptop</th>
                        <th>Jumlah Unit</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procurementOrders as $order)
                    <tr>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $order->order_number }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $order->school_name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->school_address }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.82rem;">{{ $order->pic_name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $order->pic_phone }}</div>
                        </td>
                        <td style="font-size:0.85rem;">
                            {{ $order->laptopKit?->name ?? 'Custom Package' }}
                        </td>
                        <td style="font-weight:700; font-size:0.85rem;">
                            {{ $order->quantity }} unit
                        </td>
                        <td style="font-weight:700; font-size:0.85rem; color:var(--primary);">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td>
                            @php
                                $badgeClass = match($order->status) {
                                    'pending' => 'warning',
                                    'diproses' => 'info',
                                    'dibayar' => 'success',
                                    'dibatalkan' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeClass }}" style="font-size:0.72rem;">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.cs.procurement.show', $order) }}" class="btn btn-outline btn-sm">Detail & WA</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;">{{ $procurementOrders->links() }}</div>
        @else
        <div style="padding: 40px; text-align: center; color: var(--text-muted);">
            Belum ada pengajuan pengadaan sekolah.
        </div>
        @endif
    </div>
</div>
@endsection
