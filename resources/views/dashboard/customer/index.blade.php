@extends('layouts.dashboard')
@php
    $schoolKits  = $kits->where('is_regular', false);
    $isPengadaan = $schoolKits->isNotEmpty();
    $isUmum      = !$isPengadaan && (auth()->user()->nik != null || auth()->user()->status === 'active');
    
    if ($isPengadaan) {
        $titleName = 'Dashboard Member Pengadaan';
        $subtitle  = 'Pantau status servis unit & laptop perakitan sekolah Anda';
    } elseif ($isUmum) {
        $titleName = 'Dashboard Member Umum';
        $subtitle  = 'Pantau status servis & benefit tune-up gratis keanggotaan Anda';
    } else {
        $titleName = 'Dashboard Customer';
        $subtitle  = 'Pantau status servis unit Anda';
    }
@endphp
@section('title', $titleName . ' — Klinik Komputer')
@section('page_title', 'Dashboard Saya')
@section('page_subtitle', $subtitle)

@section('sidebar_nav')
<a href="{{ route('dashboard.customer') }}" class="{{ request()->routeIs('dashboard.customer') ? 'active' : '' }}">
    <span class="nav-icon">Tiket & Laptop Saya</span>
</a>
<a href="{{ route('dashboard.customer.chat') }}" class="{{ request()->routeIs('dashboard.customer.chat*') ? 'active' : '' }}">
    <span class="nav-icon">Hubungi CS (Chat)</span>
    @php $unreadCust = \App\Models\Chat::where('customer_id', auth()->id())->where('unread_by_customer', true)->count(); @endphp
    @if($unreadCust > 0)<span class="badge-count">{{ $unreadCust }}</span>@endif
</a>
<a href="{{ route('service.booking') }}">
    <span class="nav-icon">Booking Servis Baru</span>
</a>
<a href="{{ route('service.track') }}">
    <span class="nav-icon">Cek Status Tiket</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('products') }}">
    <span class="nav-icon">Katalog Produk</span>
</a>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
@endsection

@section('topbar_actions')
<a href="{{ route('service.booking') }}" class="btn btn-primary btn-sm">Booking Baru</a>
@endsection

@section('content')
<!-- Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $tickets->count() }}</div>
            <div class="stat-label">Total Tiket Servis</div>
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
            <div class="stat-num">{{ $tickets->whereIn('status',['waiting','checking','checked','rma'])->count() }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    @if($isPengadaan)
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="2" y1="20" x2="22" y2="20"></line>
                <line x1="12" y1="17" x2="12" y2="20"></line>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num">{{ $schoolKits->count() }}</div>
            <div class="stat-label">Laptop Perakitan</div>
        </div>
    </div>
    @else
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num" style="font-size: 0.88rem; font-family: monospace;">{{ auth()->user()->nik ?? 'MEMBER' }}</div>
            <div class="stat-label">Member Mandiri / Umum</div>
        </div>
    </div>
    @endif
</div>

@if($isPengadaan || $isUmum)
{{-- Member Tune-Up Benefit Card — Game Goals Style (Light) --}}
@php
    $tuneQuotaFull = $tuneUpCount >= 2;
    $slot1Done     = $tuneUpCount >= 1;
    $slot2Done     = $tuneUpCount >= 2;
@endphp
<div style="background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: 0 2px 12px rgba(95,138,99,0.08);">

    {{-- Subtle top accent stripe --}}
    <div style="position:absolute; top:0; left:0; right:0; height:3px; background: {{ $tuneQuotaFull ? 'linear-gradient(90deg,#f87171,#ef4444)' : 'linear-gradient(90deg, var(--primary), #86efac)' }}; border-radius: 14px 14px 0 0;"></div>

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-top: 4px;">
        <div>
            <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 3px;">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path>
                </svg>
                <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary);">
                    Benefit {{ $isPengadaan ? 'Member Pengadaan' : 'Member Umum' }}
                </span>
            </div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-primary); line-height: 1.2;">Gratis Tune-Up Unit</div>
            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">Periode {{ $tuneUpResetDate->copy()->subYear()->format('d M Y') }} – {{ $tuneUpResetDate->format('d M Y') }}</div>
        </div>
        <div style="text-align: right;">
            <div style="font-family: monospace; font-size: 2rem; font-weight: 900; line-height: 1; color: {{ $tuneQuotaFull ? '#dc2626' : 'var(--primary)' }};">{{ $tuneUpCount }}<span style="font-size: 1rem; font-weight: 400; color: var(--text-muted);">/2</span></div>
            <div style="font-size: 0.65rem; color: {{ $tuneQuotaFull ? '#dc2626' : 'var(--text-muted)' }}; margin-top: 2px; font-weight: 600;">
                {{ $tuneQuotaFull ? 'Kuota habis' : ($tuneUpCount == 0 ? 'Belum digunakan' : 'Sisa 1x lagi') }}
            </div>
        </div>
    </div>

    {{-- Game-style milestone slots --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">

        {{-- Slot 1 --}}
        <div style="position: relative; border-radius: 10px; overflow: hidden; background: {{ $slot1Done ? 'rgba(95,138,99,0.07)' : 'var(--bg-alt)' }}; border: 1.5px solid {{ $slot1Done ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.3s ease;">
            @if($slot1Done)
            <div style="position:absolute; top:0; left:-100%; width:60%; height:100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent); animation: shimmer-light 2.5s infinite;"></div>
            @endif
            <div style="position:relative; padding: 12px 14px; display:flex; align-items:center; gap:10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $slot1Done ? 'var(--primary)' : 'var(--border-light)' }}; border: 2px solid {{ $slot1Done ? 'var(--primary)' : 'var(--border)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $slot1Done ? 'box-shadow: 0 0 0 3px rgba(95,138,99,0.15);' : '' }}">
                    @if($slot1Done)
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else
                    <span style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted);">1</span>
                    @endif
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: {{ $slot1Done ? 'var(--primary)' : 'var(--text-muted)' }};">Tune-Up #1</div>
                    <div style="font-size: 0.65rem; color: {{ $slot1Done ? '#16a34a' : 'var(--text-muted)' }}; font-weight: {{ $slot1Done ? '600' : '400' }};">
                        {{ $slot1Done ? '✓ Sudah digunakan' : 'Belum diklaim' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Slot 2 --}}
        <div style="position: relative; border-radius: 10px; overflow: hidden; background: {{ $slot2Done ? 'rgba(95,138,99,0.07)' : 'var(--bg-alt)' }}; border: 1.5px solid {{ $slot2Done ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.3s ease;">
            @if($slot2Done)
            <div style="position:absolute; top:0; left:-100%; width:60%; height:100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent); animation: shimmer-light 2.5s infinite 0.4s;"></div>
            @endif
            <div style="position:relative; padding: 12px 14px; display:flex; align-items:center; gap:10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $slot2Done ? 'var(--primary)' : 'var(--border-light)' }}; border: 2px solid {{ $slot2Done ? 'var(--primary)' : 'var(--border)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $slot2Done ? 'box-shadow: 0 0 0 3px rgba(95,138,99,0.15);' : '' }}">
                    @if($slot2Done)
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    @else
                    <span style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted);">2</span>
                    @endif
                </div>
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: {{ $slot2Done ? 'var(--primary)' : 'var(--text-muted)' }};">Tune-Up #2</div>
                    <div style="font-size: 0.65rem; color: {{ $slot2Done ? '#16a34a' : 'var(--text-muted)' }}; font-weight: {{ $slot2Done ? '600' : '400' }};">
                        {{ $slot2Done ? '✓ Sudah digunakan' : 'Belum diklaim' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- XP-style progress bar --}}
    <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; display:flex; align-items:center; gap:14px;">
        <div style="flex:1;">
            <div style="width:100%; height:8px; background: var(--border-light); border-radius:99px; overflow:hidden; position:relative;">
                <div style="width: {{ min(100, ($tuneUpCount/2)*100) }}%; height:100%; background: {{ $tuneQuotaFull ? 'linear-gradient(90deg,#f87171,#dc2626)' : 'linear-gradient(90deg, var(--primary), #86efac)' }}; border-radius:99px; transition: width 0.8s cubic-bezier(0.34,1.56,0.64,1);"></div>
                {{-- Midpoint marker --}}
                <div style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:2px; height:100%; background: rgba(0,0,0,0.12);"></div>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:4px; font-size:0.62rem; color:var(--text-muted);">
                <span>0</span><span>1</span><span>2</span>
            </div>
        </div>
        <div style="font-size: 0.7rem; color: var(--text-muted); white-space:nowrap; text-align:right;">
            Reset pada<br><strong style="color: var(--text-secondary);">{{ $tuneUpResetDate->format('d M Y') }}</strong>
        </div>
    </div>

    @if($tuneQuotaFull)
    <div style="margin-top: 10px; text-align:center; font-size: 0.72rem; color: #dc2626; font-weight: 700; padding: 6px 10px; background: rgba(220,38,38,0.05); border-radius: 6px; border: 1px solid rgba(220,38,38,0.15);">
        ⚠ Kuota Tune-Up Gratis periode ini telah habis — Tune-Up selanjutnya berbayar
    </div>
    @endif
</div>

<style>
@keyframes shimmer-light {
    0%   { left: -100%; }
    100% { left: 200%; }
}
</style>
@endif

<!-- Laptop Perakitan Sekolah (Khusus Member Pengadaan) -->
@if($isPengadaan && $schoolKits->count() > 0)
<div class="dash-card" style="margin-bottom: 20px; border-color: var(--primary);">
    <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 16px;">
        <h3 style="color: var(--primary);">LAPTOP PERAKITAN SEKOLAH</h3>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @foreach($schoolKits as $kit)
        <div style="padding: 16px 20px; border-bottom: {{ $loop->last ? 'none' : '1px solid var(--border)' }};">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; margin-bottom: 14px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="font-family: monospace; font-size: 0.9rem; font-weight: 800; color: var(--primary);">{{ $kit->member_id }}</div>
                        <span class="badge badge-primary" style="font-size: 0.6rem; padding: 2px 5px;">SEKOLAH</span>
                    </div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">Terdaftar atas nama: <strong>{{ $kit->student_name }}</strong></div>
                    
                    @if($kit->warranty_expires)
                    <div style="margin-top: 6px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span class="badge badge-{{ $kit->warranty_status_color }}" style="font-size: 0.65rem; padding: 3px 6px;">{{ $kit->warranty_status_label }}</span>
                        <span style="font-size: 0.72rem; color: var(--text-secondary);">
                            Masa Garansi: <strong>{{ $kit->warranty_start->format('d M Y') }}</strong> s/d <strong>{{ $kit->warranty_expires->format('d M Y') }}</strong>
                        </span>
                    </div>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                        {{ $kit->unit_model ?: ($kit->order ? $kit->order->axioo_model : 'Axioo Laptop') }}
                        @if($kit->axioo_serial_number)
                            <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">({{ $kit->axioo_serial_number }})</span>
                        @endif
                    </div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">
                        {{ $kit->institution ?: ($kit->order ? $kit->order->school_name : ($kit->purchase_store ? 'Pembelian: ' . $kit->purchase_store : '')) }}
                    </div>
                </div>
            </div>

            {{-- Warranty Expiration Alert Reminder --}}
            @if($kit->warranty_status === 'hampir_habis')
            <div class="alert alert-warning" style="margin-bottom: 14px; font-size: 0.8rem; padding: 10px 14px; align-items: center; border-radius: var(--radius-sm);">
                <div>
                    <strong style="text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.03em;">⚠️ Peringatan Garansi Hampir Habis</strong><br>
                    Masa berlaku garansi perangkat Anda tersisa <strong>{{ $kit->warranty_days_left }} hari</strong> lagi (berakhir pada {{ $kit->warranty_expires->format('d F Y') }}). Segera hubungi kami jika ada keluhan sebelum garansi habis.
                </div>
            </div>
            @endif

            <!-- Components Grid -->
            @if($kit->components && $kit->components->count() > 0)
            <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: 6px; padding: 14px;">
                <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 10px; border-bottom: 1px solid var(--border); padding-bottom: 4px;">Serial Number Komponen Terdaftar</div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                    @foreach($kit->components as $comp)
                    <div style="padding: 8px 10px; background: #fff; border: 1px solid var(--border-light); border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">{{ $comp->component_name }}</span>
                        <code style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: var(--text-primary);">{{ $comp->serial_number }}</code>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Tickets -->
<div class="dash-card">
    <div class="dash-card-header">
        <h3>Tiket Servis Saya</h3>
        <a href="{{ route('service.booking') }}" class="btn btn-primary btn-sm">Booking Baru</a>
    </div>
    <div class="dash-card-body" style="padding:0;">
        @if($tickets->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Unit</th>
                        <th>Kerusakan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary);">{{ $ticket->ticket_number }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td style="max-width:200px;">
                            <div style="font-size:0.85rem; color:var(--text-secondary); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $ticket->damage_description }}
                            </div>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <div class="status-dot {{ $ticket->status }}"></div>
                                <span class="badge badge-{{ $ticket->status_color }}">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.8rem; color:var(--text-muted);">
                            {{ $ticket->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}"
                               class="btn btn-outline btn-sm">Track</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding: 48px 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px; background: #fff;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(95, 138, 99, 0.06); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            </div>
            <div>
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin: 0 0 6px 0;">Belum Ada Tiket Servis</h4>
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; max-width: 360px; line-height: 1.45;">Anda belum memiliki riwayat perbaikan perangkat. Silakan klik tombol <strong>Booking Baru</strong> di pojok kanan atas untuk mengajukan pendaftaran servis baru.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
