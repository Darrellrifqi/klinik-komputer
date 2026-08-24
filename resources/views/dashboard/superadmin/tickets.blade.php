@extends('layouts.dashboard')
@section('title', 'Tiket Servis Aktif | Super Admin')
@section('page_title', 'Tiket Servis Aktif')
@section('page_subtitle', 'Pantau dan kelola seluruh alur tiket servis aktif berdasarkan tahapan proses')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')

<style>
    .sa-stats-grid-top {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    .sa-stats-grid-bottom {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .sa-desktop-table-wrap {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
    .sa-mobile-ticket-list {
        display: none;
    }

    @media (max-width: 768px) {
        .dashboard-content {
            padding: 10px 12px !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        .sa-stats-grid-top {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }
        .sa-stats-grid-bottom {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }
        .sa-stat-card-item {
            padding: 8px 10px !important;
            gap: 8px !important;
            border-radius: 8px !important;
        }

        .sa-desktop-table-wrap {
            display: none !important;
        }
        .sa-mobile-ticket-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
            padding: 10px !important;
        }

        .sa-mobile-ticket-card {
            background: #ffffff !important;
            border: 1px solid var(--border-light) !important;
            border-radius: 10px !important;
            padding: 12px 14px !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
        }
        .sa-m-card-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            border-bottom: 1px dashed var(--border-light) !important;
            padding-bottom: 6px !important;
        }
        .sa-m-ticket-num {
            font-family: monospace !important;
            font-weight: 800 !important;
            color: var(--primary) !important;
            font-size: 0.88rem !important;
        }
        .sa-m-card-body {
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
            font-size: 0.8rem !important;
        }
        .sa-m-customer-name {
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            font-size: 0.88rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            flex-wrap: wrap !important;
        }
        .sa-m-unit-info {
            color: var(--text-secondary) !important;
            font-size: 0.78rem !important;
        }
        .sa-m-meta {
            font-size: 0.72rem !important;
            color: var(--text-muted) !important;
        }
        .sa-m-card-footer {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            border-top: 1px solid var(--border-light) !important;
            padding-top: 8px !important;
            margin-top: 4px !important;
        }
        .sa-m-actions {
            display: flex !important;
            gap: 6px !important;
            align-items: center !important;
        }
    }
</style>

{{-- ════════ STATS RINGKASAN ════════ --}}
<div style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 12px;">
    {{-- Baris 1: 4 Kolom di Desktop / 2 Kolom di Mobile --}}
    <div class="sa-stats-grid-top">
        {{-- Menunggu Unit --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(234, 179, 8, 0.12); color: #d97706;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->where('status','waiting')->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Menunggu Unit</div>
            </div>
        </div>

        {{-- Antrian Servis --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->where('status','unit_received')->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Antrian Servis</div>
            </div>
        </div>

        {{-- Pengecekan Teknisi --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->where('status','checking')->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Pengecekan</div>
            </div>
        </div>

        {{-- Konfirmasi User --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->whereIn('status',['konfirmasi_user','checked'])->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Konfirmasi User</div>
            </div>
        </div>
    </div>

    {{-- Baris 2: 2 Kolom --}}
    <div class="sa-stats-grid-bottom">
        {{-- Proses Service --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(100, 116, 139, 0.12); color: #475569;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->whereIn('status',['proses_service','rma','in_service'])->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Proses Service</div>
            </div>
        </div>

        {{-- Siap Diambil --}}
        <div class="sa-stat-card-item" style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(13, 148, 136, 0.12); color: #0d9488;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->whereIn('status',['done','siap_diambil'])->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Siap Diambil</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-body" style="padding:12px 16px;">
        <form method="GET" action="{{ route('admin.tickets') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <div style="position:relative; min-width: 240px; flex:1;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Tiket, No. Servis, Customer, WA, Merek..." class="form-control" style="padding:7px 12px; font-size:0.82rem; border-radius:6px;">
            </div>
            <select name="status" class="form-control" style="width:auto; padding:7px 12px; font-size:0.82rem; border-radius:6px;">
                <option value="">Semua Tahapan Status</option>
                <option value="waiting"       {{ request('status')==='waiting'       ? 'selected':'' }}>Menunggu Unit</option>
                <option value="unit_received" {{ request('status')==='unit_received' ? 'selected':'' }}>Antrian Servis</option>
                <option value="checking"      {{ request('status')==='checking'      ? 'selected':'' }}>Pengecekan Teknisi</option>
                <option value="konfirmasi_user" {{ request('status')==='konfirmasi_user' ? 'selected':'' }}>Konfirmasi User</option>
                <option value="proses_service" {{ request('status')==='proses_service' ? 'selected':'' }}>Proses Service</option>
                <option value="siap_diambil"  {{ in_array(request('status'), ['done','siap_diambil']) ? 'selected':'' }}>Siap Diambil</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="padding:7px 14px; font-weight:700;">Filter</button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline btn-sm" style="padding:7px 12px;">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- ════════ TIKET GROUPED SECTIONS (Per Sub-Bab Status) ════════ --}}
<div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px;">
    @foreach($groupedTickets as $statusKey => $group)
    <div class="dash-card" style="border-left: 4px solid {{ $group['color'] }}; background: var(--bg-card); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 0;">
        <div class="dash-card-header" style="background: var(--bg-alt); padding: 8px 14px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 0.9rem; font-weight: 700; margin: 0; color: var(--text-primary);">{{ $group['title'] }}</h3>
                <span class="badge" style="background: {{ $group['color'] }}; color: #fff; font-size: 0.7rem; padding: 2px 8px; border-radius: 12px; font-weight: 800;">{{ $group['tickets']->count() }}</span>
            </div>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($group['tickets']->count() > 0)

            {{-- 1. DESKTOP FULL TABLE VIEW --}}
            <div class="sa-desktop-table-wrap">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">No. Tiket / Servis</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Customer</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Unit Perangkat</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Dibuat Oleh</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Teknisi PJ</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Status Rinci</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: left; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Tanggal</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem; text-align: center; background: var(--bg-alt); border-bottom: 1px solid var(--border);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['tickets'] as $ticket)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td style="padding: 8px 14px;">
                                <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $ticket->ticket_number }}</div>
                                @if($ticket->airtable_service_number)
                                    <div style="font-family:monospace; font-size:0.74rem; color:#2563eb; font-weight:700; margin-top:2px;">
                                        No. Servis: {{ $ticket->airtable_service_number }}
                                    </div>
                                @endif
                                <div style="font-size:0.72rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }}</div>
                            </td>
                            <td style="padding: 8px 14px;">
                                <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                    <span style="font-weight:600; font-size:0.85rem;">{{ $ticket->customer_name }}</span>
                                    @if($ticket->is_member)
                                        <span class="badge" style="background:{{ $ticket->member_badge_bg }}; color:#fff; font-size:0.62rem; padding:1px 6px; border-radius:4px; font-weight:800; letter-spacing:0.02em;">{{ $ticket->member_type_label }}</span>
                                    @endif
                                </div>
                                <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">{{ $ticket->customer_phone }}</div>
                            </td>
                            <td style="padding: 8px 14px;">
                                <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                                <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                            </td>
                            <td style="padding: 8px 14px; font-size:0.82rem; color:var(--text-secondary);">
                                {{ $ticket->creator?->name ?? 'Online' }}
                            </td>
                            <td style="padding: 8px 14px; font-size:0.82rem; color:var(--text-secondary);">
                                {{ $ticket->technician?->name ?? '—' }}
                            </td>
                            <td style="padding: 8px 14px;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div class="status-dot {{ $ticket->status }}"></div>
                                    <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.72rem;">{{ $ticket->sub_status_label ?? $ticket->status_label }}</span>
                                </div>
                            </td>
                            <td style="padding: 8px 14px; font-size:0.78rem; color:var(--text-muted);">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td style="padding: 8px 14px; white-space: nowrap; text-align: center;">
                                <div style="display: flex; gap: 6px; align-items: center; justify-content: center;">
                                    <a href="{{ route('dashboard.cs.show', $ticket) }}" class="btn btn-outline btn-sm" style="padding:4px 8px; font-size:0.72rem; font-weight:700;">Detail</a>
                                    <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}" target="_blank" class="btn btn-outline btn-sm" style="padding:4px 8px; font-size:0.72rem;">Lacak</a>
                                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket {{ $ticket->ticket_number }} ini secara permanen dari database?');" style="margin: 0;">
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

            {{-- 2. DEDICATED MOBILE TICKET CARDS LIST --}}
            <div class="sa-mobile-ticket-list">
                @foreach($group['tickets'] as $ticket)
                <div class="sa-mobile-ticket-card">
                    <div class="sa-m-card-header">
                        <div>
                            <span class="sa-m-ticket-num">{{ $ticket->ticket_number }}</span>
                            <span style="font-size:0.7rem; color:var(--text-muted); margin-left:6px;">#{{ $ticket->queue_number }}</span>
                        </div>
                        <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.68rem; padding:2px 8px;">{{ $ticket->sub_status_label ?? $ticket->status_label }}</span>
                    </div>
                    <div class="sa-m-card-body">
                        <div class="sa-m-customer-name">
                            <span>{{ $ticket->customer_name }}</span>
                            @if($ticket->is_member)
                                <span class="badge" style="background:{{ $ticket->member_badge_bg }}; color:#fff; font-size:0.58rem; padding:1px 5px; border-radius:3px;">{{ $ticket->member_type_label }}</span>
                            @endif
                        </div>
                        <div class="sa-m-meta">{{ $ticket->customer_phone }}</div>
                        <div class="sa-m-unit-info" style="margin-top: 4px; font-weight: 600;">
                            {{ $ticket->brand }} {{ $ticket->model }} <small>({{ $ticket->unit_type }})</small>
                        </div>
                    </div>
                    <div class="sa-m-card-footer">
                        <span class="sa-m-meta">PJ: <strong>{{ $ticket->technician?->name ?? '—' }}</strong></span>
                        <div class="sa-m-actions">
                            <a href="{{ route('dashboard.cs.show', $ticket) }}" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 0.75rem;">Detail</a>
                            <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}" target="_blank" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 0.75rem;">Lacak</a>
                            <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket {{ $ticket->ticket_number }} ini?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 3px 8px; font-size: 0.75rem;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @else
            <div style="padding: 10px 14px; color: var(--text-muted); font-size: 0.8rem; font-style: italic; background: var(--bg);">
                Tidak ada tiket pada status ini.
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection
