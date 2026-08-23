@extends('layouts.app')

@section('title', 'Detail Surat Keluar - Manajemen Persuratan')
@section('page-title', 'Detail Surat Keluar')
@section('page-subtitle', $surat->nomor_surat)

@push('styles')
<style>
    .letter-preview { background: #eef2f7; padding: 28px; overflow: auto; }
    .letter-paper { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 22mm 20mm; background: #fff; color: #111827; font-family: Georgia, 'Times New Roman', serif; font-size: 14px; line-height: 1.65; box-shadow: 0 8px 28px rgba(15, 59, 94, .14); }
    .letter-head { display: flex; align-items: center; justify-content: center; gap: 18px; text-align: center; }
    .letter-logo { width: 66px; height: 66px; object-fit: contain; }
    .letter-identity { flex: 1; }
    .letter-institution { font-size: 20px; font-weight: 700; text-transform: uppercase; }
    .letter-subtitle { font-size: 16px; font-weight: 700; text-transform: uppercase; }
    .letter-legalitas { font-size: 10px; line-height: 1.35; }
    .letter-address { font-size: 11px; }
    .letter-rule { margin: 10px 0 26px; border-top: 3px solid #111827; border-bottom: 1px solid #111827; height: 4px; }
    .letter-meta { display: grid; gap: 2px; margin-bottom: 28px; }
    .letter-content { min-height: 300px; white-space: pre-line; }
    .letter-signoff { width: 210px; margin: 42px 0 0 auto; text-align: center; }
    .letter-signature { display: block; width: 150px; height: 72px; margin: 8px auto; object-fit: contain; }
    .signature-space { height: 88px; }
    @media print { .sidebar, .topbar, .footer, .detail-actions { display: none !important; } .main-content { margin: 0; } .card { box-shadow: none; border: 0; } .letter-preview { padding: 0; } .letter-paper { box-shadow: none; } }
</style>
@endpush

@section('content')
<div class="card detail-actions">
    <div class="card-header p-2">
        <h3><i class="fas fa-file-alt"></i> {{ $surat->perihal }}</h3>
        <div class="template-page-actions">
            <a href="{{ route('surat.keluar.edit', $surat->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
            <button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
            <form action="{{ route('surat.keluar.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="letter-preview">
        @php($format = $surat->format_surat ?? [])
        @php($defaultLegalitas = "YAYASAN DADALI DINILLAH\nAkta Notaris Heri Hendriana, S.H.,M.H. No. 52 Tanggal 16 November 2023\nSK KemenKumHam dan Hak Asasi Manusia Republik Indonesia\nNo. AHU-AH.01.06-0044455 Tahun 2023\nNSPP/No.Reg. 5.1.0.0.32.06.1866")
        <article class="letter-paper">
            <header class="letter-head">
                @if(!empty($format['logo'])) <img class="letter-logo" src="{{ $format['logo'] }}" alt="Logo instansi"> @endif
                <div class="letter-identity">
                    @if(!empty($format['namaInstansi'])) <div class="letter-institution">{{ $format['namaInstansi'] }}</div> @endif
                    @if(!empty($format['subjudulInstansi'])) <div class="letter-subtitle">{{ $format['subjudulInstansi'] }}</div> @endif
                    <div class="letter-legalitas">{!! nl2br(e($format['legalitas'] ?? $defaultLegalitas)) !!}</div>
                    <div class="letter-address">{{ $format['alamat'] ?? 'Gunung Agra - Cikadongdong - Singaparna - Tasikmalaya 4641' }}</div>
                </div>
            </header>
            <div class="letter-rule"></div>
            <div class="letter-meta">
                <span>Nomor: {{ $surat->nomor_surat }}</span>
                <span>Perihal: {{ $surat->perihal }}</span>
                <span>Kepada Yth. {{ $surat->tujuan }}</span>
            </div>
            <div class="letter-content">{{ $surat->isi_surat }}</div>
            <div class="letter-signoff">
                <div>{{ $format['tempatTanggal'] ?? 'Tasikmalaya, ................' }}</div>
                <div>{{ $format['jabatan'] ?? 'Pimpinan Pondok' }}</div>
                @if(!empty($format['tandaTangan'])) <img class="letter-signature" src="{{ $format['tandaTangan'] }}" alt="Tanda tangan"> @else <div class="signature-space"></div> @endif
                <strong>{{ $format['penandatangan'] ?? '................................' }}</strong>
            </div>
        </article>
    </div>
</div>
@endsection
