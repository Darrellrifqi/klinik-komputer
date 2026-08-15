@extends('layouts.dashboard')
@section('title', 'Kelola Divisi PKL | Superadmin')
@section('page_title', 'Kelola Divisi Magang & PKL')
@section('page_subtitle', 'Atur daftar divisi magang yang dapat dipilih calon siswa PKL saat registrasi')

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
        
        <!-- Left: Create Division Form -->
        <div class="dash-card">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">Tambah Divisi Baru</h3>
            </div>
            <div class="dash-card-body" style="padding: 20px;">
                <form action="{{ route('admin.pkl.divisions.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 700;">Nama Divisi <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Digital Sales Media, Admin" required style="padding: 10px; border-radius: 6px;">
                        <small style="font-size: 0.72rem; color: var(--text-muted); display: block; margin-top: 4px;">Nama divisi yang akan muncul pada dropdown pilihan pendaftaran PKL.</small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; border-radius: 6px;">
                        + Tambah Divisi
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Divisions List Table -->
        <div class="dash-card">
            <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">Daftar Divisi Magang</h3>
                <span class="badge" style="background: var(--primary); color: #fff; font-size: 0.72rem; padding: 3px 10px; border-radius: 12px;">{{ $divisions->count() }} Divisi</span>
            </div>
            <div class="dash-card-body" style="padding: 0;">
                @if($divisions->count() > 0)
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="padding: 12px 16px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; width: 50px;">No</th>
                                <th style="padding: 12px 16px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Nama Divisi</th>
                                <th style="padding: 12px 16px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Status</th>
                                <th style="padding: 12px 16px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($divisions as $index => $div)
                            <tr>
                                <td style="padding: 14px 16px; font-weight: 700; font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $index + 1 }}
                                </td>

                                <td style="padding: 14px 16px;">
                                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">{{ $div->name }}</div>
                                </td>

                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($div->is_active)
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                                        ● Aktif
                                    </span>
                                    @else
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; font-weight: 700;">
                                        ● Non-aktif
                                    </span>
                                    @endif
                                </td>

                                <td style="padding: 14px 16px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.pkl.divisions.toggle', $div) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 4px; font-weight: 700; background: {{ $div->is_active ? 'var(--warning-light, #fef3c7)' : 'var(--success-light, #d1fae5)' }}; color: {{ $div->is_active ? '#b45309' : '#047857' }}; border: 1px solid {{ $div->is_active ? '#fde68a' : '#a7f3d0' }};">
                                                {{ $div->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.pkl.divisions.destroy', $div) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi magang {{ $div->name }}?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 4px; font-weight: 700; background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2);">
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
                @else
                <div style="padding: 30px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                    Belum ada divisi magang yang ditambahkan. Silakan tambah divisi pertama menggunakan form di sebelah kiri.
                </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
