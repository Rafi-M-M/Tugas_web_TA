@extends('layouts.app')

@section('title', 'Lembar Disposisi - Manajemen Persuratan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/surat_masuk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/disposisi.css') }}">
@endpush

@section('page-title', 'Lembar Disposisi')
@section('page-subtitle', 'Rincian disposisi surat masuk')

@section('content')
    @php($canManage = auth()->user()?->role !== 'pimpinan')

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-code-branch"></i> Disposisi: {{ $disposisi->suratMasuk?->nomor_surat ?? '-' }}</h3>
            <a href="{{ route('disposisi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-hashtag"></i> Nomor Surat</span>
                <span class="detail-value">{{ $disposisi->suratMasuk?->nomor_surat ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-user"></i> Pengirim</span>
                <span class="detail-value">{{ $disposisi->suratMasuk?->pengirim ?? '-' }}</span>
            </div>
            <div class="detail-item full-width">
                <span class="detail-label"><i class="fas fa-tag"></i> Perihal Surat</span>
                <span class="detail-value">{{ $disposisi->suratMasuk?->perihal ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-calendar-alt"></i> Tanggal Disposisi</span>
                <span class="detail-value">{{ $disposisi->tanggal_disposisi->format('d F Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-hourglass-half"></i> Batas Waktu</span>
                <span class="detail-value">{{ $disposisi->batas_waktu?->format('d F Y') ?? 'Tidak ada tenggat' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-user-tie"></i> Ditujukan Kepada</span>
                <span class="detail-value">{{ $disposisi->ditujukan_kepada }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-flag"></i> Sifat</span>
                <span class="detail-value"><span class="sifat-badge {{ \Str::slug($disposisi->sifat) }}">{{ $disposisi->sifat }}</span></span>
            </div>
            <div class="detail-item full-width">
                <span class="detail-label"><i class="fas fa-align-left"></i> Isi Disposisi / Instruksi</span>
                <span class="detail-value">{{ $disposisi->instruksi }}</span>
            </div>
            <div class="detail-item full-width">
                <span class="detail-label"><i class="fas fa-sticky-note"></i> Catatan</span>
                <span class="detail-value">{{ $disposisi->catatan ?: '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-user-edit"></i> Dibuat Oleh</span>
                <span class="detail-value">{{ $disposisi->pembuat?->name ?? '-' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-clock"></i> Dibuat Pada</span>
                <span class="detail-value">{{ $disposisi->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-tasks"></i> Status Tindak Lanjut</span>
                <span class="detail-value"><span class="status-badge {{ \Str::slug($disposisi->status) }}">{{ $disposisi->status }}</span></span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-user-check"></i> Ditinjau Oleh</span>
                <span class="detail-value">{{ $disposisi->peninjau?->name ?? 'Belum ditinjau' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-calendar-check"></i> Ditinjau Pada</span>
                <span class="detail-value">{{ $disposisi->ditinjau_pada?->format('d F Y, H:i') ?? '-' }}</span>
            </div>
            <div class="detail-item full-width">
                <span class="detail-label"><i class="fas fa-comment-dots"></i> Catatan Pimpinan</span>
                <span class="detail-value">{{ $disposisi->catatan_pimpinan ?: '-' }}</span>
            </div>
        </div>
    </div>

    @if(auth()->user()?->role === 'pimpinan')
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clipboard-check"></i> Tinjau Disposisi</h3>
        </div>
        <form action="{{ route('disposisi.tinjau', $disposisi->id) }}" method="POST" class="status-form review-form">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="review-sifat"><i class="fas fa-flag"></i> Sifat</label>
                <select id="review-sifat" name="sifat" required>
                    @foreach($sifatOptions as $sifat)
                        <option value="{{ $sifat }}" @selected($disposisi->sifat === $sifat)>{{ $sifat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="review-status"><i class="fas fa-flag-checkered"></i> Status</label>
                <select id="review-status" name="status" required>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" @selected($disposisi->status === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group review-note">
                <label for="catatan_pimpinan"><i class="fas fa-comment-dots"></i> Catatan Pimpinan</label>
                <input type="text" id="catatan_pimpinan" name="catatan_pimpinan" value="{{ old('catatan_pimpinan', $disposisi->catatan_pimpinan) }}" placeholder="Catatan tinjauan (opsional)">
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Tinjauan</button>
        </form>
    </div>
    @endif

    @if($canManage)
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-tasks"></i> Perbarui Tindak Lanjut</h3>
        </div>
        <form action="{{ route('disposisi.status', $disposisi->id) }}" method="POST" class="status-form">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="status"><i class="fas fa-flag-checkered"></i> Status</label>
                <select id="status" name="status" required>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" @selected($disposisi->status === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="catatan"><i class="fas fa-sticky-note"></i> Catatan</label>
                <input type="text" id="catatan" name="catatan" value="{{ old('catatan', $disposisi->catatan) }}" placeholder="Catatan tindak lanjut (opsional)">
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-envelope-open-text"></i> Isi Ringkas Surat</h3>
            @if($disposisi->suratMasuk)
                <a href="{{ route('surat.masuk.show', $disposisi->suratMasuk->id) }}" class="btn btn-secondary btn-danger-sm"><i class="fas fa-eye"></i> Lihat Surat</a>
            @endif
        </div>
        <div class="detail-value">{{ $disposisi->suratMasuk?->isi_ringkas ?? 'Surat tidak ditemukan.' }}</div>
    </div>
@endsection