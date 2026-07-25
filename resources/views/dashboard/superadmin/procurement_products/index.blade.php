@extends('layouts.dashboard')
@section('title', 'Excel Unit Pengadaan — Super Admin')
@section('page_title', 'Master Unit Pengadaan')
@section('page_subtitle', 'Kelola daftar unit laptop khusus pengadaan Retail dan TKDN.')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<style>
    /* Pagination SVG Fix */
    .pagination-wrap nav[role="navigation"] {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .pagination-wrap nav[role="navigation"] svg {
        width: 16px !important;
        height: 16px !important;
        display: inline-block !important;
        vertical-align: middle;
    }
    .pagination-wrap nav[role="navigation"] a,
    .pagination-wrap nav[role="navigation"] span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        background: #fff;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .pagination-wrap nav[role="navigation"] span[aria-current="page"] {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .btn-clear-all:hover {
        background: #b91c1c !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35) !important;
    }
    .cat-tab {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        color: var(--text-secondary);
        background: var(--bg-alt);
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .cat-tab.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(95, 138, 99, 0.25);
    }
</style>

<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Import Box --}}
    <div class="card" style="margin-bottom: 28px; background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(95,138,99,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            </div>
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-primary); margin: 0;">Upload File Excel Produk Pengadaan</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 2px 0 0 0;">Upload file Excel (.xlsx / .csv) berformat sesuai kolom: Product Name, Color, Size, CPU, RAM, STORAGE, Garansi, OS, Harga SRP, Keterangan.</p>
            </div>
        </div>

        <form action="{{ route('admin.procurement-products.import') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
            @csrf
            
            <div style="display: flex; align-items: center; gap: 8px; background: var(--bg-alt); padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-light);">
                <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-primary); margin: 0;">Kategori Target:</label>
                <select name="category" class="form-control" style="padding: 4px 8px; font-size: 0.82rem; border-radius: 6px; font-weight: 700; color: var(--primary); border: 1px solid var(--border);">
                    <option value="retail" {{ $activeCategory === 'retail' ? 'selected' : '' }}>Retail (Umum)</option>
                    <option value="tkdn" {{ $activeCategory === 'tkdn' ? 'selected' : '' }}>TKDN (Pemerintah/Sekolah)</option>
                </select>
            </div>

            <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" class="form-control" required style="flex: 1; min-width: 240px; padding: 8px 12px; border-radius: 6px;">
            <button type="submit" class="btn btn-primary" style="font-weight: 700; padding: 10px 24px; border-radius: 6px;">
                Import File Excel
            </button>
        </form>
    </div>

    {{-- Category Tabs --}}
    <div style="display: flex; gap: 12px; margin-bottom: 20px;">
        <a href="{{ route('admin.procurement-products', ['category' => 'retail']) }}" class="cat-tab {{ $activeCategory === 'retail' ? 'active' : '' }}">
            <span>Katalog RETAIL</span>
            <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 12px; background: {{ $activeCategory === 'retail' ? 'rgba(255,255,255,0.2)' : 'var(--border)' }};">
                {{ number_format($retailCount) }} Unit
            </span>
        </a>
        <a href="{{ route('admin.procurement-products', ['category' => 'tkdn']) }}" class="cat-tab {{ $activeCategory === 'tkdn' ? 'active' : '' }}">
            <span>Katalog TKDN</span>
            <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 12px; background: {{ $activeCategory === 'tkdn' ? 'rgba(255,255,255,0.2)' : 'var(--border)' }};">
                {{ number_format($tkdnCount) }} Unit
            </span>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="card" style="background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                <span>Daftar Unit Terdaftar (Kategori: <strong style="color: var(--primary);">{{ strtoupper($activeCategory) }}</strong> — Total: {{ number_format($products->total()) }} Data)</span>
            </div>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                <form action="{{ route('admin.procurement-products') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari unit, CPU, RAM..." class="form-control" style="padding: 6px 12px; font-size: 0.82rem; border-radius: 6px; width: 200px;">
                    <button type="submit" class="btn btn-outline btn-sm">Cari</button>
                </form>

                <form action="{{ route('admin.procurement-products.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data katalog {{ strtoupper($activeCategory) }}?')">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                    <button type="submit" class="btn-clear-all" style="background: #dc2626; color: #ffffff; border: none; font-weight: 700; font-size: 0.78rem; padding: 7px 16px; border-radius: 8px; display: inline-flex; align-items: center; gap: 7px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);">
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Kosongkan Katalog {{ strtoupper($activeCategory) }}
                    </button>
                </form>
            </div>
        </div>

        <div class="table-wrap" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem;">
                <thead>
                    <tr style="background: var(--bg-alt); text-align: left; border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 10px 12px;">No</th>
                        <th style="padding: 10px 12px;">Product Name</th>
                        <th style="padding: 10px 12px;">Size</th>
                        <th style="padding: 10px 12px;">CPU</th>
                        <th style="padding: 10px 12px;">RAM</th>
                        <th style="padding: 10px 12px;">Storage</th>
                        <th style="padding: 10px 12px;">OS</th>
                        <th style="padding: 10px 12px;">Harga SRP</th>
                        <th style="padding: 10px 12px;">Status</th>
                        <th style="padding: 10px 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $idx => $p)
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 10px 12px; color: var(--text-muted);">{{ $products->firstItem() + $idx }}</td>
                        <td style="padding: 10px 12px; font-weight: 700; color: var(--text-primary);">{{ $p->product_name }}</td>
                        <td style="padding: 10px 12px; color: var(--text-secondary);">{{ $p->size ?: '-' }}</td>
                        <td style="padding: 10px 12px; font-weight: 600; color: var(--primary);">{{ $p->cpu ?: '-' }}</td>
                        <td style="padding: 10px 12px;">{{ $p->ram ?: '-' }}</td>
                        <td style="padding: 10px 12px;">{{ $p->storage ?: '-' }}</td>
                        <td style="padding: 10px 12px; color: var(--text-secondary);">{{ $p->os ?: '-' }}</td>
                        <td style="padding: 10px 12px; font-weight: 700; color: var(--text-primary);">{{ $p->formatted_price }}</td>
                        <td style="padding: 10px 12px;">
                            @if(strtoupper($p->status_stock) === 'READY')
                                <span class="badge badge-success" style="font-size: 0.65rem;">READY</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.65rem;">-</span>
                            @endif
                        </td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <form action="{{ route('admin.procurement-products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus unit {{ $p->product_name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 4px;" title="Hapus">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="padding: 30px; text-align: center; color: var(--text-muted);">
                            Belum ada unit pengadaan terdaftar untuk kategori <strong>{{ strtoupper($activeCategory) }}</strong>. Silakan upload file Excel di atas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap" style="margin-top: 20px;">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
