@extends('layouts.app')
@section('title', 'Cek Status Pengadaan — Klinik Komputer')
@section('meta_description', 'Lacak status pengajuan pengadaan laptop Axioo untuk sekolah Anda menggunakan nomor referensi pengajuan.')

@section('content')
<div style="padding-top: 64px;">
<section class="section">
    <div class="section-inner" style="max-width: 640px;">

        {{-- Page Header --}}
        <div style="text-align:center; margin-bottom: 36px;">
            <div class="label-line" style="justify-content:center;">Pengadaan Sekolah</div>
            <h1 style="margin-bottom: 10px;">Cek Status Pengadaan</h1>
            <p style="color:var(--text-secondary); font-size:0.92rem;">
                Masukkan nomor referensi pengajuan yang diterima saat submit formulir untuk melihat perkembangan pengadaan Anda.
            </p>
        </div>

        {{-- Search Form --}}
        <div class="card" style="margin-bottom: 24px;">
            <form action="{{ route('procurement.track.result') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Nomor Referensi Pengajuan <span>*</span></label>
                    <input type="text"
                           name="order_number"
                           class="form-control"
                           value="{{ old('order_number', isset($order) ? $order->order_number : '') }}"
                           placeholder="Contoh: PRC-20260716-0001"
                           style="font-family:monospace; font-size:1rem; letter-spacing:1px; text-transform:uppercase;"
                           autocomplete="off"
                           required>
                    @error('order_number')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                    <span class="form-hint">Nomor ini dikirim ke email Anda saat pengajuan berhasil disubmit.</span>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Cek Status Sekarang</button>
            </form>
        </div>

        {{-- ══ HASIL TRACKING ══ --}}
        @isset($order)
        <div id="trackingResult">

            {{-- Status Header --}}
            <div class="card" style="margin-bottom: 16px; border-color: var(--{{ $order->status_color === 'warning' ? 'warning' : ($order->status_color === 'success' ? 'success' : ($order->status_color === 'info' ? 'info' : 'primary')) }});">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
                    <div>
                        <div style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:4px;">Nomor Referensi</div>
                        <div style="font-family:monospace; font-weight:800; font-size:1.1rem; color:var(--primary);">{{ $order->order_number }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Diajukan {{ $order->created_at->format('d F Y') }} &bull; {{ $order->created_at->diffForHumans() }}</div>
                    </div>
                    <span class="badge badge-{{ $order->status_color }}" style="padding:10px 18px; font-size:0.8rem;">
                        {{ $order->status_label }}
                    </span>
                </div>

                {{-- Progress Stepper --}}
                @php
                $allSteps = [
                    ['val'=>'pending',             'label'=>"Menunggu\nKonfirmasi"],
                    ['val'=>'diproses',             'label'=>"Unit\nDiproses"],
                    ['val'=>'siap_kirim',           'label'=>"Siap\nDikirim"],
                    ['val'=>'diproses_pengiriman',  'label'=>"Unit\nDikirim"],
                    ['val'=>'selesai',              'label'=>"Selesai"],
                ];
                $statusOrder = ['pending','diproses','siap_kirim','diproses_pengiriman','selesai'];
                $currentIdx  = array_search($order->status, $statusOrder);
                $isCancelled = $order->status === 'dibatalkan';
                @endphp

                @if($isCancelled)
                <div style="text-align:center; padding:16px; background:rgba(209,79,79,0.05); border:1px solid rgba(209,79,79,0.15); border-radius:6px;">
                    <div style="font-weight:700; color:var(--danger); font-size:0.9rem;">Pengajuan Dibatalkan</div>
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">Hubungi Klinik Komputer untuk informasi lebih lanjut.</div>
                </div>
                @else
                <div style="display:flex; align-items:center; overflow-x:auto; padding-bottom:4px;">
                    @foreach($allSteps as $i => $step)
                    @php
                        $stepIdx   = array_search($step['val'], $statusOrder);
                        $isDone    = $currentIdx !== false && $stepIdx < $currentIdx;
                        $isCurrent = $order->status === $step['val'];
                        $isFuture  = $currentIdx !== false && $stepIdx > $currentIdx;
                    @endphp
                    <div style="display:flex; align-items:center; flex:1; min-width:70px;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:5px; flex:1;">
                            <div style="width:34px; height:34px; border-radius:50%;
                                border:2px solid {{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--border)') }};
                                background:{{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : '#fff') }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:0.75rem; font-weight:800;
                                color:{{ ($isCurrent || $isDone) ? '#fff' : 'var(--text-muted)' }};
                                box-shadow:{{ $isCurrent ? '0 0 0 4px var(--primary-glow)' : 'none' }};
                                transition:all 0.3s;">
                                @if($isDone) ✓ @elseif($isCurrent) {{ $i + 1 }} @else {{ $i + 1 }} @endif
                            </div>
                            <div style="font-size:0.65rem; font-weight:{{ $isCurrent ? '700' : '500' }};
                                color:{{ $isCurrent ? 'var(--primary)' : ($isDone ? 'var(--success)' : 'var(--text-muted)') }};
                                text-align:center; white-space:pre-line; line-height:1.25;">{{ $step['label'] }}</div>
                        </div>
                        @if(!$loop->last)
                        <div style="height:2px; flex:1; min-width:12px;
                            background:{{ ($currentIdx !== false && $i < $currentIdx) ? 'var(--success)' : 'var(--border)' }};
                            margin-bottom:20px;"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if(!$isCancelled)
                {{-- Sub Bab "Unit Diproses" Tracker --}}
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px dashed var(--border-light);">
                    <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="display: flex; align-items: center; gap: 6px; color: var(--text-secondary);">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            Sub-Tahap: Unit Diproses
                        </span>
                        <span style="font-size: 0.68rem; color: var(--primary); font-weight: 700; background: rgba(22,163,74,0.08); padding: 2px 8px; border-radius: 99px;">Tahapan Proses</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        {{-- Sub 1: Penawaran --}}
                        <div style="background: {{ $order->substep_penawaran_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $order->substep_penawaran_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 10px; padding: 12px 8px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $order->substep_penawaran_active ? '#16a34a' : '#ffffff' }}; color: {{ $order->substep_penawaran_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $order->substep_penawaran_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px auto; font-size: 0.72rem; font-weight: 800; box-shadow: {{ $order->substep_penawaran_active ? '0 2px 6px rgba(22,163,74,0.2)' : 'none' }};">
                                @if($order->substep_penawaran_active) ✓ @else 1 @endif
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: {{ $order->substep_penawaran_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Penawaran</div>
                            <div style="font-size: 0.68rem; color: {{ $order->substep_penawaran_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 3px;">
                                {{ $order->substep_penawaran_active ? '✓ Selesai' : 'Sedang Diproses' }}
                            </div>
                        </div>

                        {{-- Sub 2: Proses Invoice --}}
                        <div style="background: {{ $order->substep_invoice_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $order->substep_invoice_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 10px; padding: 12px 8px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $order->substep_invoice_active ? '#16a34a' : '#ffffff' }}; color: {{ $order->substep_invoice_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $order->substep_invoice_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px auto; font-size: 0.72rem; font-weight: 800; box-shadow: {{ $order->substep_invoice_active ? '0 2px 6px rgba(22,163,74,0.2)' : 'none' }};">
                                @if($order->substep_invoice_active) ✓ @else 2 @endif
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: {{ $order->substep_invoice_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Proses Invoice</div>
                            <div style="font-size: 0.68rem; color: {{ $order->substep_invoice_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 3px;">
                                {{ $order->substep_invoice_active ? '✓ Diterbitkan' : 'Menunggu Invoice' }}
                            </div>
                        </div>

                        {{-- Sub 3: Pembayaran --}}
                        <div style="background: {{ $order->substep_pembayaran_active ? 'rgba(22, 163, 74, 0.04)' : 'var(--bg-alt)' }}; border: 1px solid {{ $order->substep_pembayaran_active ? '#16a34a' : 'var(--border-light)' }}; border-radius: 10px; padding: 12px 8px; text-align: center; transition: all 0.2s ease;">
                            <div style="width: 26px; height: 26px; border-radius: 50%; background: {{ $order->substep_pembayaran_active ? '#16a34a' : '#ffffff' }}; color: {{ $order->substep_pembayaran_active ? '#ffffff' : 'var(--text-muted)' }}; border: 1px solid {{ $order->substep_pembayaran_active ? '#16a34a' : 'var(--border)' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px auto; font-size: 0.72rem; font-weight: 800; box-shadow: {{ $order->substep_pembayaran_active ? '0 2px 6px rgba(22,163,74,0.2)' : 'none' }};">
                                @if($order->substep_pembayaran_active) ✓ @else 3 @endif
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: {{ $order->substep_pembayaran_active ? 'var(--text-primary)' : 'var(--text-muted)' }};">Pembayaran</div>
                            <div style="font-size: 0.68rem; color: {{ $order->substep_pembayaran_active ? '#16a34a' : 'var(--text-muted)' }}; font-weight: 600; margin-top: 3px;">
                                {{ $order->substep_pembayaran_active ? '✓ Lunas' : 'Menunggu Pelunasan' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Ringkasan Pengajuan --}}
            <div class="card" style="margin-bottom: 16px;">
                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:14px;">Ringkasan Pengajuan</div>
                <div style="display:grid; gap:10px; font-size:0.85rem;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Institusi</span>
                        <strong>{{ $order->school_name }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Jenis & Kota</span>
                        <span>{{ $order->school_type_label }}, {{ $order->school_city }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">PIC</span>
                        <span>{{ $order->pic_name }} ({{ $order->pic_position }})</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Laptop Diajukan</span>
                        <strong>{{ $order->axioo_model }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Jumlah Unit</span>
                        <strong style="color:var(--primary);">{{ number_format($order->total_units) }} unit</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Tujuan Penggunaan</span>
                        <span>{{ $order->usage_purpose_label }}</span>
                    </div>
                    @if($order->quoted_price)
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border-light); padding-bottom:8px;">
                        <span style="color:var(--text-muted);">Harga Penawaran</span>
                        <strong style="color:var(--accent); font-size:1rem;">{{ $order->formatted_quoted_price }}</strong>
                    </div>
                    @endif
                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:var(--text-muted);">Status Terakhir</span>
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    </div>
                </div>
            </div>

            {{-- Info Selanjutnya berdasarkan status --}}
            @php
            $nextInfo = match($order->status) {
                'pending'            => ['color'=>'warning', 'title'=>'Menunggu Dihubungi', 'body'=>'Tim Customer Service Klinik Komputer akan menghubungi PIC (' . $order->pic_name . ') melalui WhatsApp ' . $order->pic_phone . ' dalam 1×24 jam kerja.'],
                'diproses'           => ['color'=>'info',    'title'=>'Unit Sedang Diproses', 'body'=>'Tim kami sedang memproses ketersediaan unit. Kami akan menghubungi Anda segera untuk informasi lebih lanjut.'],
                'siap_kirim'         => ['color'=>'accent',  'title'=>'Unit Siap Dikirim!', 'body'=>'Unit laptop sudah siap. Tim kami akan menghubungi Anda untuk koordinasi jadwal pengiriman ke ' . $order->school_city . '.'],
                'diproses_pengiriman'=> ['color'=>'info',    'title'=>'Unit Dalam Perjalanan', 'body'=>'Unit laptop sedang dalam proses pengiriman ke ' . $order->school_name . '. Harap siapkan penerima di lokasi.'],
                'selesai'            => ['color'=>'success', 'title'=>'Pengadaan Selesai', 'body'=>'Terima kasih! Pengadaan laptop Axioo untuk ' . $order->school_name . ' telah berhasil diselesaikan.'],
                'dibatalkan'         => ['color'=>'danger',  'title'=>'Pengajuan Dibatalkan', 'body'=>'Pengajuan ini telah dibatalkan. Hubungi Klinik Komputer untuk informasi lebih lanjut atau ajukan kembali.'],
                default              => ['color'=>'info',    'title'=>'Sedang Diproses', 'body'=>'Pengajuan Anda sedang ditangani oleh tim kami.'],
            };
            @endphp
            <div class="alert alert-{{ $nextInfo['color'] === 'accent' ? 'warning' : $nextInfo['color'] }}" style="margin-bottom:16px; align-items:flex-start;">
                <div>
                    <div style="font-weight:700; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">{{ $nextInfo['title'] }}</div>
                    <div style="font-size:0.85rem; line-height:1.6;">{{ $nextInfo['body'] }}</div>
                </div>
            </div>

            {{-- Hubungi Kami --}}
            <div class="card" style="background:var(--bg-alt); text-align:center;">
                <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:10px;">Ada pertanyaan mengenai pengadaan ini?</div>
                <a href="https://wa.me/6281390727420?text={{ urlencode('Halo Klinik Komputer, saya ingin menanyakan status pengajuan pengadaan dengan nomor referensi ' . $order->order_number . ' dari ' . $order->school_name) }}"
                   target="_blank"
                   class="btn btn-primary btn-sm" style="margin-right:8px;">
                    Tanya via WhatsApp
                </a>
                <a href="{{ route('procurement.index') }}" class="btn btn-outline btn-sm">
                    Ajukan Pengadaan Baru
                </a>
            </div>
        </div>
        @endisset

        {{-- Empty state saat belum cari --}}
        @if(!isset($order) && !$errors->any())
        <div style="text-align:center; padding:24px; color:var(--text-muted);">
            <div style="font-size:2rem; margin-bottom:8px; opacity:0.3;">◎</div>
            <div style="font-size:0.85rem;">Masukkan nomor referensi di atas untuk melihat status pengadaan Anda.</div>
            <div style="font-size:0.78rem; margin-top:8px;">
                Belum punya nomor referensi?
                <a href="{{ route('procurement.index') }}" style="color:var(--primary); font-weight:600;">Ajukan pengadaan di sini</a>
            </div>
        </div>
        @endif

    </div>
</section>
</div>
@endsection
