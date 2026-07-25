@extends('layouts.dashboard')
@section('title', 'Kelola Token PKL — Superadmin')
@section('page_title', 'Kelola Token Registrasi PKL')
@section('page_subtitle', 'Kelola kuartal aktif dan token pendaftaran mandiri siswa PKL')

@section('sidebar_nav')
@include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="max-width: 1000px;">
    
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom: 20px;">{{ $errors->first() }}</div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 24px;">
        
        <!-- Left: Create Token Form -->
        <div class="dash-card">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">Buat Token Kuartal</h3>
            </div>
            <div class="dash-card-body" style="padding: 20px;">
                <form action="{{ route('admin.pkl.tokens.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 700;">Kode Token <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="token" class="form-control" placeholder="Contoh: PKL-2026-Q3" required style="padding: 10px; border-radius: 6px; font-family: monospace; text-transform: uppercase;">
                        <small style="font-size: 0.72rem; color: var(--text-muted); display: block; margin-top: 4px;">Kode unik yang akan dimasukkan siswa saat registrasi.</small>
                    </div>

                    <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700;">Kuartal <span style="color: var(--danger);">*</span></label>
                            <select name="quarter" class="form-control" required style="padding: 10px; border-radius: 6px;">
                                <option value="Q1">Q1 (Jan-Mar)</option>
                                <option value="Q2">Q2 (Apr-Jun)</option>
                                <option value="Q3">Q3 (Jul-Sep)</option>
                                <option value="Q4">Q4 (Okt-Des)</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-weight: 700;">Tahun <span style="color: var(--danger);">*</span></label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" min="2020" max="2050" required style="padding: 10px; border-radius: 6px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; border-radius: 6px;">
                        Buat Token Baru
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Tokens List Table -->
        <div class="dash-card">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">Daftar Token Terdaftar</h3>
            </div>
            <div class="dash-card-body" style="padding: 0;">
                @if($tokens->count() > 0)
                <div class="table-wrap" style="border: none; border-radius: 0; margin: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-light); background: var(--bg-alt);">
                                <th style="text-align: left; padding: 14px 16px; font-size: 0.8rem;">Token</th>
                                <th style="text-align: left; padding: 14px 16px; font-size: 0.8rem;">Periode</th>
                                <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem;">Siswa</th>
                                <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem;">Status</th>
                                <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tokens as $t)
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 14px 16px;">
                                    <div style="font-family: monospace; font-weight: 700; font-size: 0.9rem; color: var(--primary);">{{ $t->token }}</div>
                                </td>
                                <td style="padding: 14px 16px; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary);">
                                    {{ $t->quarter }} - {{ $t->year }}
                                </td>
                                <td style="padding: 14px 16px; text-align: center; font-size: 0.88rem; font-weight: 700; color: var(--text-primary);">
                                    {{ $t->students_count }} anak
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <span class="badge badge-{{ $t->is_active ? 'success' : 'secondary' }}" style="font-size: 0.68rem; padding: 4px 8px;">
                                        {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.pkl.tokens.toggle', $t) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline" style="font-size: 0.68rem; padding: 5px 10px; border-radius: 4px; font-weight: 700; border-color: var(--border);">
                                                {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.pkl.tokens.destroy', $t) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus token ini? Semua siswa yang mendaftar menggunakan token ini juga akan ikut terhapus.')" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="font-size: 0.68rem; padding: 5px 10px; border-radius: 4px; font-weight: 700; background: var(--danger); color: #fff; border: none;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 12px 16px;">
                    {{ $tokens->links() }}
                </div>
                @else
                <div class="empty-state" style="padding: 40px 20px; text-align: center;">
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Belum ada token registrasi PKL yang dibuat.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
