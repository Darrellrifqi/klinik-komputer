@extends('layouts.dashboard')
@section('title', 'Detail Tiket — CS')
@section('page_title', 'Detail Tiket')
@section('page_subtitle', $ticket->ticket_number)

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')
<div style="max-width:800px;">
    <div style="display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
        <a href="{{ route('dashboard.cs') }}" class="btn btn-outline btn-sm">Kembali</a>
        <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}"
           target="_blank" class="btn btn-outline btn-sm">Lihat Tracking Publik</a>
    </div>

    <!-- Ticket Header -->
    <div class="dash-card" style="margin-bottom:16px;">
        <div class="dash-card-header">
            <div>
                <div style="font-family:monospace; font-size:1.1rem; font-weight:800; color:var(--primary);">{{ $ticket->ticket_number }}</div>
                <div style="font-size:0.75rem; color:var(--text-muted);">Antrian #{{ $ticket->queue_number }} &bull; Dibuat {{ $ticket->created_at->format('d M Y, H:i') }}</div>
            </div>
            <span class="badge badge-{{ $ticket->status_color }}" style="font-size:0.78rem; padding:8px 14px;">
                {{ $ticket->status_label }}
            </span>
        </div>
        <div class="dash-card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Data Customer</div>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.85rem;">
                        <div><span style="color:var(--text-muted);">Nama:</span> <strong>{{ $ticket->customer_name }}</strong></div>
                        <div><span style="color:var(--text-muted);">Telp:</span> <strong>{{ $ticket->customer_phone }}</strong></div>
                        <div><span style="color:var(--text-muted);">Dibuat oleh:</span> <strong>{{ $ticket->creator?->name ?? 'Customer Online' }}</strong></div>
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Spesifikasi Unit</div>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.85rem;">
                        <div><span style="color:var(--text-muted);">Tipe:</span> <strong style="text-transform:uppercase;">{{ $ticket->unit_type }}</strong></div>
                        <div><span style="color:var(--text-muted);">Merek:</span> <strong>{{ $ticket->brand }}</strong></div>
                        <div><span style="color:var(--text-muted);">Model:</span> <strong>{{ $ticket->model }}</strong></div>
                    </div>
                </div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Jadwal Penyerahan Unit ke Kantor</div>
                <div style="font-size:0.9rem; font-weight:700; color:var(--primary);">{{ $ticket->dropoff_schedule ?: 'Tidak dicantumkan' }}</div>
            </div>
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--border);">
                <div style="font-size:0.7rem; color:var(--text-muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:0.05em; font-weight:700;">Deskripsi Kerusakan</div>
                <p style="font-size:0.85rem; line-height:1.6; color:var(--text-secondary);">{{ $ticket->damage_description }}</p>
            </div>
            @if($ticket->technician)
            <div style="margin-top:14px; padding:10px; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); font-size:0.8rem;">
                <span style="color:var(--text-muted);">Ditangani oleh:</span>
                <strong style="margin-left:6px;">{{ $ticket->technician->name }}</strong>
                @if($ticket->pic_name)<span style="color:var(--text-muted); font-size:0.8rem;"> (PJ: {{ $ticket->pic_name }})</span>@endif
            </div>
            @endif
        </div>
    </div>

    <!-- CS Status Update Form Card -->
    <div class="dash-card" style="margin-bottom:16px; border-left: 4px solid var(--primary);">
        <div class="dash-card-header" style="padding-bottom: 0; border-bottom: none;">
            <h3 style="margin:0; font-size:0.92rem; color:var(--primary); font-weight:800; letter-spacing:0.02em;">Update Status Tiket</h3>
        </div>
        <div class="dash-card-body" style="padding-top: 14px;">
            <form action="{{ route('dashboard.cs.tickets.update_status', $ticket) }}" method="POST">
                @csrf

                {{-- Member Info Panel --}}
                @if($ticket->customer)
                @php
                    $linkedUser   = $ticket->customer;
                    $memberPeriod = $linkedUser->tuneUpPeriod();
                    $memberCount  = $linkedUser->tuneUpCount();
                    $memberRemain = $linkedUser->tuneUpRemaining();
                    $quotaFull    = $memberCount >= 2;
                @endphp
                <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; margin-bottom: 16px;">
                    <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: var(--primary); margin-bottom: 8px;">Member Terhubung</div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; flex-direction: column; gap: 3px;">
                            <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">{{ $linkedUser->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                @if($ticket->laptopKit)
                                    <span class="badge badge-primary" style="font-size:0.6rem;">Pengadaan</span>
                                    <span style="font-family: monospace;">{{ $ticket->laptopKit->member_id }}</span>
                                @else
                                    <span class="badge badge-accent" style="font-size:0.6rem;">Umum</span>
                                    <span style="font-family: monospace;">NIK: {{ $linkedUser->nik ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="text-align: right;">
                                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 3px;">Kuota Tune-Up Gratis</div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span style="font-family: monospace; font-weight: 800; font-size: 1rem; color: {{ $quotaFull ? '#dc2626' : 'var(--primary)' }};">{{ $memberCount }}/2</span>
                                    <div style="width: 48px; height: 6px; background: var(--border-light); border-radius: 3px; overflow: hidden;">
                                        <div style="width: {{ min(100, $memberCount/2*100) }}%; height: 100%; background: {{ $quotaFull ? '#dc2626' : 'var(--primary)' }}; border-radius: 3px;"></div>
                                    </div>
                                </div>
                                <div style="font-size: 0.65rem; color: var(--text-muted); margin-top: 2px;">Reset: {{ $memberPeriod['end']->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Form Fields --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px;">Status</label>
                        <select name="status" class="form-control" required style="padding:8px 10px; font-size:0.85rem;">
                            <option value="waiting"   {{ $ticket->status === 'waiting'   ? 'selected':'' }}>Menunggu</option>
                            <option value="checking"  {{ $ticket->status === 'checking'  ? 'selected':'' }}>Pengecekan Teknisi</option>
                            <option value="checked"   {{ in_array($ticket->status, ['checked','konfirmasi_user']) ? 'selected':'' }}>Konfirmasi User</option>
                            <option value="rma"       {{ in_array($ticket->status, ['rma','proses_service'])     ? 'selected':'' }}>Proses Service</option>
                            <option value="done"      {{ in_array($ticket->status, ['done','siap_diambil'])     ? 'selected':'' }}>Siap Diambil</option>
                            <option value="cancelled" {{ $ticket->status === 'cancelled' ? 'selected':'' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px;">No. Servis Airtable</label>
                        <input type="text" name="airtable_service_number"
                               value="{{ old('airtable_service_number', $ticket->airtable_service_number) }}"
                               class="form-control" placeholder="Contoh: AX0-1108"
                               style="padding:8px 10px; font-size:0.85rem; font-family: monospace;">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; margin-bottom:5px;">ID Member <span style="font-weight:400; color:var(--text-muted);">(NIK untuk Umum / SN untuk Pengadaan)</span></label>
                    <input type="text" name="member_id_input"
                           value="{{ old('member_id_input', $ticket->customer?->nik ?? $ticket->laptopKit?->member_id) }}"
                           class="form-control" placeholder="Kosongkan jika bukan member"
                           style="padding:8px 10px; font-size:0.85rem; font-family: monospace;">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: rgba(180, 83, 9, 0.05); border: 1px solid rgba(180, 83, 9, 0.2); border-radius: var(--radius-sm);">
                    <label style="font-size: 0.84rem; font-weight: 700; color: #92400e; display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_tune_up" value="1"
                               {{ old('is_tune_up', $ticket->is_tune_up) ? 'checked' : '' }}
                               style="transform: scale(1.1); accent-color: #b45309;">
                        Layanan Tune-Up Gratis
                        <span style="font-weight: 400; color: var(--text-muted); font-size: 0.78rem;">(mengurangi 1 kuota member)</span>
                    </label>
                    <button type="submit" class="btn btn-primary" style="font-weight:700; padding:8px 22px; font-size:0.85rem; white-space:nowrap;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Timeline -->
    <div class="dash-card">
        <div class="dash-card-header"><h3>Log Riwayat Status</h3></div>
        <div class="dash-card-body">
            @if($ticket->histories->count() > 0)
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($ticket->histories as $h)
                <div style="display:flex; gap:10px; align-items:flex-start;">
                    <div style="width:6px; height:6px; border-radius:50%; background:var(--primary); margin-top:6px; flex-shrink:0;"></div>
                    <div style="flex:1; background:var(--bg-alt); border-radius:var(--radius-sm); border:1px solid var(--border); padding:10px 14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; flex-wrap:wrap; gap:6px;">
                            <div style="display:flex; align-items:center; gap:6px;">
                                @if($h->old_status)<span class="badge badge-secondary" style="font-size:0.65rem;">{{ ucfirst($h->old_status) }}</span><span style="color:var(--text-muted); font-size:0.8rem;">&rarr;</span>@endif
                                <span class="badge badge-primary" style="font-size:0.65rem;">{{ ucfirst($h->new_status) }}</span>
                            </div>
                            <span style="font-size:0.75rem; color:var(--text-muted);">{{ $h->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if($h->notes)
                        <p style="font-size:0.8rem; color:var(--text-secondary); margin:0;">{{ $h->notes }}</p>
                        @endif
                        @if($h->user)
                        <p style="font-size:0.75rem; color:var(--text-muted); margin:4px 0 0;">Diperbarui oleh: {{ $h->user->name }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="padding:20px;">
                <h3>Belum ada riwayat update</h3>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
