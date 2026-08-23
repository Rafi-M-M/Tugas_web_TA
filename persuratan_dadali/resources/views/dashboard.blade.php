@extends('layouts.app')

@section('title', 'Dashboard - Manajemen Persuratan Digital')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan & Statistik')

@section('content')
<!-- STATISTIK CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Surat Masuk</span>
            <span class="stat-icon blue"><i class="fas fa-inbox"></i></span>
        </div>
        <div class="stat-number">{{ $totalMasuk }}</div>
        <div class="stat-footer">
            <span class="trend"><i class="fas fa-calendar-day"></i> {{ $masukHariIni }} hari ini</span>
            <a href="{{ route('surat.masuk.index') }}" class="more-link">Selengkapnya <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Surat Keluar</span>
            <span class="stat-icon green"><i class="fas fa-paper-plane"></i></span>
        </div>
        <div class="stat-number">{{ $totalKeluar }}</div>
        <div class="stat-footer">
            <span class="trend"><i class="fas fa-calendar-day"></i> {{ $keluarHariIni }} hari ini</span>
            <a href="{{ route('surat.keluar.index') }}" class="more-link">Selengkapnya <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Arsip Surat Masuk</span>
            <span class="stat-icon orange"><i class="fas fa-archive"></i></span>
        </div>
        <div class="stat-number">{{ $totalArsipMasuk }}</div>
        <div class="stat-footer">
            <span class="trend"><i class="fas fa-box"></i> Tersimpan</span>
            <a href="{{ route('arsip.index') }}" class="more-link">Selengkapnya <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-label">Arsip Surat Keluar</span>
            <span class="stat-icon purple"><i class="fas fa-box-archive"></i></span>
        </div>
        <div class="stat-number">{{ $totalArsipKeluar }}</div>
        <div class="stat-footer">
            <span class="trend"><i class="fas fa-box"></i> Tersimpan</span>
            <a href="{{ route('arsip.index') }}" class="more-link">Selengkapnya <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
</div>

<!-- TWO COLUMN -->
<div class="two-col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Surat Terbaru</h3>
            <span class="badge-count">{{ $suratHariIni }} hari ini</span>
            <a href="{{ route('surat.masuk.index') }}" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratTerbaru as $surat)
                    <tr>
                        <td><strong>{{ $surat['nomor_surat'] }}</strong></td>
                        <td>{{ Str::limit($surat['perihal'], 40) }}</td>
                        <td>
                            @php
                                $badgeClass = match($surat['status']) {
                                    'Masuk' => 'masuk',
                                    'Keluar' => 'keluar',
                                    'Arsip' => 'arsip',
                                    default => 'masuk',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">{{ $surat['status'] }}</span>
                        </td>
                        <td>{{ $surat['tanggal']->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#94a3b8; padding:24px 0; font-style:italic;">
                            Belum ada surat tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-bolt"></i> Aktivitas Terakhir</h3>
            <span class="badge-count">{{ $aktivitasTerakhir->count() }} terbaru</span>
        </div>
        <div class="activity-list">
            @forelse ($aktivitasTerakhir as $aktivitas)
            <div class="activity-item">
                <div class="act-icon"><i class="fas {{ $aktivitas['icon'] }}"></i></div>
                <div class="act-content">
                    <div class="act-text">{!! $aktivitas['text'] !!}</div>
                    <div class="act-time"><i class="far fa-clock"></i> {{ $aktivitas['time']->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="activity-item">
                <div class="act-content">
                    <div class="act-text" style="color:#94a3b8; font-style:italic;">Belum ada aktivitas surat.</div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
