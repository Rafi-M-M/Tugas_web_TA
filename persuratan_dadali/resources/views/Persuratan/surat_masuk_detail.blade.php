@extends('layouts.app')

@section('title', 'Detail Surat Masuk - Manajemen Persuratan')

@section('page-title', 'Detail Surat Masuk')
@section('page-subtitle', 'Rincian lengkap surat masuk')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-envelope-open-text"></i> Detail Surat: {{ $surat->nomor_surat }}</h3>
        <a href="{{ route('surat.masuk.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-hashtag"></i> Nomor Surat</span>
            <span class="detail-value">{{ $surat->nomor_surat }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-calendar-alt"></i> Tanggal Surat</span>
            <span class="detail-value">{{ $surat->tanggal_surat->format('d F Y') }}</span>
        </div>
        <div class="detail-item full-width">
            <span class="detail-label"><i class="fas fa-user"></i> Pengirim</span>
            <span class="detail-value">{{ $surat->pengirim }}</span>
        </div>
        <div class="detail-item full-width">
            <span class="detail-label"><i class="fas fa-tag"></i> Perihal</span>
            <span class="detail-value">{{ $surat->perihal }}</span>
        </div>
        <div class="detail-item full-width">
            <span class="detail-label"><i class="fas fa-align-left"></i> Isi Ringkas</span>
            <div class="detail-content">
                {!! nl2br(e($surat->isi_ringkas)) !!}
            </div>
        </div>
        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-paperclip"></i> Lampiran</span>
            <span class="detail-value">
                @if ($surat->lampiran_path)
                    <a href="{{ route('surat.masuk.download', $surat->id) }}" class="btn btn-primary"><i class="fas fa-download"></i> Unduh Lampiran</a>
                @else
                    <span class="text-muted">Tidak ada lampiran.</span>
                @endif
            </span>
        </div>
        <div class="detail-item">
            <span class="detail-label"><i class="fas fa-clock"></i> Diterima pada</span>
            <span class="detail-value">{{ $surat->created_at->format('d F Y, H:i') }}</span>
        </div>
    </div>
</div>
@endsection