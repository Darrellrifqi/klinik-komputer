@extends('layouts.dashboard')
@section('title', 'Kelola User | Super Admin')
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
<!-- Filter & Action Bar -->
<div class="dash-card" style="margin-bottom:16px;">
    <div class="dash-card-body" style="padding:12px 16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select name="role" class="form-control" style="width:auto; padding: 6px 12px; font-size:0.8rem;">
                <option value="">Semua Peran</option>
                <option value="customer" {{ request('role')==='customer' ? 'selected':'' }}>Customer</option>
                <option value="cs"       {{ request('role')==='cs'       ? 'selected':'' }}>CS</option>
                <option value="teknisi"  {{ request('role')==='teknisi'  ? 'selected':'' }}>Teknisi</option>
                <option value="produksi" {{ request('role')==='produksi' ? 'selected':'' }}>Produksi</option>
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

        @if(auth()->user()->isSuperAdmin())
        <button type="button" onclick="toggleCreateAccForm()" class="btn btn-success btn-sm" style="font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Buat Akun Baru
        </button>
        @endif
    </div>
</div>

{{-- Create Account Card (Toggleable) --}}
@if(auth()->user()->isSuperAdmin())
<div class="dash-card" id="createAccCard" style="display: {{ $errors->any() ? 'block' : 'none' }}; margin-bottom: 20px; border-left: 4px solid var(--success);">
    <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center; background: rgba(95, 138, 99, 0.05); padding: 12px 18px;">
        <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--primary);">Form Buat Akun Pengguna Baru</h3>
        <button type="button" onclick="toggleCreateAccForm()" style="background: none; border: none; font-size: 1.2rem; color: var(--text-muted); cursor: pointer; padding: 0 4px;">&times;</button>
    </div>
    <div class="dash-card-body" style="padding: 20px;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 16px;">
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Nama Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama lengkap user" required style="font-size: 0.85rem; padding: 8px 12px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Alamat Email <span style="color:red;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@domain.com" required style="font-size: 0.85rem; padding: 8px 12px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">No. HP / WhatsApp <span style="color:red;">*</span></label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="081234567890" required style="font-size: 0.85rem; padding: 8px 12px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Role / Peran <span style="color:red;">*</span></label>
                    <select name="role" class="form-control" required style="font-size: 0.85rem; padding: 8px 12px;">
                        <option value="customer" {{ old('role')==='customer'?'selected':'' }}>Customer</option>
                        <option value="cs" {{ old('role')==='cs'?'selected':'' }}>CS (Staff CS)</option>
                        <option value="teknisi" {{ old('role')==='teknisi'?'selected':'' }}>Teknisi</option>
                        <option value="produksi" {{ old('role')==='produksi'?'selected':'' }}>Produksi (Gudang)</option>
                        <option value="superadmin" {{ old('role')==='superadmin'?'selected':'' }}>Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Password <span style="color:red;">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required style="font-size: 0.85rem; padding: 8px 12px;">
                </div>
                <div>
                    <label class="form-label" style="font-size: 0.8rem; font-weight: 700;">Konfirmasi Password <span style="color:red;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password" required style="font-size: 0.85rem; padding: 8px 12px;">
                </div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="toggleCreateAccForm()" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-success btn-sm" style="font-weight: 700; padding: 8px 16px;">Simpan Akun Baru</button>
            </div>
        </form>
    </div>
</div>
@endif

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
                            @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <select name="role" onchange="if(confirm('Ubah role {{ $user->name }} menjadi ' + this.options[this.selectedIndex].text + '?')) this.form.submit(); else this.value='{{ $user->role }}';"
                                        style="font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; border: 1px solid var(--primary); background: #ffffff; color: var(--text-primary); cursor: pointer;">
                                    <option value="customer"   {{ $user->role==='customer'   ? 'selected':'' }}>Customer</option>
                                    <option value="cs"         {{ $user->role==='cs'         ? 'selected':'' }}>CS (Staff CS)</option>
                                    <option value="teknisi"    {{ $user->role==='teknisi'    ? 'selected':'' }}>Teknisi</option>
                                    <option value="produksi"   {{ $user->role==='produksi'   ? 'selected':'' }}>Produksi (Gudang)</option>
                                    <option value="superadmin" {{ $user->role==='superadmin' ? 'selected':'' }}>Super Admin</option>
                                </select>
                            </form>
                            @else
                                @php $roleColors = ['customer'=>'primary','cs'=>'info','teknisi'=>'accent','produksi'=>'secondary','superadmin'=>'danger']; @endphp
                                <span class="badge badge-{{ $roleColors[$user->role] ?? 'secondary' }}">{{ ucfirst($user->role) }}</span>
                            @endif
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

@push('scripts')
<script>
function toggleCreateAccForm() {
    const card = document.getElementById('createAccCard');
    if (card) {
        card.style.display = (card.style.display === 'none' || card.style.display === '') ? 'block' : 'none';
        if (card.style.display === 'block') {
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}
</script>
@endpush
