@extends('layouts.dashboard')
@section('title', 'Edit Produk — Super Admin')
@section('page_title', 'Edit Produk')
@section('page_subtitle', $product->name)

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection


@section('content')
<div style="max-width:800px;">
    <a href="{{ route('admin.products') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px;">Kembali</a>

    <div class="dash-card">
        <div class="dash-card-header">
            <h3>Edit: {{ $product->name }}</h3>
            <span class="badge {{ $product->series === 'pongo' ? 'badge-accent' : 'badge-primary' }}">{{ $product->series_label }}</span>
        </div>
        <div class="dash-card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('dashboard.superadmin.products._form')
                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <a href="{{ route('admin.products') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
