@extends('layouts.dashboard')
@section('title', 'Detail Pengadaan | Super Admin')
@section('page_title', 'Detail Pengadaan')
@section('page_subtitle', $order->order_number)

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection


@section('content')
<div style="max-width: 860px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('admin.procurement') }}" class="btn btn-outline btn-sm">Kembali ke Daftar</a>
        @if($order->kits->count() > 0)
        <a href="{{ route('procurement.export-kits', $order) }}" class="btn-export">
            EXPORT
        </a>
        @endif
    </div>

    <!-- Header Card -->
    <div class="dash-card" style="margin-bottom: 16px;">
        <div class="dash-card-header">
            <div>
                <div style="font-family: monospace; font-size: 1.1rem; font-weight: 800; color: var(--primary);">{{ $order->order_number }}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Diterima {{ $order->created_at->format('d F Y, H:i') }}</div>
            </div>
            <span class="badge badge-{{ $order->status_color }}" style="padding: 8px 14px;">{{ $order->status_label }}</span>
        </div>
        <div class="dash-card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- Institusi -->
                <div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700;">Informasi Institusi</div>
                    <div style="display: flex; flex-direction: column; gap: 7px; font-size: 0.85rem;">
                        <div><span style="color: var(--text-muted);">Nama:</span> <strong>{{ $order->school_name }}</strong></div>
                        <div><span style="color: var(--text-muted);">Jenis:</span> <strong>{{ $order->school_type_label }}</strong></div>
                        <div><span style="color: var(--text-muted);">Kota:</span> <strong>{{ $order->school_city }}</strong></div>
                        <div><span style="color: var(--text-muted);">Alamat:</span> <span style="color: var(--text-secondary);">{{ $order->school_address }}</span></div>
                    </div>
                </div>
                <!-- PIC -->
                <div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700;">Data PIC</div>
                    <div style="display: flex; flex-direction: column; gap: 7px; font-size: 0.85rem;">
                        <div><span style="color: var(--text-muted);">Nama:</span> <strong>{{ $order->pic_name }}</strong></div>
                        <div><span style="color: var(--text-muted);">Jabatan:</span> <strong>{{ $order->pic_position }}</strong></div>
                        <div>
                            <span style="color: var(--text-muted);">WhatsApp:</span>
                            <a href="https://wa.me/62{{ ltrim($order->pic_phone, '0') }}?text=Halo {{ $order->pic_name }}, kami dari Klinik Komputer mengenai pengajuan pengadaan laptop {{ $order->order_number }}"
                               target="_blank" style="color: var(--primary); font-weight: 700;">{{ $order->pic_phone }}</a>
                        </div>
                        <div><span style="color: var(--text-muted);">Email:</span> <a href="mailto:{{ $order->pic_email }}" style="color: var(--primary);">{{ $order->pic_email }}</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spesifikasi & Notes -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div class="dash-card">
            <div class="dash-card-header"><h3>Detail Pengadaan</h3></div>
            <div class="dash-card-body">
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.85rem;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Seri Laptop</span>
                        <span class="badge {{ $order->axioo_series === 'pongo' ? 'badge-accent' : 'badge-primary' }}">{{ strtoupper($order->axioo_series) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Model Laptop</span>
                        <strong>{{ $order->axioo_model }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Jumlah Unit</span>
                        <strong style="font-size: 1.1rem; color: var(--primary);">{{ number_format($order->total_units) }} unit</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                        <span style="color: var(--text-muted);">Tujuan</span>
                        <strong>{{ $order->usage_purpose_label }}</strong>
                    </div>
                    @if($order->quoted_price)
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Harga Penawaran</span>
                        <strong style="color: var(--accent);">{{ $order->formatted_quoted_price }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="dash-card">
            <div class="dash-card-header"><h3>Catatan</h3></div>
            <div class="dash-card-body">
                <div style="margin-bottom: 14px;">
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Dari Customer</div>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6;">
                        {{ $order->notes ?: '—' }}
                    </p>
                </div>
                @if($order->admin_notes)
                <div style="padding-top: 12px; border-top: 1px solid var(--border-light);">
                    <div style="font-size: 0.7rem; color: var(--primary); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Catatan Internal Admin</div>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6;">{{ $order->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Update Status Form -->
    <div class="dash-card">
        <div class="dash-card-header"><h3>Update Status Pengadaan</h3></div>
        <div class="dash-card-body">
            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 16px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); color: #dc2626; padding: 12px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('admin.procurement.status', $order) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Status Pengadaan <span>*</span></label>
                        <select name="status" class="form-control" required>
                            @foreach([
                                'pending'             => 'Menunggu Konfirmasi',
                                'diproses'            => 'Unit Diproses',
                                'siap_kirim'          => 'Unit Siap Dikirim',
                                'konfirmasi_harga'    => 'Penawaran Harga',
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'dibayar'             => 'Sudah Dibayar',
                                'diproses_pengiriman' => 'Unit Dikirim',
                                'selesai'             => 'Selesai',
                                'dibatalkan'          => 'Dibatalkan',
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Penawaran (Rp)</label>
                        <input type="number" name="quoted_price" class="form-control"
                               value="{{ $order->quoted_price }}"
                               placeholder="Contoh: 150000000" min="0">
                        <span class="form-hint">Opsional. Total harga keseluruhan unit.</span>
                    </div>
                </div>
                <div style="margin-bottom: 16px; background: var(--bg-alt); border: 1px solid var(--border-light); border-radius: 8px; padding: 12px 14px;">
                    <label style="font-weight: 700; font-size: 0.72rem; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 8px;">Sub-Tahap Unit Diproses:</label>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_penawaran" value="1" {{ $order->substep_penawaran_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            📄 1. Penawaran
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_invoice" value="1" {{ $order->substep_invoice_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            🧾 2. Proses Invoice
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 600; cursor: pointer; color: var(--text-primary);">
                            <input type="checkbox" name="substep_pembayaran" value="1" {{ $order->substep_pembayaran_active ? 'checked' : '' }} style="accent-color: var(--primary); width: 16px; height: 16px;">
                            💳 3. Pembayaran
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan Internal</label>
                    <textarea name="admin_notes" class="form-control" rows="3"
                              placeholder="Catatan internal untuk tracking proses...">{{ $order->admin_notes }}</textarea>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; margin-top: 4px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="https://wa.me/62{{ ltrim($order->pic_phone, '0') }}?text=Halo {{ $order->pic_name }}, kami dari Klinik Komputer mengenai pengajuan pengadaan laptop {{ $order->order_number }}"
                       target="_blank" class="btn btn-outline">Hubungi PIC via WA</a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── DAFTAR KIT LAPTOP & AKTIVASI SISWA ── --}}
    @if($order->kits->count() > 0)
    <div class="dash-card" style="margin-top: 16px;">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 16px;">
            <h3 style="color: var(--primary);">Daftar Kit Laptop & Aktivasi Siswa ({{ $order->kits->count() }} unit)</h3>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            <div style="max-height: 400px; overflow-y: auto; border: 1px solid var(--border-light); border-radius: 6px; margin: 0 16px 16px;">
                <div class="table-wrap" style="border: none; border-radius: 0; margin: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-light); background: var(--bg-alt); position: sticky; top: 0; z-index: 1;">
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">ID Member / Owner</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Detail Komponen (SN)</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Status Aktivasi</th>
                                <th style="text-align: left; padding: 16px 20px; font-size: 0.85rem;">Tanggal Aktivasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->kits->reverse() as $kit)
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 16px 20px;">
                                    <div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.95rem; letter-spacing: 0.5px;">{{ $kit->member_id }}</div>
                                    @if($kit->status === 'activated')
                                    <div style="margin-top: 8px; font-size: 0.85rem; line-height: 1.45;">
                                        <strong style="color: var(--text-primary);">{{ $kit->student_name }}</strong>
                                        <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 4px;">
                                            Telp: {{ optional($kit->customer)->phone ?? '—' }} <br>
                                            Email: {{ optional($kit->customer)->email ?? '—' }}
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td style="padding: 16px 20px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; max-width: 480px;">
                                    @foreach(['Motherboard' => 'M', 'RAM' => 'R', 'SSD' => 'S', 'Screen' => 'L', 'Battery' => 'B'] as $fullName => $shortName)
                                        @php $comp = $kit->components->where('component_name', $fullName)->first(); @endphp
                                        <span style="font-family: monospace; font-size: 0.78rem; padding: 5px 9px; background: var(--bg-alt); border: 1px solid var(--border-light); border-radius: 4px; white-space: nowrap;" title="{{ $fullName }}">
                                            <span style="color: var(--text-muted); font-weight: 600; margin-right: 3px;">{{ $shortName }}:</span>{{ $comp ? $comp->serial_number : '—' }}
                                        </span>
                                    @endforeach
                                    </div>
                                </td>
                                <td style="padding: 16px 20px;">
                                    <span class="badge badge-{{ $kit->status === 'activated' ? 'success' : 'secondary' }}" style="font-size: 0.75rem; padding: 4px 8px;">
                                        {{ $kit->status === 'activated' ? 'Aktif' : 'Belum Diaktivasi' }}
                                    </span>
                                </td>
                                <td style="padding: 16px 20px; font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $kit->status === 'activated' && $kit->updated_at ? $kit->updated_at->format('d M Y, H:i') : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
