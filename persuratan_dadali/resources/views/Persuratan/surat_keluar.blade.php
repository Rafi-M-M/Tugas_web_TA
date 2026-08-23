@extends('layouts.app')

@section('title', 'Surat Keluar - Manajemen Persuratan')

@section('page-title', 'Surat Keluar')
@section('page-subtitle', 'Buat & Kelola Surat')

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
        <!-- ===== FORM SURAT KELUAR ===== -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-pen-fancy"></i> {{ isset($surat) ? 'Edit Surat Keluar' : 'Form Surat Keluar' }}</h3>
                <span class="card-header-subtitle"><i class="fas fa-asterisk required-asterisk"></i> wajib diisi</span>
            </div>

            <form action="{{ isset($surat) ? route('surat.keluar.update', $surat->id) : route('surat.keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @isset($surat) @method('PUT') @endisset
                <div class="form-grid">

                    <!-- Nomor Surat (auto) -->
                    <div class="form-group">
                        <label for="nomor_surat"><i class="fas fa-hashtag"></i> Nomor Surat <span class="required">*</span></label>
                        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat ?? $nomorSuratOtomatis) }}" readonly class="@error('nomor_surat') is-invalid @enderror">
                        <div class="helper-text">Akan digenerate otomatis</div>
                        @error('nomor_surat')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div class="form-group">
                        <label for="tanggal_surat"><i class="fas fa-calendar-alt"></i> Tanggal Surat <span class="required">*</span></label>
                        <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', isset($surat) ? $surat->tanggal_surat->format('Y-m-d') : now()->toDateString()) }}" class="@error('tanggal_surat') is-invalid @enderror">
                        @error('tanggal_surat')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tujuan (Penerima) -->
                    <div class="form-group full-width">
                        <label for="tujuan"><i class="fas fa-user"></i> Tujuan / Penerima <span class="required">*</span></label>
                        <input type="text" id="tujuan" name="tujuan" placeholder="Nama instansi atau perorangan" value="{{ old('tujuan', $surat->tujuan ?? '') }}" required class="@error('tujuan') is-invalid @enderror">
                        @error('tujuan')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Surat (dropdown) -->
                    <div class="form-group">
                        <label for="template_surat"><i class="fas fa-file-alt"></i> Template Surat <span class="required">*</span></label>
                        <select name="template_surat" id="template_surat" data-selected="{{ old('template_surat', $surat->template_surat ?? '') }}" class="@error('template_surat') is-invalid @enderror">
                            <option value="">-- Pilih Template --</option>
                        </select>
                        @error('template_surat')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status (default Keluar) -->
                    <div class="form-group">
                        <label for="status"><i class="fas fa-info-circle"></i> Status</label>
                        <input type="text" id="status" name="status" value="Keluar" readonly>
                    </div>

                    <div class="form-group full-width">
                        <label for="isi_surat"><i class="fas fa-align-left"></i> Isi Surat <span class="required">*</span></label>
                        <textarea id="isi_surat" name="isi_surat" rows="10" required placeholder="Isi surat akan terisi dari template dan dapat disesuaikan">{{ old('isi_surat', $surat->isi_surat ?? '') }}</textarea>
                        @error('isi_surat') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <input type="hidden" id="format_surat" name="format_surat" value="{{ old('format_surat', isset($surat) ? json_encode($surat->format_surat) : '') }}">

                    <!-- Lampiran (file) -->
                    <div class="form-group full-width">
                        <label for="lampiran"><i class="fas fa-paperclip"></i> Lampiran (opsional)</label>
                        <input type="file" id="lampiran" name="lampiran" accept=".pdf,.doc,.docx,.jpg,.png" class="@error('lampiran') is-invalid @enderror">
                        <div class="helper-text">Maks. 2MB, format PDF/DOC/JPG/PNG</div>
                        @error('lampiran')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Surat</button>
                        <button type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Reset</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <!-- ===== DAFTAR SURAT KELUAR (dari localStorage) ===== -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list-ul"></i> Riwayat Surat Keluar</h3>
                @if($canManage)
                @if($suratKeluarItems->isNotEmpty())
                <form action="{{ route('surat.keluar.clear') }}" method="POST" onsubmit="return confirm('⚠️ Hapus semua riwayat surat keluar? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-danger-sm"><i class="fas fa-trash-alt"></i> Hapus Semua</button>
                </form>
                @endif
                @endif
            </div>
            <div class="table-wrap">
                <table id="suratTable">
                    <thead>
                        <tr>
                            <th>No. Surat</th>
                            <th>Tanggal</th>
                            <th>Perihal</th>
                            <th>Tujuan</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratTableBody">
                        @forelse ($suratKeluarItems as $item)
                        <tr>
                            <td><strong>{{ $item->nomor_surat }}</strong></td>
                            <td>{{ $item->tanggal_surat->format('d M Y') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->perihal, 40) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->tujuan, 30) }}</td>
                            <td>{{ $item->template_surat }}</td>
                            <td><span class="status-badge keluar">Keluar</span></td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <a href="{{ route('surat.keluar.show', $item->id) }}" class="detail" title="Lihat detail"><i class="fas fa-eye"></i></a>
                                    @if($canManage)
                                    <a href="{{ route('surat.keluar.edit', $item->id) }}" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('surat.keluar.archive', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Arsipkan surat ini?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon-archive" title="Arsipkan"><i class="fas fa-archive"></i></button>
                                    </form>
                                    <form action="{{ route('surat.keluar.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="7">Belum ada surat keluar yang tersimpan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection

    @push('scripts')
    <script src="{{ asset('js/surat_keluar.js') }}"></script>
    @endpush