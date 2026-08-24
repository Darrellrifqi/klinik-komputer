@extends('layouts.dashboard')
@php
    $schoolKits  = $kits->where('is_regular', false);
    $regularKits = $kits->where('is_regular', true);

    $isPengadaan = $schoolKits->isNotEmpty();
    $isUmum      = !$isPengadaan && ($regularKits->isNotEmpty() || !empty(auth()->user()->nik));
    
    // Get plan string from regular laptop kit if available
    $planRecord = $regularKits->first();
    $rawPlan    = $planRecord ? $planRecord->membership_plan : null;

    if ($isPengadaan) {
        $userPlanShort    = 'Member Pengadaan';
        $userPlanBadge    = 'Member Pengadaan Sekolah';
        $userPlanTag      = 'BENEFIT LAPTOP ACP / PENGADAAN';
        $titleName        = 'Dashboard Member Pengadaan';
        $subtitle         = 'Pantau status servis unit & laptop perakitan sekolah Anda';
        
        $cleaningQuotaMax = 2;
        $osQuotaMax       = 2;
        $cleaningType     = 'Deep Care Cleaning';
        $onsiteBenefit    = null;
    } elseif ($isUmum) {
        if ($rawPlan === 'Basic Priority') {
            $userPlanShort    = 'Member Basic Priority';
            $userPlanBadge    = 'Member Basic Priority';
            $userPlanTag      = 'BENEFIT BASIC PRIORITY';
            $titleName        = 'Dashboard Member Basic Priority';
            $cleaningQuotaMax = 2;
            $osQuotaMax       = 1;
            $cleaningType     = 'Essential Cleaning';
            $onsiteBenefit    = null;
        } elseif ($rawPlan === 'Silver Priority') {
            $userPlanShort    = 'Member Silver Priority';
            $userPlanBadge    = 'Member Silver Priority';
            $userPlanTag      = 'BENEFIT SILVER PRIORITY';
            $titleName        = 'Dashboard Member Silver Priority';
            $cleaningQuotaMax = 2;
            $osQuotaMax       = 2;
            $cleaningType     = 'Deep Care Cleaning';
            $onsiteBenefit    = null;
        } elseif ($rawPlan === 'Gold Priority') {
            $userPlanShort    = 'Member Gold Priority';
            $userPlanBadge    = 'Member Gold Priority';
            $userPlanTag      = 'BENEFIT GOLD PRIORITY';
            $titleName        = 'Dashboard Member Gold Priority';
            $cleaningQuotaMax = 3;
            $osQuotaMax       = 3;
            $cleaningType     = 'Deep Care Cleaning';
            $onsiteBenefit    = 'GRATIS Onsite Service Bandung Raya';
        } elseif ($rawPlan === 'Platinum Priority') {
            $userPlanShort    = 'Member Platinum Priority';
            $userPlanBadge    = 'Member Platinum Priority';
            $userPlanTag      = 'BENEFIT PLATINUM PRIORITY';
            $titleName        = 'Dashboard Member Platinum Priority';
            $cleaningQuotaMax = 4;
            $osQuotaMax       = 4;
            $cleaningType     = 'Deep Care Cleaning';
            $onsiteBenefit    = 'GRATIS Onsite Service + Prioritas Jadwal';
        } else {
            $userPlanShort    = 'Member Mandiri / Umum';
            $userPlanBadge    = 'Member Umum';
            $userPlanTag      = 'BENEFIT MEMBER UMUM';
            $titleName        = 'Dashboard Member Umum';
            $cleaningQuotaMax = 2;
            $osQuotaMax       = 2;
            $cleaningType     = 'Deep Care Cleaning';
            $onsiteBenefit    = null;
        }
        $subtitle = 'Pantau status servis & benefit keanggotaan ' . ($rawPlan ?: 'Member Umum') . ' Anda';
    } else {
        $userPlanShort    = 'Non-Member';
        $userPlanBadge    = 'Non-Member';
        $userPlanTag      = 'NON-MEMBER';
        $titleName        = 'Dashboard Customer';
        $subtitle         = 'Pantau status servis unit Anda';
        $cleaningQuotaMax = 0;
        $osQuotaMax       = 0;
        $cleaningType     = 'Deep Care Cleaning';
        $onsiteBenefit    = null;
    }
@endphp
@section('title', $titleName . ' | Klinik Komputer')
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
<a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="nav-icon">Pengaturan Profile</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('products') }}">
    <span class="nav-icon">Katalog Produk</span>
</a>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
@endsection

@section('content')

<style>
    @media (max-width: 768px) {
        .dashboard-content {
            padding: 10px 12px !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        /* 1. Hero Profile Card */
        .member-profile-header {
            background: #ffffff !important;
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            padding: 12px 14px !important;
            margin-bottom: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
        }
        .member-profile-header .user-contact-info {
            margin-left: 0 !important;
            display: block !important;
            margin-top: 2px !important;
            word-break: break-word !important;
            font-size: 0.7rem !important;
        }

        /* 2. Equal 2-Column Stats Grid */
        .stats-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
            margin-bottom: 14px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .stat-card {
            padding: 10px 12px !important;
            gap: 8px !important;
            border-radius: 10px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .stat-card-membership {
            display: none !important; /* Hide redundant 3rd card on mobile so 2 main stats form a clean 50-50 grid */
        }
        .stat-icon {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
        }
        .stat-info .stat-num {
            font-size: 1.15rem !important;
        }
        .stat-info .stat-label {
            font-size: 0.62rem !important;
        }

        /* 3. Member Benefits Grid */
        .member-benefits-grid {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
            margin-bottom: 16px !important;
        }
        .member-benefits-grid > div {
            padding: 14px !important;
            border-radius: 12px !important;
        }
        .quota-slots-grid {
            display: flex !important;
            flex-direction: column !important;
            gap: 6px !important;
        }

        /* 4. Non-Member Upgrade Banner */
        .non-member-banner {
            flex-direction: column !important;
            text-align: center !important;
            padding: 12px 14px !important;
            gap: 10px !important;
            border-radius: 10px !important;
        }
        .non-member-banner a {
            width: 100% !important;
            text-align: center !important;
        }
    }
</style>

<!-- Flash Notifications -->
@if(session('success'))
<div class="alert alert-success" style="margin-bottom: 14px; border-radius: 8px;">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error" style="margin-bottom: 14px; border-radius: 8px;">{{ session('error') }}</div>
@endif

@php
    $registeredSn = $regularKits->first()?->axioo_serial_number;
@endphp

{{-- 1. Hero User Profile Card --}}
<div class="member-profile-header" style="background: #ffffff; border: 1px solid var(--border); border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; background: linear-gradient(135deg, var(--primary), #15803d);">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-primary);">
                Selamat datang, {{ auth()->user()->name }}
            </div>
            <div class="user-contact-info" style="font-size: 0.73rem; color: var(--text-muted); margin-top: 1px;">
                ({{ auth()->user()->phone ?? 'Belum ada No. HP' }} &bull; {{ auth()->user()->email }})
            </div>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        @if(!empty($registeredSn))
            <span class="badge" style="padding: 5px 12px; font-size: 0.75rem; border-radius: 6px; font-weight: 800; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-family: monospace; letter-spacing: 0.04em;">
                SN TERDAFTAR: {{ $registeredSn }}
            </span>
        @elseif($isPengadaan || $isUmum)
            <span class="badge badge-success" style="padding: 5px 12px; font-size: 0.75rem; border-radius: 6px; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase;">
                {{ $userPlanBadge }}
            </span>
        @else
            <span class="badge badge-primary" style="padding: 4px 10px; font-size: 0.68rem; border-radius: 6px; font-weight: 700; background: var(--bg-alt); color: var(--text-muted); border: 1px solid var(--border);">
                Non-Member
            </span>
        @endif
    </div>
</div>

<!-- 2. Equal Stats Grid -->
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
    <div class="stat-card stat-card-membership">
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
    @elseif($isUmum)
    <div class="stat-card stat-card-membership">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num" style="font-size: 0.88rem; font-family: monospace;">{{ auth()->user()->nik ?: 'MEMBER' }}</div>
            <div class="stat-label">{{ $userPlanShort }}</div>
            @if($registeredSn)
            <div style="font-size: 0.72rem; color: #0284c7; font-weight: 700; font-family: monospace; margin-top: 2px;">SN: {{ $registeredSn }}</div>
            @endif
        </div>
    </div>
    @else
    <div class="stat-card stat-card-membership">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-num" style="font-size: 0.85rem; font-weight: 700;">Non-Member</div>
            <div class="stat-label">Status Keanggotaan</div>
        </div>
    </div>
    @endif
</div>

{{-- Member Benefit Cards: Deep Care Cleaning & Essential Instalasi OS --}}
@if($isPengadaan || $isUmum)
@php
    $tuneUpCount     = auth()->user()->tuneUpCount();
    $osInstallCount  = auth()->user()->osInstallCount();
    $tuneUpResetDate  = auth()->user()->tuneUpResetDate();
    
    $tuneQuotaFull = $tuneUpCount >= $cleaningQuotaMax;
    $osQuotaFull   = $osInstallCount >= $osQuotaMax;
@endphp

<div class="member-benefits-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-bottom: 24px;">

    <!-- Benefit Bar 1: Cleaning -->
    <div style="position: relative; background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; box-shadow: 0 4px 18px rgba(0,0,0,0.03); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="position:absolute; top:0; left:0; right:0; height:3px; background: {{ $tuneQuotaFull ? 'linear-gradient(90deg,#f87171,#ef4444)' : 'linear-gradient(90deg, var(--primary), #86efac)' }}; border-radius: 14px 14px 0 0;"></div>

        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-top: 4px;">
                <div>
                    <div style="font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--primary); margin-bottom: 2px;">
                        {{ $userPlanTag }}
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-primary); margin: 0 0 2px 0;">
                        Gratis {{ $cleaningType }} Unit ({{ $cleaningQuotaMax }}x / Periode)
                    </h4>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">
                        Periode {{ $tuneUpResetDate->copy()->subYear()->format('d M Y') }} &ndash; {{ $tuneUpResetDate->format('d M Y') }}
                    </div>
                </div>

                {{-- Progress Counter Badge --}}
                <div style="text-align: right;">
                    <div style="font-size: 1.25rem; font-weight: 900; color: {{ $tuneQuotaFull ? '#dc2626' : 'var(--primary)' }}; line-height: 1;">
                        {{ $tuneUpCount }}<span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted);">/{{ $cleaningQuotaMax }}</span>
                    </div>
                    <div style="font-size: 0.65rem; font-weight: 600; color: {{ $tuneQuotaFull ? '#dc2626' : 'var(--text-muted)' }}; margin-top: 2px;">
                        {{ $tuneQuotaFull ? 'Kuota Habis' : ($cleaningQuotaMax - $tuneUpCount . 'x tersisa') }}
                    </div>
                </div>
            </div>

            {{-- Dynamic Quota Slot Cards --}}
            <div class="quota-slots-grid" style="display: grid; grid-template-columns: repeat({{ min($cleaningQuotaMax, 4) }}, 1fr); gap: 10px; margin-bottom: 8px;">
                @for($i = 1; $i <= $cleaningQuotaMax; $i++)
                    @php $isDone = $tuneUpCount >= $i; @endphp
                    <div style="position: relative; border-radius: 10px; overflow: hidden; background: {{ $isDone ? 'rgba(95,138,99,0.07)' : 'var(--bg-alt)' }}; border: 1.5px solid {{ $isDone ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.3s ease;">
                        <div style="position:relative; padding: 10px 12px; display:flex; align-items:center; gap:8px;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $isDone ? 'var(--primary)' : 'var(--border-light)' }}; border: 2px solid {{ $isDone ? 'var(--primary)' : 'var(--border)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                @if($isDone)
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @else
                                <span style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted);">{{ $i }}</span>
                                @endif
                            </div>
                            <div>
                                <div style="font-size: 0.72rem; font-weight: 700; color: {{ $isDone ? 'var(--primary)' : 'var(--text-muted)' }};">Cleaning #{{ $i }}</div>
                                <div style="font-size: 0.62rem; color: {{ $isDone ? '#16a34a' : 'var(--text-muted)' }}; font-weight: {{ $isDone ? '600' : '400' }};">
                                    {{ $isDone ? '✓ Sudah dipakai' : 'Belum diklaim' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        @if($tuneQuotaFull)
        <div style="margin-top: 8px; text-align:center; font-size: 0.7rem; color: #dc2626; font-weight: 700; padding: 5px 8px; background: rgba(220,38,38,0.05); border-radius: 6px; border: 1px solid rgba(220,38,38,0.15);">
            ⚠ Kuota periode ini telah habis
        </div>
        @endif
    </div>

    <!-- Benefit Bar 2: Essential Instalasi OS -->
    <div style="position: relative; background: #ffffff; border: 1px solid var(--border); border-radius: 14px; padding: 20px 22px; box-shadow: 0 4px 18px rgba(0,0,0,0.03); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="position:absolute; top:0; left:0; right:0; height:3px; background: {{ $osQuotaFull ? 'linear-gradient(90deg,#f87171,#ef4444)' : 'linear-gradient(90deg, #3b82f6, #60a5fa)' }}; border-radius: 14px 14px 0 0;"></div>

        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; padding-top: 4px;">
                <div>
                    <div style="font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #2563eb; margin-bottom: 2px;">
                        {{ $userPlanTag }}
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-primary); margin: 0 0 2px 0;">
                        Gratis Essential Instalasi OS ({{ $osQuotaMax }}x / Periode)
                    </h4>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">
                        Periode {{ $tuneUpResetDate->copy()->subYear()->format('d M Y') }} &ndash; {{ $tuneUpResetDate->format('d M Y') }}
                    </div>
                </div>

                {{-- Progress Counter Badge --}}
                <div style="text-align: right;">
                    <div style="font-size: 1.25rem; font-weight: 900; color: {{ $osQuotaFull ? '#dc2626' : '#2563eb' }}; line-height: 1;">
                        {{ $osInstallCount }}<span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted);">/{{ $osQuotaMax }}</span>
                    </div>
                    <div style="font-size: 0.65rem; font-weight: 600; color: {{ $osQuotaFull ? '#dc2626' : 'var(--text-muted)' }}; margin-top: 2px;">
                        {{ $osQuotaFull ? 'Kuota Habis' : ($osQuotaMax - $osInstallCount . 'x tersisa') }}
                    </div>
                </div>
            </div>

            {{-- Dynamic Quota Slot Cards --}}
            <div class="quota-slots-grid" style="display: grid; grid-template-columns: repeat({{ min($osQuotaMax, 4) }}, 1fr); gap: 10px; margin-bottom: 8px;">
                @for($j = 1; $j <= $osQuotaMax; $j++)
                    @php $isOsDone = $osInstallCount >= $j; @endphp
                    <div style="position: relative; border-radius: 10px; overflow: hidden; background: {{ $isOsDone ? 'rgba(59,130,246,0.07)' : 'var(--bg-alt)' }}; border: 1.5px solid {{ $isOsDone ? '#2563eb' : 'var(--border)' }}; transition: all 0.3s ease;">
                        <div style="position:relative; padding: 10px 12px; display:flex; align-items:center; gap:8px;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $isOsDone ? '#2563eb' : 'var(--border-light)' }}; border: 2px solid {{ $isOsDone ? '#2563eb' : 'var(--border)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                @if($isOsDone)
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @else
                                <span style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted);">{{ $j }}</span>
                                @endif
                            </div>
                            <div>
                                <div style="font-size: 0.72rem; font-weight: 700; color: {{ $isOsDone ? '#2563eb' : 'var(--text-muted)' }};">Instalasi OS #{{ $j }}</div>
                                <div style="font-size: 0.62rem; color: {{ $isOsDone ? '#2563eb' : 'var(--text-muted)' }}; font-weight: {{ $isOsDone ? '600' : '400' }};">
                                    {{ $isOsDone ? '✓ Sudah dipakai' : 'Belum diklaim' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        @if($osQuotaFull)
        <div style="margin-top: 8px; text-align:center; font-size: 0.7rem; color: #dc2626; font-weight: 700; padding: 5px 8px; background: rgba(220,38,38,0.05); border-radius: 6px; border: 1px solid rgba(220,38,38,0.15);">
            ⚠ Kuota periode ini telah habis
        </div>
        @endif
    </div>

</div>

@else
<div class="non-member-banner" style="background: #ffffff; border: 1px solid var(--border); border-radius: 8px; padding: 10px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
    <div style="font-size: 0.78rem; color: var(--text-secondary);">
        💡 <strong style="color: var(--text-primary);">Ingin benefit 2x Deep Care Cleaning &amp; 2x Essential OS gratis per tahun?</strong> Hubungi CS untuk pendaftaran Member Umum.
    </div>
    <a href="https://wa.me/6285103051000?text=Halo%20Klinik%20Komputer,%20saya%20tertarik%20menjadi%20Member%20Umum" target="_blank" class="btn btn-primary btn-sm" style="padding: 5px 14px; font-weight: 700; font-size: 0.72rem; border-radius: 6px; flex-shrink: 0;">
        Tanya Member CS
    </a>
</div>
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
