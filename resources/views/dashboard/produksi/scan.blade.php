@extends('layouts.dashboard')
@section('title', 'Scan Serial Number — Produksi')
@section('page_title', 'Scan Serial Number')
@section('page_subtitle', $order->order_number . ' | ' . $order->school_name)

@section('sidebar_nav')
<a href="{{ route('dashboard.produksi') }}" class="active">
    <span class="nav-icon">Daftar Project</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
@endsection

@section('content')
<div style="max-width: 900px;">
    <a href="{{ route('dashboard.produksi') }}" class="btn btn-outline btn-sm" style="margin-bottom: 16px;">Kembali ke Daftar</a>

    {{-- Order Information Card --}}
    <div class="dash-card" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <h3 style="font-size: 1.15rem; color: var(--text-primary);">{{ $order->school_name }}</h3>
                <p style="color: var(--text-muted); font-size: 0.78rem; margin-top: 2px;">
                    {{ $order->school_type_label }} &bull; {{ $order->school_city }} &bull; {{ $order->axioo_model }} ({{ strtoupper($order->axioo_series) }})
                </p>
            </div>
            <div style="text-align: right; min-width: 140px;">
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 2px;">Status Pengadaan</div>
                <span class="badge badge-{{ $order->status_color }}" id="orderStatusBadge">{{ $order->status_label }}</span>
            </div>
        </div>
    </div>

    @php
        $scannedCount = $order->kits->count();
        $isCompleted = $scannedCount >= $order->total_units;
    @endphp

    {{-- Scan Panel Grid --}}
    <div style="display: grid; grid-template-columns: 1fr 280px; gap: 20px; align-items: start; margin-bottom: 20px;">
        
        {{-- Scan Form Panel --}}
        <div class="dash-card" id="scanPanel" style="border-color: var(--primary);">
            <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 14px; margin-bottom: 16px;">
                <h4 style="margin: 0; color: var(--primary);">Input Serial Number Kit Laptop</h4>
            </div>
            
            <div class="dash-card-body">
                {{-- Completed Banner --}}
                <div id="completeBanner" style="display: {{ $isCompleted ? 'block' : 'none' }}; text-align: center; padding: 24px 0;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(69,178,107,0.08); border: 2px solid var(--success); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-weight: 800; color: var(--success);">✓</div>
                    <h4 style="color: var(--success); margin-bottom: 4px;">Scanning Selesai!</h4>
                    <p style="font-size: 0.8rem; color: var(--text-muted); max-width: 320px; margin: 0 auto;">Semua {{ $order->total_units }} kit laptop untuk project ini telah selesai discan dan siap dikirim.</p>
                </div>

                {{-- Form --}}
                <form id="scanForm" style="display: {{ $isCompleted ? 'none' : 'block' }};">
                    <div id="formError" class="alert alert-error" style="display: none; margin-bottom: 14px; font-size: 0.8rem; padding: 10px 14px;"></div>
                    
                    {{-- Member ID --}}
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="color: var(--primary); font-weight: 700; font-size: 0.75rem;">ID Member Siswa (Generated)</label>
                        <input type="text" id="member_id" class="form-control" value="{{ $nextMemberId }}" readonly 
                               style="background: var(--bg-alt); font-family: monospace; font-weight: 700; color: var(--primary-dark); font-size: 0.95rem; letter-spacing: 1px; padding: 12px 14px; border-radius: 6px; border: 1px solid var(--border);">
                    </div>

                    <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 6px;">Scan Serial Number Komponen</div>

                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 22px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 700; margin-bottom: 4px;">1. Motherboard SN <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="motherboard_sn" class="form-control scan-input" placeholder="Scan barcode Motherboard" autocomplete="off" required style="padding: 12px 14px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #c8d3c9;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 700; margin-bottom: 4px;">2. RAM SN <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="ram_sn" class="form-control scan-input" placeholder="Scan barcode RAM" autocomplete="off" required style="padding: 12px 14px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #c8d3c9;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 700; margin-bottom: 4px;">3. SSD SN <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="ssd_sn" class="form-control scan-input" placeholder="Scan barcode SSD" autocomplete="off" required style="padding: 12px 14px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #c8d3c9;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 700; margin-bottom: 4px;">4. Screen/Display SN <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="screen_sn" class="form-control scan-input" placeholder="Scan barcode Layar" autocomplete="off" required style="padding: 12px 14px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #c8d3c9;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.72rem; font-weight: 700; margin-bottom: 4px;">5. Battery SN <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="battery_sn" class="form-control scan-input" placeholder="Scan barcode Baterai" autocomplete="off" required style="padding: 12px 14px; border-radius: 6px; font-size: 0.9rem; border: 1px solid #c8d3c9;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px; font-weight: 700; font-size: 0.85rem; border-radius: 6px;">Bungkus Kit & Generate ID Member</button>
                </form>
            </div>
        </div>

        {{-- Progress Panel --}}
        <div class="dash-card">
            <div class="dash-card-body">
                <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 12px;">Progres Scan SN</div>
                <div style="text-align: center; padding: 12px 0 20px; border-bottom: 1px solid var(--border-light);">
                    <div style="font-size: 3.2rem; font-weight: 900; color: var(--primary); line-height: 1;" id="progressNum">
                        {{ $scannedCount }}
                    </div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px;">
                        Dari {{ $order->total_units }} unit
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">Spesifikasi Unit</div>
                    <div style="font-size: 0.8rem; display: flex; flex-direction: column; gap: 6px;">
                        <div><span style="color: var(--text-muted);">Model:</span> <br><strong>{{ $order->axioo_model }}</strong></div>
                        <div><span style="color: var(--text-muted);">Seri:</span> <span class="badge {{ $order->axioo_series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size: 0.62rem; margin-top: 2px;">{{ strtoupper($order->axioo_series) }}</span></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- History Scanned Kits --}}
    <div class="dash-card">
        <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Kit Laptop Yang Sudah Di-scan (<span id="historyHeaderCount">{{ $scannedCount }}</span>)</h3>
            <a href="{{ route('procurement.export-kits', $order) }}" class="btn-export">
                EXPORT
            </a>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            <div class="table-wrap" style="border: none; border-radius: 0;">
                <table id="historyTable">
                    <thead>
                        <tr>
                            <th>ID Member</th>
                            <th>Motherboard SN</th>
                            <th>RAM SN</th>
                            <th>SSD SN</th>
                            <th>Screen SN</th>
                            <th>Battery SN</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->kits->reverse() as $kit)
                        <tr>
                            <td>
                                <div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $kit->member_id }}</div>
                                @if($kit->status === 'activated')
                                <div style="margin-top: 4px; font-size: 0.72rem; line-height: 1.35;">
                                    <strong style="color: var(--text-primary);">{{ $kit->student_name }}</strong>
                                    <div style="color: var(--text-muted); font-size: 0.65rem;">
                                        Telp: {{ optional($kit->customer)->phone ?? '—' }} <br>
                                        Email: {{ optional($kit->customer)->email ?? '—' }}
                                    </div>
                                </div>
                                @endif
                            </td>
                            @foreach(['Motherboard', 'RAM', 'SSD', 'Screen', 'Battery'] as $compName)
                                @php
                                    $comp = $kit->components->where('component_name', $compName)->first();
                                @endphp
                                <td style="font-family: monospace; font-size: 0.78rem;">{{ $comp ? $comp->serial_number : '—' }}</td>
                            @endforeach
                            <td>
                                <span class="badge badge-{{ $kit->status === 'activated' ? 'success' : 'secondary' }}" style="font-size: 0.65rem;">
                                    {{ $kit->status === 'activated' ? 'Aktif' : 'Disimpan' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data-row">
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada kit laptop yang di-scan untuk order ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .scan-input:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(95,138,99,0.15) !important;
        background: rgba(95,138,99,0.02) !important;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = Array.from(document.querySelectorAll('.scan-input'));
        const form = document.getElementById('scanForm');
        const errorDiv = document.getElementById('formError');

        // Autofocus first input on load
        if (inputs.length > 0) inputs[0].focus();

        // Keyboard navigation for barcode scanner (auto jump focus on enter/submit)
        inputs.forEach((input, index) => {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Stop standard form submission
                    
                    if (this.value.trim() !== '') {
                        // Check if we have a next input field
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            // If last input, trigger form submit
                            submitScan();
                        }
                    }
                }
            });
        });

        // Submit kit scan
        function submitScan() {
            // Check if all fields are filled
            let valid = true;
            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    valid = false;
                    input.focus();
                    return false;
                }
            });

            if (!valid) return;

            errorDiv.style.display = 'none';

            // Gather inputs
            const payload = {
                _token: '{{ csrf_token() }}',
                member_id: document.getElementById('member_id').value,
                motherboard_sn: document.getElementById('motherboard_sn').value,
                ram_sn: document.getElementById('ram_sn').value,
                ssd_sn: document.getElementById('ssd_sn').value,
                screen_sn: document.getElementById('screen_sn').value,
                battery_sn: document.getElementById('battery_sn').value,
            };

            // Disable inputs while sending
            inputs.forEach(input => input.disabled = true);

            fetch('{{ route("dashboard.produksi.scan.store", $order) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                // Re-enable inputs
                inputs.forEach(input => input.disabled = false);

                if (data.success) {
                    // Update progress UI
                    document.getElementById('progressNum').textContent = data.progress;
                    document.getElementById('historyHeaderCount').textContent = data.progress;
                    
                    // Reset fields
                    inputs.forEach(input => input.value = '');
                    document.getElementById('member_id').value = data.next_member;

                    // Append row to history table
                    const tbody = document.querySelector('#historyTable tbody');
                    const noDataRow = tbody.querySelector('.no-data-row');
                    if (noDataRow) noDataRow.remove();

                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td><div style="font-family: monospace; font-weight: 700; color: var(--primary); font-size: 0.82rem;">${payload.member_id}</div></td>
                        <td style="font-family: monospace; font-size: 0.78rem;">${payload.motherboard_sn}</td>
                        <td style="font-family: monospace; font-size: 0.78rem;">${payload.ram_sn}</td>
                        <td style="font-family: monospace; font-size: 0.78rem;">${payload.ssd_sn}</td>
                        <td style="font-family: monospace; font-size: 0.78rem;">${payload.screen_sn}</td>
                        <td style="font-family: monospace; font-size: 0.78rem;">${payload.battery_sn}</td>
                        <td><span class="badge badge-secondary" style="font-size: 0.65rem;">Disimpan</span></td>
                    `;
                    tbody.insertBefore(newRow, tbody.firstChild);

                    // Alert toast or text
                    const toast = document.createElement('div');
                    toast.className = 'flash-notification alert alert-success';
                    toast.style.position = 'fixed';
                    toast.style.bottom = '20px';
                    toast.style.right = '20px';
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `<span>${data.message}</span>`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);

                    // Check if complete
                    if (data.is_completed) {
                        form.style.display = 'none';
                        document.getElementById('completeBanner').style.display = 'block';
                        
                        const badge = document.getElementById('orderStatusBadge');
                        badge.className = 'badge badge-accent';
                        badge.textContent = 'Unit Siap Dikirim';
                    } else {
                        // Focus back on first input
                        inputs[0].focus();
                    }

                } else {
                    errorDiv.textContent = data.message || 'Gagal menyimpan data.';
                    errorDiv.style.display = 'block';
                    inputs[0].focus();
                }
            })
            .catch(err => {
                inputs.forEach(input => input.disabled = false);
                errorDiv.textContent = 'Terjadi kesalahan jaringan atau server.';
                errorDiv.style.display = 'block';
                inputs[0].focus();
            });
        }

        // Trigger on click as well
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitScan();
        });
    });
</script>
@endpush
