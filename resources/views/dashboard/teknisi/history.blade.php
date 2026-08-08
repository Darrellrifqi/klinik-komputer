@extends('layouts.dashboard')
@section('title', 'Riwayat Servis - Teknisi')
@section('page_title', 'Riwayat Servis Teknisi')
@section('page_subtitle', 'Daftar seluruh tiket servis yang sudah diambil oleh customer atau dibatalkan')

@section('sidebar_nav')
<a href="{{ route('dashboard.teknisi') }}" class="{{ request()->routeIs('dashboard.teknisi') && !request()->routeIs('dashboard.teknisi.history*') ? 'active' : '' }}">
    <span class="nav-icon">Tiket Aktif</span>
</a>
<a href="{{ route('dashboard.teknisi.history') }}" class="{{ request()->routeIs('dashboard.teknisi.history*') ? 'active' : '' }}">
    <span class="nav-icon">Riwayat Servis</span>
</a>
<a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="nav-icon">Pengaturan Profile</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('service.track') }}"><span class="nav-icon">Tracking Publik</span></a>
<a href="{{ route('home') }}"><span class="nav-icon">Beranda</span></a>
@endsection

@section('content')

{{-- ════════ SUMMARY STATS RIWAYAT ════════ --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
    {{-- Total Riwayat --}}
    <div style="padding: 14px 18px; display: flex; align-items: center; gap: 12px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
        <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(22, 163, 74, 0.12); color: #16a34a;">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div>
            <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $stats['total'] }}</div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; margin-top: 2px;">Total Riwayat Servis</div>
        </div>
    </div>

    {{-- Sudah Diambil --}}
    <div style="padding: 14px 18px; display: flex; align-items: center; gap: 12px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
        <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(22, 163, 74, 0.12); color: #16a34a;">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div>
            <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $stats['sudah_diambil'] }}</div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; margin-top: 2px;">Sudah Diambil</div>
        </div>
    </div>

    {{-- Dibatalkan --}}
    <div style="padding: 14px 18px; display: flex; align-items: center; gap: 12px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
        <div style="width: 40px; height: 40px; min-width: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(239, 68, 68, 0.12); color: #ef4444;">
            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div>
            <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $stats['cancelled'] }}</div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; margin-top: 2px;">Dibatalkan</div>
        </div>
    </div>
</div>

{{-- ════════ FILTER & SEARCH BAR ════════ --}}
<div class="dash-card" style="margin-bottom: 20px; padding: 16px;">
    <form method="GET" action="{{ route('dashboard.teknisi.history') }}" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        {{-- Search Input --}}
        <div style="flex: 1; min-width: 240px; position: relative;">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama customer, no. tiket, no. telp, unit, kendala..."
                   class="form-control"
                   style="padding-left: 38px; height: 40px; font-size: 0.85rem;">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"
                 style="position: absolute; left: 12px; top: 11px; color: var(--text-muted);">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>

        {{-- Status Filter --}}
        <div style="min-width: 180px;">
            <select name="status" class="form-control" style="height: 40px; font-size: 0.85rem;" onchange="this.form.submit()">
                <option value="">Semua Status Riwayat</option>
                <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        {{-- Action Buttons --}}
        <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 18px; font-size: 0.85rem; font-weight: 700;">
            Cari
        </button>
        @if(request()->filled('search') || request()->filled('status'))
        <a href="{{ route('dashboard.teknisi.history') }}" class="btn btn-outline" style="height: 40px; padding: 0 14px; font-size: 0.85rem; display: inline-flex; align-items: center;">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- ════════ UNIFIED HISTORY TABLE ════════ --}}
<div class="dash-card" style="background: var(--bg-card); border-radius: var(--radius-sm); overflow: hidden;">
    <div class="dash-card-header" style="background: var(--bg-alt); padding: 12px 18px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 0.95rem; font-weight: 700; margin: 0; color: var(--text-primary);">Daftar Riwayat Servis</h3>
        <span class="badge" style="background: var(--primary); color: #fff; font-size: 0.72rem; padding: 3px 10px; border-radius: 12px; font-weight: 800;">{{ $tickets->total() }} Tiket</span>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @if($tickets->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">No. Tiket</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Customer</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Unit Perangkat</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Deskripsi Masalah</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Status</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Selesai</th>
                        <th style="padding: 10px 14px; font-size: 0.72rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td style="padding: 10px 14px;">
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $ticket->ticket_number }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">#{{ $ticket->queue_number }}</div>
                        </td>
                        <td style="padding: 10px 14px;">
                            <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                <span style="font-weight:600; font-size:0.85rem;">{{ $ticket->customer_name }}</span>
                                @if($ticket->is_member)
                                    @if($ticket->member_type_label === 'Member Pengadaan')
                                        <span class="badge" style="background:#7c3aed; color:#fff; font-size:0.62rem; padding:1px 6px; border-radius:4px; font-weight:800; letter-spacing:0.02em;">MEMBER PENGADAAN</span>
                                    @else
                                        <span class="badge" style="background:#0284c7; color:#fff; font-size:0.62rem; padding:1px 6px; border-radius:4px; font-weight:800; letter-spacing:0.02em;">MEMBER</span>
                                    @endif
                                @endif
                            </div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">{{ $ticket->customer_phone }}</div>
                        </td>
                        <td style="padding: 10px 14px;">
                            <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td style="padding: 10px 14px; max-width:200px;">
                            <div style="font-size:0.8rem; color:var(--text-secondary); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $ticket->damage_description }}">
                                {{ $ticket->damage_description }}
                            </div>
                        </td>
                        <td style="padding: 10px 14px;">
                            <div style="display:flex; align-items:center; gap:6px;">
                                <div class="status-dot {{ $ticket->status }}"></div>
                                <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.72rem;">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td style="padding: 10px 14px; font-size:0.78rem; color:var(--text-muted);">{{ $ticket->updated_at->format('d M H:i') }}</td>
                        <td style="padding: 10px 14px;">
                            <a href="{{ route('dashboard.teknisi.show', $ticket) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 14px 18px;">
            {{ $tickets->links() }}
        </div>
        @else
        <div style="padding: 40px 18px; text-align: center; color: var(--text-muted);">
            Tidak ada data riwayat servis yang cocok dengan pencarian / filter Anda.
        </div>
        @endif
    </div>
</div>

@endsection
