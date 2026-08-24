@extends('layouts.app')

@section('title', 'Disposisi Surat - Manajemen Persuratan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/surat_masuk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/disposisi.css') }}">
@endpush

@section('page-title', 'Disposisi Surat')
@section('page-subtitle', 'Tindak Lanjut Surat Masuk')

@section('content')
    @php($canManage = auth()->user()?->role !== 'pimpinan')

    @unless($canManage)
        <div class="card">
            <div class="card-body">
                <div class="helper-text" style="font-size:14px; color:#334155;">
                    Role pimpinan hanya bisa melihat data disposisi.
                </div>
            </div>
        </div>
    @endunless

    @if($canManage)
    <!-- ===== CARD FORM ===== -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-code-branch"></i> Form Disposisi</h3>
            <span class="card-header-subtitle"><i class="fas fa-asterisk required-asterisk"></i> wajib diisi</span>
        </div>

        <div class="card-body">
            @if($suratMasukOptions->isEmpty())
                <div class="helper-text" style="font-size:14px; color:#334155;">
                    Belum ada surat masuk yang bisa didisposisikan. Tambahkan surat pada halaman
                    <a href="{{ route('surat.masuk.index') }}">Surat Masuk</a> terlebih dahulu.
                </div>
            @else
            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="surat_masuk_id"><i class="fas fa-inbox"></i> Surat Masuk <span class="required">*</span></label>
                        <select id="surat_masuk_id" name="surat_masuk_id" required class="@error('surat_masuk_id') is-invalid @enderror">
                            <option value="">-- Pilih surat masuk --</option>
                            @foreach($suratMasukOptions as $surat)
                                <option value="{{ $surat->id }}" @selected(old('surat_masuk_id') == $surat->id)>
                                    {{ $surat->nomor_surat }} — {{ \Str::limit($surat->perihal, 50) }} ({{ $surat->pengirim }})
                                </option>
                            @endforeach
                        </select>
                        @error('surat_masuk_id')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_disposisi"><i class="fas fa-calendar-alt"></i> Tanggal Disposisi <span class="required">*</span></label>
                        <input type="date" id="tanggal_disposisi" name="tanggal_disposisi" value="{{ old('tanggal_disposisi', now()->toDateString()) }}" required class="@error('tanggal_disposisi') is-invalid @enderror">
                        @error('tanggal_disposisi')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="batas_waktu"><i class="fas fa-hourglass-half"></i> Batas Waktu</label>
                        <input type="date" id="batas_waktu" name="batas_waktu" value="{{ old('batas_waktu') }}" class="@error('batas_waktu') is-invalid @enderror">
                        <div class="helper-text">Kosongkan jika tidak ada tenggat.</div>
                        @error('batas_waktu')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ditujukan_kepada"><i class="fas fa-user-tie"></i> Ditujukan Kepada <span class="required">*</span></label>
                        <input type="text" id="ditujukan_kepada" name="ditujukan_kepada" placeholder="Contoh: Sekretaris / Bendahara" value="{{ old('ditujukan_kepada') }}" required class="@error('ditujukan_kepada') is-invalid @enderror">
                        @error('ditujukan_kepada')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sifat"><i class="fas fa-flag"></i> Sifat <span class="required">*</span></label>
                        <select id="sifat" name="sifat" required class="@error('sifat') is-invalid @enderror">
                            @foreach($sifatOptions as $sifat)
                                <option value="{{ $sifat }}" @selected(old('sifat', 'Biasa') === $sifat)>{{ $sifat }}</option>
                            @endforeach
                        </select>
                        @error('sifat')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="instruksi"><i class="fas fa-align-left"></i> Isi Disposisi / Instruksi <span class="required">*</span></label>
                        <textarea id="instruksi" name="instruksi" rows="3" placeholder="Contoh: Harap ditindaklanjuti dan dibuatkan surat balasan." required class="@error('instruksi') is-invalid @enderror">{{ old('instruksi') }}</textarea>
                        @error('instruksi')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="catatan"><i class="fas fa-sticky-note"></i> Catatan</label>
                        <textarea id="catatan" name="catatan" rows="2" placeholder="Catatan tambahan (opsional)" class="@error('catatan') is-invalid @enderror">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Disposisi</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Reset</button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
    @endif

    <!-- ===== DAFTAR DISPOSISI ===== -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list-ul"></i> Daftar Disposisi</h3>
            @if($canManage && $disposisiItems->isNotEmpty())
            <form action="{{ route('disposisi.clear') }}" method="POST" onsubmit="return confirm('⚠️ Hapus semua disposisi? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-danger-sm"><i class="fas fa-trash-alt"></i> Hapus Semua</button>
            </form>
            @endif
        </div>

        <div class="table-toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="search-input" placeholder="Cari nomor surat, tujuan, atau instruksi...">
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Ditujukan Kepada</th>
                        <th>Sifat</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposisiItems as $item)
                    <tr class="searchable-row">
                        <td><strong>{{ $item->suratMasuk?->nomor_surat ?? '-' }}</strong></td>
                        <td>{{ $item->tanggal_disposisi->format('d M Y') }}</td>
                        <td>{{ \Str::limit($item->ditujukan_kepada, 30) }}</td>
                        <td><span class="sifat-badge {{ \Str::slug($item->sifat) }}">{{ $item->sifat }}</span></td>
                        <td>{{ $item->batas_waktu?->format('d M Y') ?? '-' }}</td>
                        <td><span class="status-badge {{ \Str::slug($item->status) }}">{{ $item->status }}</span></td>
                        <td class="text-center">
                            <div class="action-icons">
                                <a href="{{ route('disposisi.show', $item->id) }}" class="action-icon-primary" title="Lihat Lembar Disposisi"><i class="fas fa-eye"></i></a>
                                @if($canManage)
                                <form action="{{ route('disposisi.destroy', $item->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Hapus disposisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="7">Belum ada disposisi yang tersimpan.</td></tr>
                    @endforelse
                    <tr class="search-empty-row" style="display:none;"><td colspan="7">Tidak ada hasil pencarian.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/disposisi.js') }}"></script>
@endpush