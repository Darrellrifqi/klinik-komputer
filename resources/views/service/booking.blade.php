@extends('layouts.app')
@section('title', 'Booking Servis | Klinik Komputer')
@section('meta_description', 'Book layanan servis komputer dan laptop Anda secara online. Dapatkan nomor antrian dan pantau progress perbaikan secara real-time.')

@section('content')
<div style="padding-top: 64px;">
<section class="section">
    <div class="section-inner" style="max-width: 680px;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 36px;">
            <div class="label-line">Pendaftaran</div>
            <h1 style="margin-bottom: 10px; font-size: 2rem;">Booking Servis Online</h1>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Lengkapi formulir untuk mendapatkan nomor antrian. Serahkan unit Anda ke kantor kami dengan menunjukkan nomor antrian tersebut.</p>
        </div>

        <!-- Step Indicator -->
        <div class="progress-steps" style="margin-bottom: 32px;" id="stepIndicator">
            <div class="step-item active" id="si1">
                <div class="step-circle">1</div>
                <div class="step-label">Data Diri</div>
            </div>
            <div class="step-item" id="si2">
                <div class="step-circle">2</div>
                <div class="step-label">Spesifikasi Unit</div>
            </div>
            <div class="step-item" id="si3">
                <div class="step-circle">3</div>
                <div class="step-label">Kerusakan</div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('service.store') }}" method="POST" id="serviceForm">
            @csrf

            <!-- STEP 1: Data Diri -->
            <div id="step1" class="step-panel">
                <div class="card" style="margin-bottom: 20px;">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary);">Informasi Kontak</h3>
                        <p style="color: var(--text-muted); font-size: 0.78rem;">Data untuk verifikasi dan notifikasi status pengerjaan.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span>*</span></label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-error @enderror"
                               value="{{ old('customer_name', auth()->user()?->name) }}"
                               placeholder="Masukkan nama lengkap" required>
                        @error('customer_name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp <span>*</span></label>
                        <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-error @enderror"
                               value="{{ old('customer_phone', auth()->user()?->phone) }}"
                               placeholder="Contoh: 081234567890" required>
                        @error('customer_phone') <span class="form-error">{{ $message }}</span> @enderror
                        <span class="form-hint">Nomor aktif untuk koordinasi estimasi biaya pengerjaan.</span>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Selanjutnya</button>
                </div>
            </div>

            <!-- STEP 2: Data Unit -->
            <div id="step2" class="step-panel" style="display: none;">
                <div class="card" style="margin-bottom: 20px;">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary);">Spesifikasi Unit</h3>
                        <p style="color: var(--text-muted); font-size: 0.78rem;">Data teknis perangkat keras yang akan diperiksa.</p>
                    </div>

                    @if(auth()->check() && $registeredKits->count() > 0)
                        <div class="form-group" style="margin-bottom: 18px; padding: 14px; background: rgba(95, 138, 99, 0.05); border: 1px dashed var(--primary); border-radius: 8px;">
                            <label class="form-label" style="color: var(--primary); font-weight: 700;">Hubungkan dengan Laptop Terdaftar Anda (Member)</label>
                            <select id="laptop_kit_select" class="form-control" style="font-weight: 600;">
                                <option value="">-- Booking Unit Baru (Non-Member / Pilih Manual) --</option>
                                @foreach($registeredKits as $kit)
                                    <option value="{{ $kit->id }}" 
                                            data-brand="Axioo" 
                                            data-model="{{ $kit->unit_model ?? ($kit->axioo_serial_number ? 'Axioo Laptop' : 'Laptop Mandiri') }}"
                                            data-sn="{{ $kit->member_id }}"
                                            data-tuneups="{{ $kit->tune_up_count }}">
                                        {{ $kit->member_id }} - {{ $kit->unit_model ?? ($kit->axioo_serial_number ? 'Axioo Laptop' : 'Laptop Mandiri') }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="form-hint" style="color: var(--text-muted); font-size: 0.72rem; margin-top: 4px;">Pilih laptop member Anda untuk mengklaim kuota gratis Tune Up.</span>
                        </div>
                    @endif

                    <input type="hidden" name="laptop_kit_id" id="laptop_kit_id">

                    {{-- Free Tune-up Selection Box --}}
                    <div id="tune_up_box" class="form-group" style="display: none; margin-bottom: 18px; padding: 14px; background: rgba(226, 140, 59, 0.08); border: 1px solid var(--warning); border-radius: 8px;">
                        <label class="form-label" style="color: var(--warning-dark, #b45309); font-weight: 800; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="is_tune_up" id="is_tune_up_checkbox" value="1" style="transform: scale(1.15);">
                            Klaim Jatah Gratis Tune Up Unit
                        </label>
                        <div id="tune_up_quota_text" style="font-size: 0.78rem; margin-top: 6px; color: var(--text-primary); font-weight: 600;">
                            Sisa kuota gratis Tune Up untuk unit ini: 0/2
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Perangkat <span>*</span></label>
                        <select name="unit_type" id="unit_type_select" class="form-control @error('unit_type') is-error @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="laptop"  {{ old('unit_type')==='laptop'  ? 'selected' : '' }}>Laptop</option>
                            <option value="desktop" {{ old('unit_type')==='desktop' ? 'selected' : '' }}>Desktop / PC</option>
                            <option value="printer" {{ old('unit_type')==='printer' ? 'selected' : '' }}>Printer</option>
                            <option value="other"   {{ old('unit_type')==='other'   ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('unit_type') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Merek Perangkat <span>*</span></label>
                            <input type="text" name="brand" id="brand_input" class="form-control @error('brand') is-error @enderror"
                                   value="{{ old('brand') }}" placeholder="Contoh: Axioo, Asus, HP" required>
                            @error('brand') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seri / Model <span>*</span></label>
                            <input type="text" name="model" id="model_input" class="form-control @error('model') is-error @enderror"
                                   value="{{ old('model') }}" placeholder="Contoh: Hype 5" required>
                            @error('model') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-outline" onclick="prevStep(1)">Kembali</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Selanjutnya</button>
                </div>
            </div>

            <!-- STEP 3: Deskripsi Kerusakan -->
            <div id="step3" class="step-panel" style="display: none;">
                <div class="card" style="margin-bottom: 20px;">
                    <div style="margin-bottom: 20px;">
                        <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary);">Detail Kerusakan</h3>
                        <p style="color: var(--text-muted); font-size: 0.78rem;">Informasikan sedetail mungkin masalah perangkat Anda.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Keluhan <span>*</span></label>
                        <textarea name="damage_description" class="form-control @error('damage_description') is-error @enderror"
                                  rows="4" placeholder="Tuliskan keluhan kerusakan yang dialami (misal: Layar bergaris, tidak bisa menyala, keyboard tidak berfungsi)..." required>{{ old('damage_description') }}</textarea>
                        @error('damage_description') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group" style="margin-top: 14px;">
                        <label class="form-label">Kapan unit akan diserahkan ke kantor? <span>*</span></label>
                        <input type="text" name="dropoff_schedule" class="form-control @error('dropoff_schedule') is-error @enderror"
                               value="{{ old('dropoff_schedule') }}"
                               placeholder="Contoh: Rabu, 22 Juli 2026 (Jam 10:00 WIB)" required>
                        @error('dropoff_schedule') <span class="form-error">{{ $message }}</span> @enderror
                        <span class="form-hint">Format: Hari, tanggal, bulan, tahun (Contoh: Rabu, 22 Juli 2026).</span>
                    </div>

                    <!-- Summary Preview -->
                    <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px; margin-top: 8px;">
                        <div style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Ringkasan Booking</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 0.8rem;">
                            <div><span style="color: var(--text-muted);">Nama:</span> <span id="preview-name" style="color: var(--text-primary); font-weight: 600;">-</span></div>
                            <div><span style="color: var(--text-muted);">Telp:</span> <span id="preview-phone" style="color: var(--text-primary); font-weight: 600;">-</span></div>
                            <div><span style="color: var(--text-muted);">Unit:</span> <span id="preview-unit" style="color: var(--text-primary); font-weight: 600;">-</span></div>
                            <div><span style="color: var(--text-muted);">Merek:</span> <span id="preview-brand" style="color: var(--text-primary); font-weight: 600;">-</span></div>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button type="button" class="btn btn-outline" onclick="prevStep(2)">Kembali</button>
                    <button type="submit" class="btn btn-primary">Dapatkan Nomor Antrian</button>
                </div>
            </div>
        </form>
    </div>
</section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const laptopSelect = document.getElementById('laptop_kit_select');
    if (laptopSelect) {
        laptopSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kitId = this.value;
            
            const kitIdInput = document.getElementById('laptop_kit_id');
            const unitTypeSelect = document.getElementById('unit_type_select');
            const brandInput = document.getElementById('brand_input');
            const modelInput = document.getElementById('model_input');
            
            const tuneUpBox = document.getElementById('tune_up_box');
            const tuneUpCheckbox = document.getElementById('is_tune_up_checkbox');
            const tuneUpQuotaText = document.getElementById('tune_up_quota_text');
            
            if (kitId) {
                kitIdInput.value = kitId;
                unitTypeSelect.value = 'laptop';
                brandInput.value = selectedOption.getAttribute('data-brand') || '';
                modelInput.value = selectedOption.getAttribute('data-model') || '';
                
                // Disable editing if selected member unit
                unitTypeSelect.disabled = true;
                brandInput.readOnly = true;
                modelInput.readOnly = true;
                
                // Add hidden cloned inputs for disabled fields so they still get submitted
                createOrUpdateHiddenInput('hidden_unit_type', 'unit_type', 'laptop');
                createOrUpdateHiddenInput('hidden_brand', 'brand', brandInput.value);
                createOrUpdateHiddenInput('hidden_model', 'model', modelInput.value);
                
                // Check tune-up quota
                const tuneUpsCount = parseInt(selectedOption.getAttribute('data-tuneups') || '0', 10);
                const maxFree = 2;
                const remaining = maxFree - tuneUpsCount;
                
                tuneUpBox.style.display = 'block';
                if (remaining > 0) {
                    tuneUpQuotaText.innerHTML = `Sisa kuota gratis Tune Up tahun ini: <strong>${remaining}/${maxFree}</strong>`;
                    tuneUpCheckbox.disabled = false;
                    tuneUpCheckbox.checked = false;
                } else {
                    tuneUpQuotaText.innerHTML = `<span style="color: var(--danger);">Kuota Tune-Up Gratis tahun ini telah habis untuk unit ini (${tuneUpsCount}/${maxFree}).</span>`;
                    tuneUpCheckbox.disabled = true;
                    tuneUpCheckbox.checked = false;
                }
            } else {
                kitIdInput.value = '';
                unitTypeSelect.value = '';
                brandInput.value = '';
                modelInput.value = '';
                
                unitTypeSelect.disabled = false;
                brandInput.readOnly = false;
                modelInput.readOnly = false;
                
                removeHiddenInput('hidden_unit_type');
                removeHiddenInput('hidden_brand');
                removeHiddenInput('hidden_model');
                
                tuneUpBox.style.display = 'none';
                tuneUpCheckbox.checked = false;
            }
        });
        
        // Handle tune-up checkbox toggle to auto-populate damage description in step 3
        const tuneUpCheckbox = document.getElementById('is_tune_up_checkbox');
        if (tuneUpCheckbox) {
            tuneUpCheckbox.addEventListener('change', function() {
                const damageTextarea = document.querySelector('[name=damage_description]');
                if (this.checked) {
                    damageTextarea.value = "Klaim layanan Tune-Up gratis unit member (pemeriksaan menyeluruh, pembersihan debu, penggantian thermal paste, optimasi software).";
                } else {
                    damageTextarea.value = "";
                }
            });
        }
    }
});

function createOrUpdateHiddenInput(id, name, value) {
    let input = document.getElementById(id);
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.id = id;
        input.name = name;
        document.getElementById('procurementForm').appendChild(input);
    }
    input.value = value;
}

function removeHiddenInput(id) {
    const input = document.getElementById(id);
    if (input) {
        input.remove();
    }
}

let currentStep = 1;

function nextStep(to) {
    if (to === 2) {
        const name = document.querySelector('[name=customer_name]').value;
        const phone = document.querySelector('[name=customer_phone]').value;
        if (!name.trim() || !phone.trim()) {
            alert('Harap isi Nama dan Nomor WhatsApp terlebih dahulu.');
            return;
        }
    }
    if (to === 3) {
        const type = document.querySelector('[name=unit_type]').value;
        const brand = document.querySelector('[name=brand]').value;
        const model = document.querySelector('[name=model]').value;
        if (!type || !brand.trim() || !model.trim()) {
            alert('Harap lengkapi data spesifikasi unit.');
            return;
        }
        // Update preview
        document.getElementById('preview-name').textContent  = document.querySelector('[name=customer_name]').value;
        document.getElementById('preview-phone').textContent = document.querySelector('[name=customer_phone]').value;
        document.getElementById('preview-unit').textContent  = type.toUpperCase() + ' - ' + model;
        document.getElementById('preview-brand').textContent = brand;
    }
    goToStep(to);
}

function prevStep(to) { goToStep(to); }

function goToStep(step) {
    document.querySelectorAll('.step-panel').forEach(p => p.style.display = 'none');
    document.getElementById('step' + step).style.display = 'block';
    for (let i = 1; i <= 3; i++) {
        const si = document.getElementById('si' + i);
        si.classList.remove('active', 'done');
        if (i < step) si.classList.add('done');
        if (i === step) si.classList.add('active');
    }
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Re-populate on validation error (server side)
@if($errors->any())
    @php $step = 1;
    if($errors->has('unit_type')||$errors->has('brand')||$errors->has('model')) $step=2;
    if($errors->has('damage_description')) $step=3;
    @endphp
    goToStep({{ $step }});
@endif
</script>
@endpush
