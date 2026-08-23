@extends('layouts.app')

@section('title', 'Arsip Surat - Manajemen Persuratan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/arsip_surat.css') }}">
@endpush

@section('page-title', 'Arsip Surat')
@section('page-subtitle', 'Dokumen Tersimpan')

@section('content')
    @php($canManage = auth()->user()?->role !== 'pimpinan')

    @unless($canManage)
        <div class="card">
            <div class="card-body">
                <div class="helper-text" style="font-size:14px; color:#334155;">
                    Role pimpinan hanya bisa melihat data arsip.
                </div>
            </div>
        </div>
    @endunless

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-archive"></i> Daftar Arsip Surat</h3>
            @if($canManage)
            @if($arsipMasuk->isNotEmpty() || $arsipKeluar->isNotEmpty())
            <form action="{{ route('arsip.clear') }}" method="POST" onsubmit="return confirm('⚠️ Hapus semua arsip (masuk dan keluar)? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus Semua Arsip</button>
            </form>
            @endif
            @endif
        </div>

        <div class="tabs" id="tabContainer">
            <button class="tab-btn active" data-tab="masuk"><i class="fas fa-inbox"></i> Arsip Surat Masuk</button>
            <button class="tab-btn" data-tab="keluar"><i class="fas fa-paper-plane"></i> Arsip Surat Keluar</button>
        </div>

        <div class="tab-pane active" id="tab-masuk">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" placeholder="Cari nomor, pengirim, atau perihal..." data-search-target="masuk">
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Tanggal</th>
                            <th>Pengirim</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsipMasuk as $item)
                        <tr class="searchable-row">
                            <td><strong>{{ $item->nomor_surat }}</strong></td>
                            <td>{{ $item->tanggal_surat->format('d M Y') }}</td>
                            <td>{{ \Str::limit($item->pengirim, 35) }}</td>
                            <td>{{ \Str::limit($item->perihal, 45) }}</td>
                            <td><span class="status-badge arsip">Arsip</span></td>
                            <td class="text-center">
                                <div class="action-icons">
                                    @if($canManage)
                                    <form action="{{ route('arsip.masuk.restore', $item->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon restore" title="Kembalikan" onclick="return confirm('Kembalikan arsip ini ke daftar surat masuk aktif?');">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('arsip.masuk.destroy', $item->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Hapus" onclick="return confirm('Hapus arsip ini secara permanen?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @else
                                        <span class="helper-text">Hanya lihat</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="6">Belum ada arsip surat masuk.</td></tr>
                        @endforelse
                        <tr class="search-empty-row" style="display:none;"><td colspan="6">Tidak ada hasil pencarian.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="tab-keluar">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" placeholder="Cari nomor, tujuan, atau perihal..." data-search-target="keluar">
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($arsipKeluar as $item)
                        <tr class="searchable-row">
                            <td><strong>{{ $item->nomor_surat }}</strong></td>
                            <td>{{ $item->tanggal_surat->format('d M Y') }}</td>
                            <td>{{ \Str::limit($item->tujuan, 35) }}</td>
                            <td>{{ \Str::limit($item->perihal, 45) }}</td>
                            <td><span class="status-badge arsip">Arsip</span></td>
                            <td class="text-center">
                                <div class="action-icons">
                                    @if($canManage)
                                    <form action="{{ route('arsip.keluar.restore', $item->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon restore" title="Kembalikan" onclick="return confirm('Kembalikan arsip ini ke daftar surat keluar aktif?');">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('arsip.keluar.destroy', $item->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Hapus" onclick="return confirm('Hapus arsip ini secara permanen?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @else
                                        <span class="helper-text">Hanya lihat</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="6">Belum ada arsip surat keluar.</td></tr>
                        @endforelse
                        <tr class="search-empty-row" style="display:none;"><td colspan="6">Tidak ada hasil pencarian.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card info-card">
        <div class="info-card-content">
            <i class="fas fa-info-circle"></i>
            <span><strong>Info:</strong> Untuk mengarsipkan surat, gunakan tombol <i class="fas fa-archive"></i> pada halaman <a href="{{ route('surat.masuk.index') }}">Surat Masuk</a> atau <a href="{{ route('surat.keluar.index') }}">Surat Keluar</a>.</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/arsip_surat.js') }}"></script>
@endpush
