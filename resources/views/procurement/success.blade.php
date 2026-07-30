@extends('layouts.app')
@section('title', 'Pengajuan Berhasil | Klinik Komputer')

@section('content')
<div style="padding-top: 64px;">
<section class="section">
    <div class="section-inner" style="max-width: 640px; text-align: center;">

        <!-- Success Icon -->
        <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(69,178,107,0.08); border: 2px solid var(--success); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-weight: 800; color: var(--success); font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.05em;">
            OK
        </div>

        <h1 style="color: var(--primary); margin-bottom: 10px; font-size: 1.8rem;">Pengajuan Diterima</h1>
        <p style="color: var(--text-secondary); font-size: 0.92rem; margin-bottom: 28px; max-width: 480px; margin-left: auto; margin-right: auto;">
            Terima kasih! Pengajuan pengadaan laptop dari <strong>{{ $order->school_name }}</strong> telah berhasil kami terima. Tim Klinik Komputer akan segera menghubungi Anda.
        </p>

        <!-- Order Number -->
        <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: 8px; padding: 20px 28px; margin-bottom: 24px;">
            <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 6px;">Nomor Referensi Pengajuan</div>
            <div style="font-family: monospace; font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing: 2px;">{{ $order->order_number }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Simpan nomor ini untuk keperluan tindak lanjut</div>
        </div>

        <!-- Summary Card -->
        <div class="card" style="margin-bottom: 20px; text-align: left;">
            <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 14px;">Ringkasan Pengajuan</div>
            <div style="display: grid; gap: 10px; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Institusi</span>
                    <strong>{{ $order->school_name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Jenis</span>
                    <strong>{{ $order->school_type_label }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">PIC</span>
                    <strong>{{ $order->pic_name }} ({{ $order->pic_position }})</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">WhatsApp PIC</span>
                    <strong>{{ $order->pic_phone }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Laptop Diminta</span>
                    <strong style="text-align: right;">
                        @if($order->items && count($order->items) > 0)
                            @foreach($order->items as $it)
                                <div>{{ $it['model'] }} ({{ number_format($it['units']) }} unit)</div>
                            @endforeach
                        @else
                            {{ $order->axioo_model }}
                        @endif
                    </strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Total Unit</span>
                    <strong style="color: var(--primary);">{{ number_format($order->total_units) }} unit</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Status</span>
                    <span class="badge badge-warning">{{ $order->status_label }}</span>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="alert alert-info" style="text-align: left; margin-bottom: 24px;">
            <div>
                <strong style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em;">Langkah Selanjutnya:</strong>
                <ol style="margin-top: 8px; padding-left: 16px; font-size: 0.82rem; line-height: 1.7; color: var(--text-secondary);">
                    <li>Tim Klinik Komputer akan menghubungi <strong>{{ $order->pic_name }}</strong> melalui WhatsApp <strong>{{ $order->pic_phone }}</strong> dalam 1×24 jam kerja.</li>
                    <li>Kami akan menyampaikan surat penawaran harga resmi beserta detail spesifikasi unit.</li>
                    <li>Setelah harga disetujui, proses pembayaran dilakukan sesuai kesepakatan.</li>
                    <li>Unit akan dikirimkan ke alamat institusi Anda setelah pembayaran dikonfirmasi.</li>
                </ol>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            <a href="https://wa.me/6285103051000?text=Halo, saya sudah mengisi form pengadaan dengan nomor {{ $order->order_number }} dari {{ $order->school_name }}"
               target="_blank" class="btn btn-primary">Konfirmasi via WhatsApp</a>
            <a href="{{ route('procurement.index') }}?track={{ $order->order_number }}#tracking" class="btn btn-outline">Lacak Status Pengadaan</a>
            <a href="{{ route('procurement.index') }}" class="btn btn-outline">Ajukan Pengadaan Lain</a>
            <button onclick="window.print()" class="btn btn-outline">Cetak Bukti</button>
        </div>

        <div style="margin-top: 20px; padding: 14px; background: var(--bg-alt); border-radius: 6px; border: 1px solid var(--border); font-size: 0.78rem; color: var(--text-secondary); text-align: left;">
            <strong>Klinik Komputer</strong> | Komplek Ruko Segitiga Emas Kosambi, Jl. A. Yani Blok E8, Kota Bandung<br>
            WhatsApp: 085103051000 &bull; Jam Kerja: Senin–Jumat 09.00–17.00 WIB, Sabtu 09.00–14.00 WIB
        </div>
    </div>
</section>
</div>
@endsection
