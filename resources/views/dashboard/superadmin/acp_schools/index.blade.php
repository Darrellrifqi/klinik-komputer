@extends('layouts.dashboard')
@section('title', 'Kelola Mitra Sekolah ACP')
@section('page_title', 'Mitra Sekolah Axioo Class Program (ACP)')
@section('page_subtitle', 'Master data sekolah/instansi mitra ACP yang berhak mengajukan program Internship di Klinik Komputer.')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 24px; align-items: flex-start;">
    
    {{-- Main Table Section --}}
    <div>
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Search and Action Row --}}
        <div style="display: flex; gap: 12px; margin-bottom: 16px; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.acp.schools') }}" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari nama sekolah, kota, npsn..." style="padding: 8px 12px; font-size: 0.85rem;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 700;">Cari</button>
                @if($search)
                    <a href="{{ route('admin.acp.schools') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">Reset</a>
                @endif
            </form>

            @if($schools->total() > 0)
                <form action="{{ route('admin.acp.schools.delete_all') }}" method="POST" onsubmit="return confirm('PERINGATAN KRITIS: Apakah Anda yakin ingin menghapus SELURUH data sekolah mitra ACP? Tindakan ini tidak dapat dibatalkan!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error" style="font-weight: 700; padding: 9px 18px; font-size: 0.85rem;">
                        Hapus Semua Data Sekolah
                    </button>
                </form>
            @endif
        </div>

        <div class="dash-card">
            <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin:0; font-size: 1.05rem; font-weight: 700; color: var(--primary);">Daftar Sekolah Mitra ACP ({{ $schools->total() }})</h3>
                <span class="badge badge-success" style="padding: 4px 10px; font-size: 0.75rem;">Verified ACP Partner List</span>
            </div>

            <div class="dash-card-body" style="padding: 0;">
                <div class="table-wrap">
                    <table class="dash-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sekolah / Instansi</th>
                                <th>NPSN</th>
                                <th>Kota / Kabupaten</th>
                                <th>Provinsi</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $index => $school)
                            <tr>
                                <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $schools->firstItem() + $index }}</td>
                                <td>
                                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">{{ $school->name }}</div>
                                </td>
                                <td style="font-family: monospace; font-size: 0.82rem; color: var(--primary); font-weight: 600;">
                                    {{ $school->npsn ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem;">{{ $school->city ?? '-' }}</td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $school->province ?? '-' }}</td>
                                <td style="text-align: right;">
                                    <form action="{{ route('admin.acp.schools.destroy', $school) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sekolah {{ $school->name }} dari daftar mitra ACP?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    Belum ada data sekolah mitra ACP. Gunakan form di sebelah kanan untuk menambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($schools->hasPages())
                <div style="padding: 16px;">
                    {{ $schools->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar Form Section --}}
    <div>
        <div class="dash-card" style="margin-bottom: 20px; border-left: 4px solid var(--primary);">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--primary);">+ Tambah Sekolah ACP Baru</h3>
            </div>
            <div class="dash-card-body">
                <form action="{{ route('admin.acp.schools.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Nama Sekolah / Instansi <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: SMK Negeri 1 Bandung" style="padding: 8px 12px; font-size: 0.85rem;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">NPSN Sekolah (Opsional)</label>
                        <input type="text" name="npsn" class="form-control" placeholder="Contoh: 20219804" style="padding: 8px 12px; font-size: 0.85rem; font-family: monospace;">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Kota / Kabupaten (Opsional)</label>
                        <input type="text" name="city" class="form-control" placeholder="Contoh: Bandung" style="padding: 8px 12px; font-size: 0.85rem;">
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Provinsi (Opsional)</label>
                        <input type="text" name="province" class="form-control" placeholder="Contoh: Jawa Barat" style="padding: 8px 12px; font-size: 0.85rem;">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="font-size: 0.88rem; font-weight: 700; padding: 10px;">
                        Simpan Sekolah ACP
                    </button>
                </form>
            </div>
        </div>

        {{-- Excel Import Card --}}
        <div class="dash-card" style="margin-bottom: 20px; border-left: 4px solid var(--success);">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--success);">Import Excel Sekolah ACP</h3>
            </div>
            <div class="dash-card-body">
                <form action="{{ route('admin.acp.schools.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Pilih File Excel/CSV</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv" style="padding: 8px 12px; font-size: 0.85rem;">
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                            Format file: .xlsx, .xls, .csv. Pastikan kolom pertama berisi nama sekolah/institusi.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block" style="font-size: 0.88rem; font-weight: 700; padding: 10px;">
                        Import Excel
                    </button>
                </form>
            </div>
        </div>

        {{-- Excel Info Card --}}
        <div class="card" style="padding: 18px; border: 1px dashed var(--primary); background: rgba(95, 138, 99, 0.05); border-radius: 10px;">
            <div style="font-size: 0.85rem; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Petunjuk Kolom Excel</div>
            <div style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
                Struktur kolom file Excel:
                <ul style="margin: 4px 0 0 0; padding-left: 20px;">
                    <li>Kolom A: Nama Institusi (Wajib)</li>
                    <li>Kolom B: NPSN (Opsional)</li>
                    <li>Kolom C: Kota (Opsional)</li>
                    <li>Kolom D: Provinsi (Opsional)</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
