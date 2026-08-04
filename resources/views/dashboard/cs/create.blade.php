@extends('layouts.dashboard')
@section('title', 'Buat Tiket Baru | CS')
@section('page_title', 'Buat Tiket Baru')
@section('page_subtitle', 'Input data customer walk-in')

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')
<div style="max-width:760px;">
    <div class="dash-card">
        <div class="dash-card-header">
            <h3>Form Input Tiket Customer</h3>
            <a href="{{ route('dashboard.cs') }}" class="btn btn-outline btn-sm">Kembali</a>
        </div>
        <div class="dash-card-body">
            <form action="{{ route('dashboard.cs.store') }}" method="POST">
                @csrf

                <div style="background:var(--bg-alt); border-radius:var(--radius-sm); padding:16px; margin-bottom:20px; border:1px solid var(--border);">
                    <div style="font-size:0.72rem; color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">
                        Data Customer
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nama Lengkap <span>*</span></label>
                            <input type="text" name="customer_name" class="form-control"
                                   value="{{ old('customer_name') }}" placeholder="Nama customer" required>
                            @error('customer_name')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nomor WhatsApp <span>*</span></label>
                            <input type="tel" name="customer_phone" class="form-control"
                                   value="{{ old('customer_phone') }}" placeholder="081234567890" required>
                            @error('customer_phone')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div style="background:var(--bg-alt); border-radius:var(--radius-sm); padding:16px; margin-bottom:20px; border:1px solid var(--border);">
                    <div style="font-size:0.72rem; color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">
                        Spesifikasi Unit
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tipe Unit <span>*</span></label>
                            <select name="unit_type" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="laptop"  {{ old('unit_type')==='laptop'  ? 'selected':'' }}>Laptop</option>
                                <option value="desktop" {{ old('unit_type')==='desktop' ? 'selected':'' }}>Desktop / PC</option>
                                <option value="other"   {{ old('unit_type')==='other'   ? 'selected':'' }}>Lainnya</option>
                            </select>
                            @error('unit_type')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Merek <span>*</span></label>
                            <input type="text" name="brand" class="form-control"
                                   value="{{ old('brand') }}" placeholder="Contoh: Axioo" required>
                            @error('brand')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Seri / Model <span>*</span></label>
                            <input type="text" name="model" class="form-control"
                                   value="{{ old('model') }}" placeholder="Contoh: Hype 5" required>
                            @error('model')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                </div>

                <div style="background:var(--bg-alt); border-radius:var(--radius-sm); padding:16px; margin-bottom:20px; border:1px solid var(--border);">
                    <div style="font-size:0.72rem; color:var(--warning); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">
                        Klaim Layanan & Keanggotaan (Member)
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:flex-start;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">ID Member (NIK / SN Pengadaan)</label>
                            <input type="text" name="member_id_input" class="form-control"
                                   value="{{ old('member_id_input') }}" placeholder="NIK (Member Umum) atau SN (Pengadaan)" style="font-family: monospace;">
                            <div style="font-size:0.7rem; color:var(--text-muted); margin-top:4px;">
                                Kosongkan jika bukan member. NIK = Member Umum, SN = Member Pengadaan.
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0; padding-top:10px; display:flex; flex-direction:column; gap:8px;">
                            <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:700;">
                                <input type="checkbox" name="is_tune_up" value="1" {{ old('is_tune_up') ? 'checked' : '' }} style="transform: scale(1.15);">
                                Tandai sebagai Deep Care Cleaning Gratis <span style="font-weight:400; color:var(--text-muted);">(kuota member -1)</span>
                            </label>
                            <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:700;">
                                <input type="checkbox" name="is_os_install" value="1" {{ old('is_os_install') ? 'checked' : '' }} style="transform: scale(1.15);">
                                Tandai sebagai Essential Instalasi OS Gratis <span style="font-weight:400; color:var(--text-muted);">(kuota member -1)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div style="background:var(--bg-alt); border-radius:var(--radius-sm); padding:16px; margin-bottom:20px; border:1px solid var(--border);">
                    <div style="font-size:0.72rem; color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">
                        Keluhan & Catatan
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Kerusakan <span>*</span></label>
                        <textarea name="damage_description" class="form-control" rows="4"
                                  placeholder="Tuliskan keluhan kerusakan perangkat..." required>{{ old('damage_description') }}</textarea>
                        @error('damage_description')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan Tambahan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="Catatan tambahan internal...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <a href="{{ route('dashboard.cs') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Buat Tiket & Antrian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
