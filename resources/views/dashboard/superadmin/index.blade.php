@extends('layouts.dashboard')
@section('title', 'Super Admin Dashboard')
@section('page_title', 'Overview Super Admin')
@section('page_subtitle', 'Monitoring statistik dan tren aktivitas sistem Klinik Komputer.')

@section('sidebar_nav')
    @include('dashboard.superadmin.sidebar')
@endsection

@section('content')
{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- 1. Stat Cards Row --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
    
    <!-- Group 1: Layanan Servis -->
    <div class="dash-card" style="padding: 20px;">
        <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 6px;">
            <span style="width: 6px; height: 6px; background: var(--primary); border-radius: 50%;"></span>
            Layanan Servis
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 4px;">
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">{{ $stats['total_tickets'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Total Tiket</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #3b82f6;">{{ $stats['booking_tickets'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Booking</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #f59e0b;">{{ $stats['active_tickets'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Aktif (Serah)</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">{{ $stats['done_tickets'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Group 2: Pengadaan Sekolah -->
    <div class="dash-card" style="padding: 20px;">
        <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent); margin-bottom: 16px; display: flex; align-items: center; gap: 6px;">
            <span style="width: 6px; height: 6px; background: var(--accent); border-radius: 50%;"></span>
            Pengadaan Sekolah
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 4px;">
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">{{ $stats['total_procurement'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Total Order</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #f59e0b;">{{ $stats['processing_procurement'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Diproses</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">{{ $stats['completed_procurement'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Selesai</div>
            </div>
            <div style="width: 1px; height: 32px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: #ef4444;">{{ $stats['cancelled_procurement'] }}</div>
                <div style="font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Dibatalkan</div>
            </div>
        </div>
    </div>

    <!-- Group 3: Sistem & Katalog -->
    <div class="dash-card" style="padding: 20px;">
        <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--info); margin-bottom: 16px; display: flex; align-items: center; gap: 6px;">
            <span style="width: 6px; height: 6px; background: var(--info); border-radius: 50%;"></span>
            Pengguna & Produk
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary);">{{ $stats['total_users'] }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">User Staff</div>
            </div>
            <div style="width: 1px; height: 36px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--danger);">{{ $stats['pending_users'] }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Persetujuan</div>
            </div>
            <div style="width: 1px; height: 36px; background: var(--border);"></div>
            <div style="text-align: center; flex: 1;">
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $stats['total_products'] }}</div>
                <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.02em; font-weight: 600; margin-top: 2px;">Produk</div>
            </div>
        </div>
    </div>
</div>

{{-- 2. Monitoring Charts Section --}}
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
    
    {{-- Daily Demand Trend Line Chart --}}
    <div class="card" style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 22px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div>
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0;">Grafik Naik Turun Permintaan (7 Hari Terakhir)</h3>
                <p style="font-size: 0.78rem; color: var(--text-muted); margin: 2px 0 0 0;">Monitoring grafik harian masuknya booking servis & pesanan pengadaan unit.</p>
            </div>
            <div style="display: flex; gap: 12px; font-size: 0.76rem; font-weight: 700;">
                <span style="color: #5f8a63; display: flex; align-items: center; gap: 6px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #5f8a63; display: inline-block;"></span>
                    Servis
                </span>
                <span style="color: #3b82f6; display: flex; align-items: center; gap: 6px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
                    Pengadaan
                </span>
            </div>
        </div>
        <div style="height: 260px; position: relative;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Ticket Status Distribution Doughnut Chart --}}
    <div class="card" style="background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 22px; display: flex; flex-direction: column;">
        <div style="margin-bottom: 14px;">
            <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin: 0;">Distribusi Status Servis</h3>
            <p style="font-size: 0.78rem; color: var(--text-muted); margin: 2px 0 0 0;">Proporsi status tiket servis saat ini.</p>
        </div>
        <div style="flex: 1; min-height: 200px; position: relative; display: flex; align-items: center; justify-content: center;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

{{-- 3. Quick Navigation Cards --}}
<div style="margin-bottom: 20px;">
    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 12px;">Navigasi Cepat Kelola Halaman</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        
        <a href="{{ route('admin.tickets') }}" class="card" style="padding: 16px; border: 1px solid var(--border-light); border-radius: 10px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: all 0.2s ease;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(95,138,99,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Kelola Tiket Servis</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($stats['total_tickets']) }} Tiket Terdaftar</div>
            </div>
        </a>

        <a href="{{ route('admin.procurement') }}" class="card" style="padding: 16px; border: 1px solid var(--border-light); border-radius: 10px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: all 0.2s ease;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(59,130,246,0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Daftar Pengadaan</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($stats['total_procurement']) }} Order Pengadaan</div>
            </div>
        </a>

        <a href="{{ route('admin.procurement-products') }}" class="card" style="padding: 16px; border: 1px solid var(--border-light); border-radius: 10px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: all 0.2s ease;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(245,158,11,0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Excel Unit Pengadaan</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Retail & TKDN Catalog</div>
            </div>
        </a>

        <a href="{{ route('admin.users') }}" class="card" style="padding: 16px; border: 1px solid var(--border-light); border-radius: 10px; text-decoration: none; display: flex; align-items: center; gap: 14px; transition: all 0.2s ease;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(239,68,68,0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">Kelola User Staff</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($stats['total_users']) }} Akun Staff</div>
            </div>
        </a>

    </div>
</div>

{{-- Chart.js Initialization Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Line Chart Trend
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    
    const serviceGradient = ctxTrend.createLinearGradient(0, 0, 0, 260);
    serviceGradient.addColorStop(0, 'rgba(95, 138, 99, 0.3)');
    serviceGradient.addColorStop(1, 'rgba(95, 138, 99, 0.0)');

    const procurementGradient = ctxTrend.createLinearGradient(0, 0, 0, 260);
    procurementGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    procurementGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [
                {
                    label: 'Servis',
                    data: {!! json_encode($serviceTrend) !!},
                    borderColor: '#5f8a63',
                    backgroundColor: serviceGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#5f8a63',
                },
                {
                    label: 'Pengadaan',
                    data: {!! json_encode($procurementTrend) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: procurementGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { weight: 'bold' }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                }
            }
        }
    });

    // 2. Doughnut Chart Status Distribution
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu (Booking)', 'Pengecekan', 'Konfirmasi User', 'Proses Service', 'Siap Diambil'],
            datasets: [{
                data: [
                    {{ $statusDistribution['waiting'] }},
                    {{ $statusDistribution['checking'] }},
                    {{ $statusDistribution['confirmed'] }},
                    {{ $statusDistribution['in_service'] }},
                    {{ $statusDistribution['done'] }}
                ],
                backgroundColor: [
                    '#3b82f6',
                    '#f59e0b',
                    '#8b5cf6',
                    '#ec4899',
                    '#10b981'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: { size: 10, weight: '600' }
                    }
                }
            },
            cutout: '68%'
        }
    });
});
</script>
@endsection
