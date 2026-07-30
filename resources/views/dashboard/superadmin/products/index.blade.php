@extends('layouts.dashboard')
@section('title', 'Kelola Produk | Super Admin')
@section('page_title', 'Kelola Produk')
@section('page_subtitle', 'Manajemen katalog laptop Axioo')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('topbar_actions')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Tambah Produk
</a>
@endsection

@section('content')
<div class="dash-card">
    <div class="dash-card-header" style="display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; border-bottom: 1px solid var(--border-light);">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--text-primary);">Katalog Produk Axioo</h3>
            <span style="font-size: 0.78rem; color: var(--text-muted);">Total {{ $products->total() }} produk terdaftar</span>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Produk Baru
        </a>
    </div>
    <div class="dash-card-body" style="padding: 0;">
        @if($products->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Seri</th>
                        <th>Spesifikasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:44px; height:44px; background:var(--bg-alt); border:1px solid var(--border-light); border-radius:8px; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0;">
                                    @if(count($product->images) > 0)
                                        <img src="{{ asset('storage/'.$product->images[0]) }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">IMG</span>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:700; font-size:0.88rem; color: var(--text-primary);">{{ $product->name }}</div>
                                    <div style="font-size:0.72rem; color:var(--text-muted); display:flex; align-items:center; gap:6px; margin-top:2px;">
                                        <span>ID: #{{ $product->id }}</span>
                                        @if($product->tokopedia_url)
                                            <span style="color: #16a34a; font-weight: 700; background: rgba(22,163,74,0.08); padding: 1px 6px; border-radius: 4px;">✓ Online Shop</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $product->series === 'pongo' ? 'badge-accent' : 'badge-primary' }}" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">
                                {{ $product->series_label }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size:0.8rem; color:var(--text-secondary); line-height:1.4;">
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $product->processor }}</div>
                                <div style="color: var(--text-muted); font-size: 0.75rem;">{{ $product->ram }} &bull; {{ $product->storage }}</div>
                            </div>
                        </td>
                        <td style="font-weight:700; color:var(--primary); font-size: 0.9rem;">{{ $product->formatted_price }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success" style="font-size: 0.7rem;">Aktif</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.7rem;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display:flex; gap:6px; justify-content: flex-end;">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm" style="padding: 5px 10px; font-size: 0.75rem; font-weight: 600;">Edit</a>
                                <form action="{{ route('admin.products.toggle', $product) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-dark" style="padding: 5px 10px; font-size: 0.75rem; font-weight: 600;">
                                        {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 5px 10px; font-size: 0.75rem; font-weight: 600;" onclick="return confirm('Hapus produk {{ $product->name }}?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 24px;">{{ $products->links() }}</div>
        @else
        <div style="text-align: center; padding: 60px 24px; background: var(--bg-card); border-radius: var(--radius-md);">
            <div style="width: 64px; height: 64px; background: var(--bg-alt); border: 1px solid var(--border-light); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; color: var(--text-muted);">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">Belum Ada Produk Terdaftar</h3>
            <p style="color: var(--text-secondary); font-size: 0.88rem; max-width: 440px; margin: 0 auto 20px auto; line-height: 1.5;">Katalog produk laptop Axioo saat ini masih kosong. Silakan tambahkan produk baru untuk ditampilkan di katalog pengunjung.</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 10px 20px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Produk Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
