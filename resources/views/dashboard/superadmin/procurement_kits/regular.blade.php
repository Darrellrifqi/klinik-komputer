@extends('layouts.dashboard')
@section('title', 'Daftar Member Mandiri')
@section('page_title', 'Daftar Member Mandiri (Umum)')
@section('page_subtitle', 'Master data member umum/mandiri yang melakukan pendaftaran melalui website.')

@section('sidebar_nav')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.superadmin.sidebar')
    @elseif(auth()->user()->isCs())
        @include('dashboard.cs.sidebar')
    @endif
@endsection

@section('content')
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
        <form action="{{ auth()->user()->isCs() ? route('cs.regular-members') : route('admin.regular-members') }}" method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 400px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari NIK, nama member..." style="padding: 8px 12px; font-size: 0.85rem;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.85rem; font-weight: 700;">Cari</button>
            @if($search)
                <a href="{{ auth()->user()->isCs() ? route('cs.regular-members') : route('admin.regular-members') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">Reset</a>
            @endif
        </form>
    </div>

    <div class="dash-card" style="margin: 0;">
        <div class="dash-card-header">
            <h3 style="margin:0; font-size: 1.05rem; font-weight: 700; color: var(--accent);">Daftar Member Mandiri ({{ $kits->total() }})</h3>
        </div>

        <div class="dash-card-body" style="padding: 0;">
            <div class="table-wrap">
                <table class="dash-table" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK (ID Member)</th>
                            <th>Nama Pemilik</th>
                            <th>WhatsApp</th>
                            <th>Email</th>
                            <th>Status Aktivasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kits as $index => $kit)
                        <tr>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $kits->firstItem() + $index }}</td>
                            <td>
                                <strong style="font-family: monospace; font-size: 0.88rem; color: var(--accent);">{{ $kit->member_id }}</strong>
                            </td>
                            <td style="font-size: 0.88rem; font-weight: 700; color: var(--text-primary);">
                                {{ $kit->student_name ?? '-' }}
                            </td>
                            <td style="font-size: 0.85rem;">{{ $kit->customer->phone ?? '-' }}</td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $kit->customer->email ?? '-' }}</td>
                            <td>
                                @if($kit->customer && $kit->customer->status === 'active')
                                    <span class="badge badge-success" style="font-size: 0.7rem; padding: 3px 8px;">Aktif</span>
                                @elseif($kit->customer && $kit->customer->status === 'pending')
                                    <span class="badge badge-warning" style="font-size: 0.7rem; padding: 3px 8px;">Pending</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 0.7rem; padding: 3px 8px;">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.procurement-kits.destroy', $kit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member mandiri {{ $kit->student_name }}?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size: 0.7rem; padding: 4px 8px; font-weight: 700;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada data member mandiri terdaftar.
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
@endsection
