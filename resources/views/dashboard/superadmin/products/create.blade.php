@extends('layouts.dashboard')
@section('title', 'Tambah Produk — Super Admin')
@section('page_title', 'Tambah Produk Baru')
@section('page_subtitle', 'Tambahkan laptop Axioo ke katalog')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection


@section('content')
<div style="max-width:800px;">
    <a href="{{ route('admin.products') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px;">Kembali</a>

    <div class="dash-card">
        <div class="dash-card-header"><h3>Form Tambah Produk</h3></div>
        <div class="dash-card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('dashboard.superadmin.products._form')
                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <a href="{{ route('admin.products') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
