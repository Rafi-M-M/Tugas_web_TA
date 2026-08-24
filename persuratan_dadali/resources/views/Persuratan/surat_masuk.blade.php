@extends('layouts.app')

@section('title', 'Surat Masuk - Manajemen Persuratan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/surat_masuk.css') }}">
@endpush



@section('page-title', 'Surat Masuk')
@section('page-subtitle', 'Kelola Surat Masuk')

@section('content')
        @php($canManage = auth()->user()?->role !== 'pimpinan')

        @unless($canManage)
            <div class="card">
                <div class="card-body">
                    <div class="helper-text" style="font-size:14px; color:#334155;">
                        Role pimpinan hanya bisa melihat data surat.
                    </div>
                </div>
            </div>
        @endunless

        @if($canManage)
        <!-- ===== CARD FORM ===== -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-pen-fancy"></i> Form Surat Masuk</h3>
                <span class="card-header-subtitle"><i class="fas fa-asterisk required-asterisk"></i> wajib diisi</span>
            </div>

            <!-- ===== FORM MANUAL ===== -->
            <div class="card-body">
                <div class="form-shell">
                    <div class="form-intro">
                        <span class="form-pill"> Masukan semua informasi pada surat masuk kedalam form </span>
                    </div>

                    <form action="{{ route('surat.masuk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nomor_surat"><i class="fas fa-hashtag"></i> Nomor Surat <span class="required">*</span></label>
                                <input type="text" id="nomor_surat" name="nomor_surat" placeholder="Contoh: 045/PDD/VII/2026" value="{{ old('nomor_surat') }}" required class="@error('nomor_surat') is-invalid @enderror">
                                @error('nomor_surat')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tanggal_surat"><i class="fas fa-calendar-alt"></i> Tanggal Surat <span class="required">*</span></label>
                                <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', now()->toDateString()) }}" required class="@error('tanggal_surat') is-invalid @enderror">
                                @error('tanggal_surat')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group full-width">
                                <label for="pengirim"><i class="fas fa-user"></i> Pengirim <span class="required">*</span></label>
                                <input type="text" id="pengirim" name="pengirim" placeholder="Nama instansi atau perorangan" value="{{ old('pengirim') }}" required class="@error('pengirim') is-invalid @enderror">
                                @error('pengirim')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group full-width">
                                <label for="perihal"><i class="fas fa-tag"></i> Perihal <span class="required">*</span></label>
                                <input type="text" id="perihal" name="perihal" placeholder="Perihal surat" value="{{ old('perihal') }}" required class="@error('perihal') is-invalid @enderror">
                                @error('perihal')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group full-width">
                                <label for="isi_ringkas"><i class="fas fa-align-left"></i> Isi Ringkas <span class="required">*</span></label>
                                <textarea id="isi_ringkas" name="isi_ringkas" rows="3" placeholder="Isi singkat surat" required class="@error('isi_ringkas') is-invalid @enderror">{{ old('isi_ringkas') }}</textarea>
                                @error('isi_ringkas')
                                    <div class="error-text">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Surat</button>
                                <button type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- ===== DAFTAR SURAT MASUK ===== -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list-ul"></i> Riwayat Surat Masuk</h3>
                @if($canManage)
                @if($suratMasukItems->isNotEmpty())
                <form action="{{ route('surat.masuk.clear') }}" method="POST" onsubmit="return confirm('⚠️ Hapus semua riwayat surat masuk? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-danger-sm"><i class="fas fa-trash-alt"></i> Hapus Semua</button>
                </form>
                @endif
                @endif
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
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse ($suratMasukItems as $item)
                        <tr>
                            <td><strong>{{ $item->nomor_surat }}</strong></td>
                            <td>{{ $item->tanggal_surat->format('d M Y') }}</td>
                            <td>{{ \Str::limit($item->pengirim, 35) }}</td>
                            <td>{{ \Str::limit($item->perihal, 45) }}</td>
                            <td><span class="status-badge masuk">Masuk</span></td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <a href="{{ route('surat.masuk.show', $item->id) }}" class="action-icon-primary" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    @if($item->lampiran_path)
                                        <a href="{{ route('surat.masuk.download', $item->id) }}" class="action-icon-secondary" title="Unduh Lampiran"><i class="fas fa-download"></i></a>
                                    @endif
                                    @if($canManage)
                                    <form action="{{ route('surat.masuk.archive', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Arsipkan surat ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon-archive" title="Arsipkan"><i class="fas fa-archive"></i></button>
                                    </form>
                                    <form action="{{ route('surat.masuk.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="6">Belum ada surat masuk yang tersimpan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/surat_masuk.js') }}"></script>
@endpush
