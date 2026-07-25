@extends('layouts.dashboard')
@section('title', 'Kelola User — Super Admin')
@section('page_title', 'Kelola User')
@section('page_subtitle', 'Manage akun customer, CS, dan teknisi')

@section('sidebar_nav')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.superadmin.sidebar')
    @elseif(auth()->user()->isCs())
        @include('dashboard.cs.sidebar')
    @endif
@endsection


@section('content')
<!-- Filter -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-body" style="padding:12px 16px;">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select name="role" class="form-control" style="width:auto; padding: 6px 12px; font-size:0.8rem;">
                <option value="">Semua Peran</option>
                <option value="customer" {{ request('role')==='customer' ? 'selected':'' }}>Customer</option>
                <option value="cs"       {{ request('role')==='cs'       ? 'selected':'' }}>CS</option>
                <option value="teknisi"  {{ request('role')==='teknisi'  ? 'selected':'' }}>Teknisi</option>
            </select>
            <select name="status" class="form-control" style="width:auto; padding: 6px 12px; font-size:0.8rem;">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')==='active'  ? 'selected':'' }}>Aktif</option>
                <option value="pending"  {{ request('status')==='pending' ? 'selected':'' }}>Menunggu</option>
                <option value="rejected" {{ request('status')==='rejected'? 'selected':'' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ auth()->user()->isCs() ? route('cs.users') : route('admin.users') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header">
        <h3>Daftar Pengguna ({{ $users->total() }})</h3>
    </div>
    <div class="dash-card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Peran</th>
                        <th>WhatsApp</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div class="user-avatar" style="width:30px; height:30px; font-size:0.75rem;">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:600; font-size:0.85rem;">{{ $user->name }}</div>
                                    <div style="font-size:0.72rem; color:var(--text-muted);">{{ $user->email }}</div>
                                    @if($user->nik)
                                        <div style="font-size:0.72rem; color:var(--primary); font-weight:700; font-family:monospace; margin-top: 2px;">NIK: {{ $user->nik }}</div>
                                    @endif
                                    @php $kit = $user->laptopKits->first(); @endphp
                                    @if($kit)
                                        <div style="font-size:0.72rem; color:var(--text-secondary); margin-top: 2px;">
                                            Unit: {{ $kit->unit_model ?? $kit->axioo_serial_number }}
                                        </div>
                                        @if($kit->is_regular && $kit->proof_of_purchase)
                                            <div style="margin-top: 4px;">
                                                <a href="{{ $kit->proof_of_purchase }}" target="_blank" class="badge badge-accent" style="font-size: 0.65rem; padding: 2px 6px; text-decoration: none;">Lihat Nota Pembelian</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php $roleColors = ['customer'=>'primary','cs'=>'info','teknisi'=>'accent']; @endphp
                            <span class="badge badge-{{ $roleColors[$user->role] ?? 'secondary' }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td style="font-size:0.82rem; color:var(--text-secondary);">{{ $user->phone ?? '—' }}</td>
                        <td>
                            @if($user->status === 'active')   <span class="badge badge-success">Aktif</span>
                            @elseif($user->status === 'pending') <span class="badge badge-warning">Menunggu</span>
                            @else <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                        <td style="font-size:0.78rem; color:var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @php
                                $isCs = auth()->user()->isCs();
                                $approveRoute = $isCs ? route('cs.users.approve', $user) : route('admin.users.approve', $user);
                                $rejectRoute = $isCs ? route('cs.users.reject', $user) : route('admin.users.reject', $user);
                            @endphp
                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                @if($user->status === 'pending')
                                <form action="{{ $approveRoute }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="font-size:0.7rem; padding:4px 8px;">Setujui</button>
                                </form>
                                <form action="{{ $rejectRoute }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size:0.7rem; padding:4px 8px;" onclick="return confirm('Tolak pendaftaran akun ini?')">Tolak</button>
                                </form>
                                @elseif($user->status === 'active' && in_array($user->role,['cs','teknisi','customer']))
                                <form action="{{ $rejectRoute }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" style="font-size:0.7rem; padding:4px 8px;" onclick="return confirm('Nonaktifkan/Tolak akun ini?')">Nonaktifkan</button>
                                </form>
                                @elseif($user->status === 'rejected')
                                <form action="{{ $approveRoute }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" style="font-size:0.7rem; padding:4px 8px;">Aktifkan</button>
                                </form>
                                @endif
                                
                                @if(!$isCs)
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size:0.7rem; padding:4px 8px;" onclick="return confirm('Hapus permanen akun {{ $user->name }}?')">Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><p>Tidak ditemukan data pengguna.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;">{{ $users->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
