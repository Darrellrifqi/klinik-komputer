@extends('layouts.app')
@section('title', 'Cek Status Servis — Klinik Komputer')

@section('content')
<div style="padding-top: 64px;">
<section class="section">
    <div class="section-inner" style="max-width: 680px;">
        <div style="text-align: center; margin-bottom: 32px;">
            <div class="label-line">Pelacakan</div>
            <h1>Status Perbaikan</h1>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Masukkan nomor tiket untuk melacak perkembangan perbaikan unit Anda secara real-time.</p>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('service.track') }}" style="margin-bottom: 28px;">
            <div style="display: flex; gap: 8px;">
                <input type="text" name="ticket_number" class="form-control"
                       value="{{ $ticketNumber ?? '' }}"
                       placeholder="Nomor Tiket Servis..."
                       style="flex: 1; font-size: 0.95rem; font-family: monospace;">
                <button type="submit" class="btn btn-primary">Lacak Unit</button>
            </div>
        </form>

        @if(isset($trackError))
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <span>{{ $trackError }}</span>
            </div>
        @elseif($ticketNumber && !$ticket)
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <span>Nomor Tiket Servis <strong>{{ $ticketNumber }}</strong> tidak terdaftar. Periksa kembali penulisan nomor tiket Anda.</span>
            </div>
        @endif

        @if($ticket)
        <!-- Ticket Found -->
        <div class="card" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <div style="font-family: monospace; font-size: 1.05rem; color: var(--primary); font-weight: 700;">{{ $ticket->ticket_number }}</div>
                        @if($ticket->airtable_service_number)
                            <span class="badge badge-success" style="font-family: monospace; font-size: 0.72rem; padding: 3px 8px;">
                                No. Tiket Servis: {{ $ticket->airtable_service_number }}
                            </span>
                        @endif
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Antrian #{{ $ticket->queue_number }} &bull; Dibuat {{ $ticket->created_at->format('d M Y, H:i') }}</div>
                </div>
                <span class="badge badge-{{ $ticket->status_color }}" style="padding: 6px 12px;">
                    {{ $ticket->status_label }}
                </span>
            </div>
                </span>
            </div>

<style>
    /* Overriding main.css pseudo-element to prevent double line artifact */
    .progress-steps-wrap {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        margin: 28px 0 36px 0;
        padding: 0;
    }
    .progress-steps-wrap .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
        text-align: center;
    }
    /* Explicitly hide main.css pseudo-element line */
    .progress-steps-wrap .step-item::after {
        display: none !important;
    }

    /* Single clean background line running horizontally */
    .progress-track-line-bg {
        position: absolute;
        top: 17px;
        left: 10%;
        right: 10%;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
        border-radius: 2px;
    }
    /* Single clean active fill line */
    .progress-track-line-fill {
        position: absolute;
        top: 17px;
        left: 10%;
        height: 3px;
        background: var(--primary, #5f8a63);
        z-index: 1;
        border-radius: 2px;
        transition: width 0.4s ease;
    }

    .progress-steps-wrap .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 12px;
        position: relative;
        z-index: 3;
        transition: all 0.3s ease;
    }
    .progress-steps-wrap .step-item.done .step-circle {
        background: var(--primary, #5f8a63);
        border-color: var(--primary, #5f8a63);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(95, 138, 99, 0.35);
    }
    .progress-steps-wrap .step-item.active .step-circle {
        background: #ffffff;
        border-color: var(--primary, #5f8a63);
        color: var(--primary, #5f8a63);
        box-shadow: 0 0 0 4px rgba(95, 138, 99, 0.2);
    }

    .progress-steps-wrap .step-label {
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748b;
        line-height: 1.3;
        min-height: 36px;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        text-align: center;
        width: 100%;
        padding: 0 4px;
        box-sizing: border-box;
    }
    .progress-steps-wrap .step-item.done .step-label,
    .progress-steps-wrap .step-item.active .step-label {
        color: var(--primary, #5f8a63);
    }
</style>

            <!-- Progress Steps -->
            @php
            $steps = [
                ['key'=>'waiting',          'label'=>'Menunggu'],
                ['key'=>'checking',         'label'=>'Pengecekan Teknisi'],
                ['key'=>'konfirmasi_user',  'label'=>'Konfirmasi User'],
                ['key'=>'proses_service',   'label'=>'Proses Service'],
                ['key'=>'done',             'label'=>'Siap Diambil'],
                ['key'=>'sudah_diambil',    'label'=>'Sudah Diambil'],
            ];
            $currentStep = $ticket->status_step;
            $fillPercent = match($currentStep) {
                1 => 0,
                2 => 20,
                3 => 40,
                4 => 60,
                5 => 80,
                6 => 100,
                default => 0
            };
            @endphp

            @if($ticket->status !== 'cancelled')
            <div class="progress-steps-wrap">
                <div class="progress-track-line-bg"></div>
                <div class="progress-track-line-fill" style="width: calc({{ $fillPercent }}% * 0.8);"></div>
                @foreach($steps as $i => $step)
                <div class="step-item {{ $currentStep > $i ? 'done' : ($currentStep === $i+1 ? 'active' : '') }}">
                    <div class="step-circle">
                        @if($currentStep > $i) &check;
                        @else {{ $i+1 }}
                        @endif
                    </div>
                    <div class="step-label">{{ $step['label'] }}</div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <span>Tiket perbaikan ini telah dibatalkan.</span>
            </div>
            @endif

            <!-- Unit Info & Dropoff Schedule -->
            <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 12px;">
                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Unit & Penyerahan</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.82rem;">
                    <div><span style="color: var(--text-muted);">Nama:</span> <strong>{{ $ticket->customer_name }}</strong></div>
                    <div><span style="color: var(--text-muted);">Tipe:</span> <strong>{{ strtoupper($ticket->unit_type) }}</strong></div>
                    <div><span style="color: var(--text-muted);">Merek & Model:</span> <strong>{{ $ticket->brand }} {{ $ticket->model }}</strong></div>
                    <div><span style="color: var(--text-muted);">Penyerahan ke Kantor:</span> <strong style="color: var(--primary);">{{ $ticket->dropoff_schedule ?: '-' }}</strong></div>
                </div>
            </div>

            <!-- Technician Info (if checking started) -->
            @if($ticket->start_check_date)
            <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 12px;">
                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Laporan Pengecekan</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 0.82rem;">
                    <div><span style="color: var(--text-muted);">Mulai Pemeriksaan:</span> <strong>{{ $ticket->start_check_date->format('d M Y') }}</strong></div>
                    <div><span style="color: var(--text-muted);">Teknisi PJ:</span> <strong>{{ $ticket->pic_name ?? '-' }}</strong></div>
                </div>
            </div>
            @endif

            <!-- Diagnosis (if checked) -->
            @if($ticket->status_step >= 3 && $ticket->components_issue)
            <div style="background: rgba(59, 142, 202, 0.05); border: 1px solid rgba(59, 142, 202, 0.15); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 12px;">
                <div style="font-size: 0.65rem; color: var(--info); margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Hasil Pemeriksaan</div>
                <div style="margin-bottom: 6px;">
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Komponen Bermasalah:</span>
                    <div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px;">
                        @foreach($ticket->components_issue as $comp)
                        <span class="badge badge-danger" style="font-size: 0.65rem;">{{ $comp }}</span>
                        @endforeach
                    </div>
                </div>
                @if($ticket->cause)
                <div style="margin-top: 8px;">
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Penyebab Kerusakan:</span>
                    <p style="font-size: 0.85rem; margin-top: 2px;">{{ $ticket->cause }}</p>
                </div>
                @endif
                @if($ticket->estimated_cost)
                <div style="margin-top: 8px;">
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Estimasi Biaya:</span>
                    <p style="font-size: 1.05rem; font-weight: 800; color: var(--accent); margin-top: 2px;">Rp {{ number_format($ticket->estimated_cost, 0, ',', '.') }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- History Timeline -->
            @if($ticket->histories->count() > 0)
            <div style="margin-top: 16px;">
                <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Log Riwayat Perbaikan</div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($ticket->histories as $h)
                    <div style="display: flex; gap: 10px; align-items: flex-start;">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--primary); margin-top: 6px; flex-shrink: 0;"></div>
                        <div style="flex: 1; background: var(--bg-alt); border-radius: var(--radius-sm); border: 1px solid var(--border); padding: 8px 12px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 2px; font-size: 0.78rem;">
                                <span class="badge badge-primary" style="font-size: 0.65rem; padding: 2px 6px;">{{ ucfirst($h->new_status) }}</span>
                                <span style="color: var(--text-muted); font-size: 0.72rem;">{{ $h->created_at->format('d M, H:i') }}</span>
                            </div>
                            @if($h->notes)
                            <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px;">{{ $h->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div style="display: flex; gap: 12px; justify-content: center; align-items: center; margin-top: 24px; flex-wrap: wrap;">
            <a href="https://wa.me/6285103051000?text=Halo, saya ingin menanyakan status tiket {{ $ticket->ticket_number }}"
               target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 700; padding: 10px 20px;">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                WhatsApp Customer Service
            </a>
            <a href="{{ route('service.track') }}" class="btn btn-outline" style="font-weight: 600; padding: 10px 20px;">Lacak Tiket Lain</a>
        </div>
        @endif

        @if(!$ticketNumber)
        <div style="text-align: center; padding: 20px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-card); color: var(--text-secondary); font-size: 0.88rem; font-weight: 600; margin-top: 10px;">
            Masukkan Nomor Tiket Servis Anda di atas untuk memantau progress perbaikan secara real-time.
        </div>
        @endif
    </div>
</section>
</div>
@endsection
