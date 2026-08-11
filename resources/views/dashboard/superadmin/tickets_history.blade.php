@extends('layouts.dashboard')
@section('title', 'History Servis Selesai | Super Admin')
@section('page_title', 'History Servis Selesai')
@section('page_subtitle', 'Arsip data unit servis yang sudah diselesaikan dan diambil pelanggan')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<!-- Filter & Actions Bar -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-body" style="padding:14px 18px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <form method="GET" action="{{ route('admin.tickets.history') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; flex:1;">
            <div style="position:relative; min-width: 240px; flex:1;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Tiket, No. Servis, Customer, WA, Merek..." class="form-control" style="padding:7px 12px; font-size:0.82rem; border-radius:6px;">
            </div>
            <select name="unit_type" class="form-control" style="width:auto; padding:7px 12px; font-size:0.82rem; border-radius:6px;">
                <option value="">Semua Tipe Unit</option>
                <option value="laptop" {{ request('unit_type')==='laptop' ? 'selected':'' }}>Laptop</option>
                <option value="desktop" {{ request('unit_type')==='desktop' ? 'selected':'' }}>Desktop PC</option>
                <option value="lainnya" {{ request('unit_type')==='lainnya' ? 'selected':'' }}>Lainnya</option>
            </select>
            <select name="month" class="form-control" style="width:auto; padding:7px 12px; font-size:0.82rem; border-radius:6px;">
                <option value="">Semua Bulan</option>
                @foreach($months as $num => $name)
                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="year" class="form-control" style="width:auto; padding:7px 12px; font-size:0.82rem; border-radius:6px;">
                <option value="">Semua Tahun</option>
                @foreach($years as $yr)
                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="padding:7px 14px; font-weight:700;">Filter</button>
            @if(request()->hasAny(['search', 'unit_type', 'month', 'year']))
            <a href="{{ route('admin.tickets.history') }}" class="btn btn-outline btn-sm" style="padding:7px 12px;">Reset</a>
            @endif
        </form>

        <div>
            <a href="{{ route('admin.tickets.history.export', request()->query()) }}" class="btn" style="background:#16a34a; color:#ffffff; font-weight:700; font-size:0.82rem; padding:8px 16px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; text-decoration:none; box-shadow:0 2px 5px rgba(22,163,74,0.25);">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Export Excel (.xls)
            </a>
        </div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>History Servis Selesai ({{ $tickets->total() }})</h3>
        <span style="font-size:0.75rem; color:var(--text-muted);">Menampilkan data tiket yang sudah diambil oleh pelanggan</span>
    </div>
    <div class="dash-card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No</th>
                        <th>No. Tiket / Servis</th>
                        <th>Customer & WA</th>
                        <th>Unit Perangkat</th>
                        <th>Penyebab / Kerusakan</th>
                        <th>Teknisi PJ</th>
                        <th>Biaya Servis</th>
                        <th>Tgl Diambil</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $index => $ticket)
                    <tr>
                        <td style="text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                            {{ $tickets->firstItem() + $index }}
                        </td>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.84rem;">{{ $ticket->ticket_number }}</div>
                            @if($ticket->airtable_service_number)
                            <div style="font-family:monospace; font-weight:600; color:#2563eb; font-size:0.76rem; margin-top:2px;">
                                No. Servis: {{ $ticket->airtable_service_number }}
                            </div>
                            @endif
                            <div style="font-size:0.7rem; color:var(--text-muted);">#Antrian {{ $ticket->queue_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight:700; font-size:0.86rem; color:var(--text-primary);">{{ $ticket->customer_name }}</div>
                            <div style="font-size:0.76rem; color:var(--text-muted); display:flex; align-items:center; gap:4px; margin-top:2px;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                {{ $ticket->customer_phone }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:700; font-size:0.84rem;">{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <span style="font-size:0.68rem; font-weight:700; text-transform:uppercase; background:var(--bg-alt); border:1px solid var(--border-light); padding:1px 6px; border-radius:4px; color:var(--text-secondary);">
                                {{ $ticket->unit_type }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size:0.8rem; color:var(--text-primary); max-width:220px; white-space:normal; line-height:1.35;">
                                {{ Str::limit($ticket->cause ?: ($ticket->damage_description ?: '-'), 65) }}
                            </div>
                        </td>
                        <td>
                            @if($ticket->technician)
                            <div style="font-weight:600; font-size:0.82rem; color:var(--text-primary);">{{ $ticket->technician->name }}</div>
                            @else
                            <span style="font-size:0.75rem; color:var(--text-muted); font-style:italic;">Belum Ditentukan</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:800; font-size:0.85rem; color:#16a34a;">
                                Rp {{ number_format($ticket->estimated_cost ?: 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.78rem; font-weight:600; color:var(--text-primary);">{{ $ticket->updated_at->format('d/m/Y') }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);">{{ $ticket->updated_at->format('H:i') }} WIB</div>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('dashboard.cs.show', $ticket) }}" class="btn btn-outline btn-sm" style="padding:4px 10px; font-size:0.74rem; font-weight:700;">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding:32px; color:var(--text-muted);">
                            <div style="font-size:1rem; font-weight:700; margin-bottom:4px;">Belum Ada History Servis Selesai</div>
                            <div style="font-size:0.82rem;">Tiket servis yang statusnya telah diambil pelanggan akan otomatis tersimpan di halaman ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div style="padding:16px; border-top:1px solid var(--border-light);">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
