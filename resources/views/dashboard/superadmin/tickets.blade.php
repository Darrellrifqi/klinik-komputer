@extends('layouts.dashboard')
@section('title', 'Tiket Servis — Super Admin')
@section('page_title', 'Tiket Servis')
@section('page_subtitle', 'Pantau tiket servis di sistem')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection


@section('content')
<!-- Filter -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-body" style="padding:12px 16px;">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select name="status" class="form-control" style="width:auto; padding:6px 12px; font-size:0.8rem;">
                <option value="">Semua Status</option>
                <option value="waiting"  {{ request('status')==='waiting'  ? 'selected':'' }}>Menunggu</option>
                <option value="checking" {{ request('status')==='checking' ? 'selected':'' }}>Pengecekan</option>
                <option value="checked"  {{ request('status')==='checked'  ? 'selected':'' }}>Selesai Cek</option>
                <option value="rma"      {{ request('status')==='rma'      ? 'selected':'' }}>Proses RMA</option>
                <option value="done"     {{ in_array(request('status'), ['done','siap_diambil']) ? 'selected':'' }}>Siap Diambil</option>
                <option value="sudah_diambil" {{ in_array(request('status'), ['sudah_diambil','taken']) ? 'selected':'' }}>Sudah Diambil</option>
                <option value="cancelled"{{ request('status')==='cancelled'? 'selected':'' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header">
        <h3>Tiket Servis ({{ $tickets->total() }})</h3>
    </div>
    <div class="dash-card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Customer</th>
                        <th>Unit Perangkat</th>
                        <th>Dibuat Oleh</th>
                        <th>Teknisi PJ</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <div style="font-family:monospace; font-weight:700; color:var(--primary); font-size:0.82rem;">{{ $ticket->ticket_number }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted);">#{{ $ticket->queue_number }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:0.85rem;">{{ $ticket->customer_name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ticket->customer_phone }}</div>
                        </td>
                        <td style="font-size:0.85rem;">
                            <div>{{ $ticket->brand }} {{ $ticket->model }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase;">{{ $ticket->unit_type }}</div>
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary);">{{ $ticket->creator?->name ?? 'Online' }}</td>
                        <td style="font-size:0.82rem; color:var(--text-secondary);">{{ $ticket->technician?->name ?? '—' }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:5px;">
                                <div class="status-dot {{ $ticket->status }}"></div>
                                <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.68rem;">{{ $ticket->status_label }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td style="white-space: nowrap;">
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}"
                                   target="_blank" class="btn btn-outline btn-sm" style="padding:4px 8px;">Lacak</a>
                                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket {{ $ticket->ticket_number }} ini secara permanen dari database?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px; font-size:0.72rem;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8"><div class="empty-state"><p>Tidak ada tiket perbaikan.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;">{{ $tickets->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
