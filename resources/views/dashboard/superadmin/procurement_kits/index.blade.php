@extends('layouts.dashboard')
@section('title', 'Daftar Member Pengadaan')
@section('page_title', 'Daftar Member Pengadaan (Sekolah)')
@section('page_subtitle', 'Master data serial number unit pengadaan sekolah resmi yang terdaftar untuk aktivasi membership siswa.')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div class="procurement-kits-grid" style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 24px; align-items: flex-start; min-width: 0;">
    
    {{-- Main Table Section --}}
    <div>
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom: 20px;">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Search and Action Row --}}
        <div style="display: flex; gap: 12px; margin-bottom: 16px; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.procurement-kits') }}" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari SN, nama siswa, instansi..." style="padding: 8px 12px; font-size: 0.85rem;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 700;">Cari</button>
                @if($search)
                    <a href="{{ route('admin.procurement-kits') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">Reset</a>
                @endif
            </form>

            @if($kits->total() > 0)
                <form action="{{ route('admin.procurement-kits.delete_all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SELURUH data Serial Number Unit Pengadaan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error" style="font-weight: 700; padding: 9px 18px; font-size: 0.85rem;">
                        Hapus Semua SN
                    </button>
                </form>
            @endif
        </div>

        <div class="dash-card" style="margin: 0;">
            <div class="dash-card-header">
                <h3 style="margin:0; font-size: 1.05rem; font-weight: 700; color: var(--primary);">Daftar Serial Number Unit ({{ $kits->total() }})</h3>
            </div>

            <div class="dash-card-body" style="padding: 0;">
                <div class="table-wrap">
                    <table class="dash-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Serial Number (ID Member)</th>
                                <th>Nama Pemilik</th>
                                <th>Instansi / Sekolah</th>
                                <th>Model Unit</th>
                                <th>Status Aktivasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kits as $index => $kit)
                            <tr>
                                <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $kits->firstItem() + $index }}</td>
                                <td>
                                    <strong style="font-family: monospace; font-size: 0.88rem; color: var(--primary);">{{ $kit->member_id }}</strong>
                                </td>
                                <td style="font-size: 0.88rem; font-weight: 700; color: var(--text-primary);">
                                    {{ $kit->student_name ?? '-' }}
                                </td>
                                <td style="font-size: 0.85rem;">{{ $kit->institution ?? '-' }}</td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $kit->unit_model ?? '-' }}</td>
                                <td>
                                    @if($kit->status === 'activated')
                                        <span class="badge badge-success" style="font-size: 0.7rem; padding: 3px 8px;">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 0.7rem; padding: 3px 8px;">Belum Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.procurement-kits.destroy', $kit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Serial Number {{ $kit->member_id }}?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="font-size: 0.7rem; padding: 4px 8px; font-weight: 700;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    Belum ada data Serial Number Unit Pengadaan terdaftar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($kits->hasPages())
                <div style="padding: 16px;">
                    {{ $kits->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar Form Section --}}
    <div>
        {{-- Excel Import Card --}}
        <div class="dash-card" style="margin-bottom: 20px; border-left: 4px solid var(--success);">
            <div class="dash-card-header">
                <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--success); white-space: nowrap;">Import Excel SN Pengadaan</h3>
            </div>
            <div class="dash-card-body">
                <div style="margin-bottom: 16px;">
                    <a href="{{ route('admin.procurement-kits.template') }}" class="btn-dl-template" style="width: 100%;">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Download Template Excel (.csv)
                    </a>
                </div>

                <form action="{{ route('admin.procurement-kits.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Upload File Excel/CSV Yang Sudah Diisi</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv" style="padding: 8px 12px; font-size: 0.85rem;">
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                            Format file didukung: .xlsx, .xls, .csv
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">Petunjuk Kolom Excel</div>
                <a href="{{ route('admin.procurement-kits.template') }}" class="btn-dl-template" style="font-size: 0.7rem; padding: 3px 8px;">
                    Download Template
                </a>
            </div>
            <div style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
                Struktur kolom file Excel:
                <ul style="margin: 4px 0 0 0; padding-left: 20px;">
                    <li>Kolom A: Nomor urut (#)</li>
                    <li>Kolom B: Nama Lengkap Siswa</li>
                    <li>Kolom C: Instansi / Sekolah</li>
                    <li>Kolom D: Tahun Pengadaan</li>
                    <li>Kolom E: Model Unit (Laptop)</li>
                    <li>Kolom F: Unit Serial Number (Wajib)</li>
                    <li>Kolom G: Valid Until (Opsional)</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<style>
.btn-dl-template {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 14px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--primary);
    background: #ffffff;
    border: 1.5px solid var(--primary);
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: all 0.2s ease-in-out;
}
.btn-dl-template:hover {
    background: var(--primary) !important;
    color: #ffffff !important;
    border-color: var(--primary) !important;
    box-shadow: 0 4px 12px rgba(95, 138, 99, 0.25);
}
.btn-dl-template svg {
    stroke: currentColor;
}
@media (max-width: 1200px) {
    .procurement-kits-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
