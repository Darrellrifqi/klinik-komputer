@extends('layouts.dashboard')
@section('title', 'Kelola Cover — Super Admin')
@section('page_title', 'Kelola Cover')
@section('page_subtitle', 'Manajemen slide cover latar belakang dan teks di halaman beranda')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('topbar_actions')
<a href="{{ route('admin.hero.create') }}" class="btn btn-primary btn-sm">Tambah Slide</a>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="dash-card">
    <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Daftar Slide Cover</h3>
        <a href="{{ route('admin.hero.create') }}" class="btn btn-primary btn-sm">Tambah Slide Baru</a>
    </div>
    
    <div class="dash-card-body" style="padding: 0;">
        @if($slides->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">Order</th>
                        <th style="width: 120px;">Preview</th>
                        <th>Tag & Judul</th>
                        <th>Deskripsi</th>
                        <th>Link Tombol (CTA)</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 150px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slides as $slide)
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: var(--text-secondary);">
                            {{ $slide->order_index }}
                        </td>
                        <td>
                            <img src="{{ $slide->image_url }}" alt="Preview" 
                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        </td>
                        <td>
                            @if($slide->tag)
                                <span class="badge badge-primary" style="font-size: 0.6rem; padding: 2px 6px; margin-bottom: 4px; display: inline-block;">
                                    {{ $slide->tag }}
                                </span>
                            @endif
                            <div style="font-weight: 700; color: var(--text-primary);">{{ $slide->title ?? '—' }}</div>
                        </td>
                        <td style="font-size: 0.8rem; color: var(--text-secondary); max-width: 250px; white-space: normal; line-height: 1.4;">
                            {{ Str::limit($slide->description, 100) }}
                        </td>
                        <td style="font-size: 0.78rem; line-height: 1.4;">
                            @if($slide->cta_text_primary)
                                <div><strong style="color: var(--primary);">Utama:</strong> {{ $slide->cta_text_primary }} ({{ $slide->cta_url_primary }})</div>
                            @endif
                            @if($slide->cta_text_secondary)
                                <div><strong style="color: var(--text-muted);">Kedua:</strong> {{ $slide->cta_text_secondary }} ({{ $slide->cta_url_secondary }})</div>
                            @endif
                            @if(!$slide->cta_text_primary && !$slide->cta_text_secondary)
                                <span style="color: var(--text-muted);">Tidak ada tombol</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($slide->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('admin.hero.edit', $slide) }}" class="btn btn-outline btn-sm" style="padding: 4px 10px; font-size: 0.7rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.hero.destroy', $slide) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini? Gambar akan dihapus permanen.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.7rem;">
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
        <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
            <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 6px;">Belum ada slide cover custom</div>
            <p style="font-size: 0.82rem; max-width: 450px; margin: 0 auto 20px;">Beranda saat ini menampilkan 3 slide bawaan (fallback). Klik tombol di bawah untuk menambahkan slide kustom dengan gambar pilihan Anda!</p>
            <a href="{{ route('admin.hero.create') }}" class="btn btn-primary btn-sm">Tambah Slide Pertama</a>
        </div>
        @endif
    </div>
</div>
@endsection
