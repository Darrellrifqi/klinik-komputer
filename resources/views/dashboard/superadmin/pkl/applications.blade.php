@extends('layouts.dashboard')
@section('title', 'Pengajuan Internship Sekolah | Superadmin')
@section('page_title', 'Pengajuan Internship Sekolah')
@section('page_subtitle', 'Kelola permohonan internship dari Sekolah Mitra ACP')

@section('sidebar_nav')
@include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="max-width: 1080px;">
    
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px; border-radius: 6px;">{{ session('success') }}</div>
    @endif

    @php $currentStatus = request('status', ''); @endphp

    <!-- Filter Tabs & Search Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
        <!-- Status Tabs -->
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <a href="{{ route('admin.internship.applications') }}" class="btn {{ $currentStatus === '' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.75rem; padding: 7px 14px; border-radius: 6px; font-weight: 700;">
                Semua
            </a>
            <a href="{{ route('admin.internship.applications', ['status' => 'pending']) }}" class="btn {{ $currentStatus === 'pending' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.75rem; padding: 7px 14px; border-radius: 6px; font-weight: 700;">
                Menunggu Persetujuan
            </a>
            <a href="{{ route('admin.internship.applications', ['status' => 'approved']) }}" class="btn {{ $currentStatus === 'approved' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.75rem; padding: 7px 14px; border-radius: 6px; font-weight: 700;">
                Disetujui
            </a>
            <a href="{{ route('admin.internship.applications', ['status' => 'rejected']) }}" class="btn {{ $currentStatus === 'rejected' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.75rem; padding: 7px 14px; border-radius: 6px; font-weight: 700;">
                Ditolak
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.internship.applications') }}" style="display: flex; gap: 6px; margin: 0;">
            @if($currentStatus)<input type="hidden" name="status" value="{{ $currentStatus }}">@endif
            <input type="text" name="search" class="form-control" placeholder="Cari nama sekolah / PJ..." value="{{ request('search') }}" style="padding: 7px 12px; font-size: 0.82rem; border-radius: 6px; width: 220px; border: 1px solid var(--border);">
            <button type="submit" class="btn btn-primary" style="font-size: 0.75rem; padding: 7px 14px; border-radius: 6px; font-weight: 700;">Cari</button>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="dash-card">
        <div class="dash-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-bottom: 1px solid var(--border-light);">
            <h3 style="margin: 0; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;">
                Daftar Pengajuan Sekolah {{ $currentStatus ? '('.ucfirst($currentStatus).')' : '' }}
            </h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">Total: <strong style="color: var(--primary);">{{ $applications->total() }}</strong> pengajuan</span>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($applications->count() > 0)
            <div class="table-wrap" style="border: none; border-radius: 0; margin: 0; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 850px;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-light); background: var(--bg-alt);">
                            <th style="text-align: left; padding: 12px 18px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Sekolah & Pembimbing</th>
                            <th style="text-align: center; padding: 12px 14px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); width: 100px;">Kuota</th>
                            <th style="text-align: center; padding: 12px 14px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); width: 160px;">Estimasi Periode</th>
                            <th style="text-align: center; padding: 12px 14px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); width: 130px;">Proposal</th>
                            <th style="text-align: center; padding: 12px 14px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); width: 110px;">Status</th>
                            <th style="text-align: center; padding: 12px 18px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); width: 190px; white-space: nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <!-- 1. Sekolah & Contact -->
                            <td style="padding: 16px 18px; vertical-align: middle;">
                                <div style="font-weight: 800; font-size: 0.92rem; color: var(--text-primary); line-height: 1.3;">
                                    {{ $app->school_name }}
                                </div>
                                <div style="font-size: 0.82rem; color: var(--primary); margin-top: 3px; font-weight: 700;">
                                    PJ: {{ $app->contact_person }}
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 3px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <a href="https://wa.me/62{{ ltrim($app->phone, '0') }}?text=Halo%20{{ urlencode($app->contact_person) }},%20kami%20dari%20Klinik%20Komputer%20mengenai%20pengajuan%20internship%20{{ urlencode($app->school_name) }}" 
                                       target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: none;">{{ $app->phone }}</a>
                                    @if($app->email) <span>&bull; {{ $app->email }}</span> @endif
                                </div>
                                @if($app->notes)
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 6px; background: rgba(95, 138, 99, 0.06); padding: 6px 10px; border-radius: 6px; border-left: 3px solid var(--primary); line-height: 1.4;">
                                    <strong>Catatan:</strong> {{ $app->notes }}
                                </div>
                                @endif
                            </td>

                            <!-- 2. Kuota -->
                            <td style="text-align: center; padding: 16px 14px; vertical-align: middle;">
                                <div style="display: inline-block; background: rgba(95, 138, 99, 0.1); color: var(--primary); padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 0.88rem; border: 1px solid rgba(95, 138, 99, 0.2);">
                                    {{ $app->student_count }} <span style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700; display: block; margin-top: -2px;">Siswa</span>
                                </div>
                            </td>

                            <!-- 3. Periode -->
                            <td style="text-align: center; padding: 16px 14px; vertical-align: middle; font-size: 0.8rem; color: var(--text-secondary); line-height: 1.4;">
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $app->start_date ? \Carbon\Carbon::parse($app->start_date)->format('d M Y') : '-' }}</div>
                                <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">s/d</div>
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $app->end_date ? \Carbon\Carbon::parse($app->end_date)->format('d M Y') : '-' }}</div>
                            </td>

                            <!-- 4. Proposal -->
                            <td style="text-align: center; padding: 16px 14px; vertical-align: middle;">
                                @if($app->proposal_file)
                                <a href="{{ asset('storage/' . $app->proposal_file) }}" target="_blank" class="btn btn-outline btn-sm" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 6px; font-weight: 600;">
                                    📄 Proposal
                                </a>
                                @else
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">Tidak ada</span>
                                @endif
                            </td>

                            <!-- 5. Status -->
                            <td style="text-align: center; padding: 16px 14px; vertical-align: middle;">
                                @if($app->status === 'pending')
                                    <span class="badge badge-warning" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 6px; font-weight: 700;">Menunggu</span>
                                @elseif($app->status === 'approved')
                                    <span class="badge badge-success" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 6px; font-weight: 700;">Disetujui</span>
                                @else
                                    <span class="badge badge-danger" style="font-size: 0.72rem; padding: 5px 10px; border-radius: 6px; font-weight: 700;">Ditolak</span>
                                @endif
                            </td>

                            <!-- 6. Aksi -->
                            <td style="text-align: center; padding: 16px 18px; vertical-align: middle; white-space: nowrap;">
                                <div style="display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap;">
                                    @if($app->status === 'pending')
                                    <form action="{{ route('admin.internship.applications.approve', $app) }}" method="POST" style="margin: 0; display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" style="font-size: 0.72rem; padding: 6px 10px; font-weight: 700; border-radius: 5px;">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.internship.applications.reject', $app) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Tolak pengajuan internship ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline" style="font-size: 0.72rem; padding: 6px 10px; font-weight: 700; color: #dc2626; border-color: #fca5a5; border-radius: 5px;">Tolak</button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.internship.applications.destroy', $app) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Hapus data pengajuan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline" style="font-size: 0.72rem; padding: 6px 10px; font-weight: 600; border-radius: 5px;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 16px 20px;">
                {{ $applications->links() }}
            </div>
            @else
            <div class="empty-state" style="padding: 40px; text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.9rem;">Belum ada pengajuan internship sekolah yang sesuai filter.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
