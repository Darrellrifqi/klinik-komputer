@extends('layouts.app')
@section('title', 'Booking Berhasil | Klinik Komputer')

@section('content')
<div style="padding-top: 64px;">
<section class="section">
    <div class="section-inner" style="max-width: 580px; text-align: center;">
        <div style="margin-bottom: 28px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(69, 178, 107, 0.08); border: 2px solid var(--success); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: var(--success); font-size: 1.5rem; font-weight: 700;">
                OK
            </div>
            <h1 style="color: var(--primary); margin-bottom: 8px; font-size: 1.8rem;">Pendaftaran Berhasil</h1>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Simpan nomor antrian di bawah ini untuk ditunjukkan kepada petugas CS saat menyerahkan unit.</p>
        </div>

        <!-- Queue Number Display -->
        <div class="queue-display" style="margin-bottom: 24px;">
            <div style="font-size: 0.72rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Nomor Antrian Anda</div>
            <div class="queue-number">#{{ $ticket->queue_number }}</div>
            <div class="ticket-id">{{ $ticket->ticket_number }}</div>
        </div>

        <!-- Ticket Details -->
        <div class="card" style="margin-bottom: 20px; text-align: left;">
            <h3 style="margin-bottom: 16px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Detail Pendaftaran</h3>
            <div style="display: grid; gap: 10px; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Nama</span>
                    <span style="font-weight: 600;">{{ $ticket->customer_name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Nomor WhatsApp</span>
                    <span style="font-weight: 600;">{{ $ticket->customer_phone }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Perangkat</span>
                    <span style="font-weight: 600;">{{ $ticket->brand }} {{ $ticket->model }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
                    <span style="color: var(--text-muted);">Tipe</span>
                    <span style="font-weight: 600; text-transform: uppercase; font-size: 0.75rem;">{{ $ticket->unit_type }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted);">Status</span>
                    <span class="badge badge-warning">Menunggu</span>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="alert alert-info" style="text-align: left; margin-bottom: 24px;">
            <div>
                <strong style="text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Langkah Selanjutnya:</strong>
                <ol style="margin-top: 6px; padding-left: 14px; font-size: 0.82rem; line-height: 1.6; color: var(--text-secondary);">
                    <li>Kunjungi kantor Klinik Komputer.</li>
                    <li>Sampaikan nomor antrian <strong>#{{ $ticket->queue_number }}</strong> kepada CS.</li>
                    <li>Serahkan unit perangkat keras Anda untuk diperiksa oleh teknisi.</li>
                    <li>Lacak kemajuan perbaikan secara berkala melalui fitur cek status.</li>
                </ol>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
            <a href="{{ route('service.track') }}?ticket_number={{ $ticket->ticket_number }}"
               class="btn btn-primary">Lacak Pengerjaan</a>
            <a href="{{ route('home') }}" class="btn btn-outline">Beranda</a>
            <button onclick="window.print()" class="btn btn-outline">Cetak Bukti</button>
        </div>

        <div style="margin-top: 24px; padding: 14px; background: var(--bg-alt); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.8rem; color: var(--text-secondary); text-align: left;">
            <strong>Lokasi Kantor:</strong><br>
            Komplek Ruko Segitiga Emas Kosambi, Jl. A. Yani Blok E8, Merdeka, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40113
        </div>
    </div>
</section>
</div>
@endsection
