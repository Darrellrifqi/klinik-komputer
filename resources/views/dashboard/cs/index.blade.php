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
<div style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 12px;">
    {{-- Baris 1: 4 Kolom --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
        {{-- Menunggu Unit --}}
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
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
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
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
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
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
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
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

    {{-- Baris 2: 3 Kolom --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        {{-- Menunggu Sparepart --}}
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->whereIn('sub_status', ['menunggu_part', 'pembelian_part'])->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Menunggu Part</div>
            </div>
        </div>

        {{-- Proses Service --}}
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
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

        {{-- Selesai --}}
        <div style="padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-radius: 8px; background: var(--bg-card); border: 1px solid var(--border);">
            <div style="width: 34px; height: 34px; min-width: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(22, 163, 74, 0.12); color: #16a34a;">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ $allTickets->whereIn('status',['done','siap_diambil','sudah_diambil','taken'])->count() }}</div>
                <div style="font-size: 0.68rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.02em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">Selesai</div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ TIKET GROUPED SECTIONS (Airtable / Notion Style) ════════ --}}
<div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px;">
    @foreach($groupedTickets as $statusKey => $group)
    <div class="dash-card" style="border-left: 4px solid {{ $group['color'] }}; background: var(--bg-card); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 0;">
        <div class="dash-card-header" style="background: var(--bg-alt); padding: 8px 14px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 0.9rem; font-weight: 700; margin: 0; color: var(--text-primary);">{{ $group['title'] }}</h3>
                <span class="badge" style="background: {{ $group['color'] }}; color: #fff; font-size: 0.7rem; padding: 2px 8px; border-radius: 12px; font-weight: 800;">{{ $group['tickets']->count() }}</span>
            </div>
            @if($statusKey === 'waiting' || $statusKey === 'unit_received')
                <a href="{{ route('dashboard.cs.create') }}" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 3px 8px;">+ Buat Tiket</a>
            @endif
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($group['tickets']->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">No. Tiket / Antrian</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Customer</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Unit Perangkat</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Teknisi PJ</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Status Rinci</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Dibuat</th>
                            <th style="padding: 8px 14px; font-size: 0.72rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['tickets'] as $ticket)
                        <tr>
                            <td style="padding: 8px 14px;">
                                <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.85rem;">{{ $ticket->ticket_number }}</div>
                                @if($ticket->airtable_service_number)
                                    <div style="font-family:monospace; font-size:0.72rem; color:var(--success); font-weight:700; margin-top:2px;">
                                        AT: {{ $ticket->airtable_service_number }}
                                    </div>
                                @endif
                                <div style="font-size:0.72rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }}</div>
                            </td>
                            <td style="padding: 8px 14px;">
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
                                @if($ticket->dropoff_schedule)
                                <div style="font-size:0.72rem; color:var(--primary); font-weight:600; margin-top:2px;">
                                    Penyerahan: {{ $ticket->dropoff_schedule }}
                                </div>
                                @endif
                            </td>
                            <td style="padding: 8px 14px;">
                                <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                                <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                            </td>
                            <td style="padding: 8px 14px; font-size:0.85rem; color:var(--text-secondary);">
                                {{ $ticket->technician?->name ?? '—' }}
                            </td>
                            <td style="padding: 8px 14px;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div class="status-dot {{ $ticket->status }}"></div>
                                    <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.72rem;">{{ $ticket->sub_status_label ?? $ticket->status_label }}</span>
                                </div>
                            </td>
                            <td style="padding: 8px 14px; font-size:0.78rem; color:var(--text-muted);">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td style="padding: 8px 14px; white-space: nowrap;">
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
            @else
            <div style="padding: 8px 14px; color: var(--text-muted); font-size: 0.8rem; font-style: italic; background: var(--bg);">
                Tidak ada tiket pada status ini.
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection
