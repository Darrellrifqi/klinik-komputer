@extends('layouts.dashboard')
@section('title', 'Tinjau Pendaftaran Siswa PKL | Superadmin')
@section('page_title', 'Tinjau Pendaftaran Siswa PKL')
@section('page_subtitle', 'Review dan setujui pendaftaran mandiri siswa PKL & Magang')

@section('sidebar_nav')
@include('dashboard.superadmin.sidebar')
@endsection

@section('content')
<div style="max-width: 1000px;">
    
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <!-- Status Tabs Filter & Search -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
        <!-- Status Tabs -->
        <div style="display: flex; gap: 8px;">
            @php $currentStatus = request('status', 'pending'); @endphp
            <a href="{{ route('admin.pkl.students', ['status' => 'pending']) }}" class="btn {{ $currentStatus === 'pending' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.72rem; padding: 6px 14px; border-radius: 4px; font-weight: 700;">
                Menunggu Persetujuan
            </a>
            <a href="{{ route('admin.pkl.students', ['status' => 'approved']) }}" class="btn {{ $currentStatus === 'approved' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.72rem; padding: 6px 14px; border-radius: 4px; font-weight: 700;">
                Disetujui
            </a>
            <a href="{{ route('admin.pkl.students', ['status' => 'rejected']) }}" class="btn {{ $currentStatus === 'rejected' ? 'btn-primary' : 'btn-outline' }}" style="font-size: 0.72rem; padding: 6px 14px; border-radius: 4px; font-weight: 700;">
                Ditolak
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" style="display: flex; gap: 8px; margin: 0;">
            <input type="hidden" name="status" value="{{ $currentStatus }}">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, sekolah, divisi..." value="{{ request('search') }}" style="padding: 8px 12px; font-size: 0.82rem; border-radius: 4px; width: 220px; border: 1px solid var(--border);">
            <button type="submit" class="btn btn-primary" style="font-size: 0.72rem; padding: 8px 14px; border-radius: 4px;">Cari</button>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="dash-card">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 0;">
            <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; text-transform: uppercase;">
                Daftar Pendaftar (Status: {{ ucfirst($currentStatus) }})
            </h3>
        </div>
        <div class="dash-card-body" style="padding: 0;">
            @if($students->count() > 0)
            <div class="table-wrap" style="border: none; border-radius: 0; margin: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-light); background: var(--bg-alt);">
                            <th style="text-align: left; padding: 14px 16px; font-size: 0.8rem; width: 250px;">Siswa & Sekolah</th>
                            <th style="text-align: left; padding: 14px 16px; font-size: 0.8rem; width: 160px;">Divisi Magang</th>
                            <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem; width: 130px;">Token/Kuartal</th>
                            <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem; width: 160px;">Tanggal PKL</th>
                            <th style="text-align: center; padding: 14px 16px; font-size: 0.8rem; width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <!-- Student Biodata -->
                            <td style="padding: 14px 16px; vertical-align: middle;">
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <div style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; border: 2px solid var(--primary); flex-shrink: 0; background: var(--bg-alt);">
                                        <a href="{{ $student->photo_url }}" target="_blank">
                                            <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-primary);">{{ $student->name }}</div>
                                        <div style="font-size: 0.76rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-top: 2px;">{{ $student->school }}</div>
                                        @if($student->testimonial)
                                        <div style="font-size: 0.72rem; color: var(--text-secondary); font-style: italic; margin-top: 2px;">"{{ $student->testimonial }}"</div>
                                        @endif
                                        <div style="font-size: 0.68rem; color: var(--text-muted); margin-top: 4px;">Pendaftaran: {{ $student->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Division -->
                            <td style="padding: 14px 16px; vertical-align: middle; font-size: 0.85rem; font-weight: 700; color: var(--text-primary);">
                                <span style="background: rgba(95, 138, 99, 0.06); padding: 5px 10px; border-radius: 4px; border: 1px solid rgba(95, 138, 99, 0.15);">
                                    {{ $student->division }}
                                </span>
                            </td>

                            <!-- Token / Quarter -->
                            <td style="padding: 14px 16px; text-align: center; vertical-align: middle;">
                                <div style="font-family: monospace; font-weight: 700; font-size: 0.82rem; color: var(--text-primary);">{{ $student->period->token }}</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">{{ $student->period->quarter }} {{ $student->period->year }}</div>
                            </td>

                            <!-- Dates -->
                            <td style="padding: 14px 16px; text-align: center; vertical-align: middle; font-size: 0.8rem; font-family: monospace; line-height: 1.45; color: var(--text-secondary);">
                                {{ $student->start_date->format('d M Y') }}<br>s/d<br>{{ $student->end_date->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td style="padding: 14px 16px; text-align: center; vertical-align: middle;">
                                @if($student->status === 'pending')
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <!-- Approve -->
                                    <form action="{{ route('admin.pkl.students.approve', $student) }}" method="POST" style="margin: 0; display: inline;">
                                        @csrf
                                        <button type="submit" class="btn" style="font-size: 0.68rem; padding: 6px 12px; border-radius: 4px; font-weight: 700; background: var(--success); color: #fff; border: none; text-transform: uppercase;">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Reject -->
                                    <form action="{{ route('admin.pkl.students.reject', $student) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran siswa ini?')">
                                        @csrf
                                        <button type="submit" class="btn" style="font-size: 0.68rem; padding: 6px 12px; border-radius: 4px; font-weight: 700; background: var(--danger); color: #fff; border: none; text-transform: uppercase;">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                                @else
                                <!-- Delete Record -->
                                <form action="{{ route('admin.pkl.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa PKL ini secara permanen dari sistem?')" style="margin: 0; display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="font-size: 0.68rem; padding: 6px 12px; border-radius: 4px; font-weight: 700; background: var(--danger); color: #fff; border: none; display: flex; align-items: center; justify-content: center; gap: 4px; margin: 0 auto;">
                                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 16px;">
                {{ $students->links() }}
            </div>
            @else
            <div class="empty-state" style="padding: 50px 20px; text-align: center;">
                <svg viewBox="0 0 24 24" width="36" height="36" stroke="var(--text-muted)" stroke-width="2" fill="none" style="margin-bottom: 8px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Tidak ada pendaftar siswa PKL dengan status ini.</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
