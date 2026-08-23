@extends('layouts.app')

@section('title', 'Template Surat - Manajemen Persuratan')

@section('page-title', 'Template Surat')
@section('page-subtitle', 'Manajemen & Kategori Template')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/template_surat.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-folder-tree"></i> Daftar Template Surat</h3>
        <div class="template-page-actions">
            <div class="search-box">
                <i class="fas fa-search" style="color:#94a3b8;"></i>
                <input type="text" id="searchInput" placeholder="Cari template...">
            </div>
            <button class="btn btn-primary" id="btnAddTemplate" type="button">
                <i class="fas fa-plus"></i> Tambah Template
            </button>
        </div>
    </div>

    <div class="category-tree" id="categoryTree"></div>
</div>

<div class="modal-overlay" id="modalTemplate" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-header">
            <h3 id="modalTitle"><i class="fas fa-file-alt"></i> Tambah Template</h3>
            <button class="modal-close" id="modalClose" type="button" aria-label="Tutup modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="formTemplate" novalidate>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="tplKategori"><i class="fas fa-folder"></i> Kategori <span class="required">*</span></label>
                    <select id="tplKategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Administrasi Umum">Administrasi Umum</option>
                        <option value="Santri">Santri</option>
                        <option value="Guru & Staff">Guru & Staff</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Kerja Sama & Eksternal">Kerja Sama & Eksternal</option>
                    </select>
                    <div class="helper-text">Pilih kategori sesuai struktur template surat</div>
                </div>

                <div class="form-group full-width">
                    <label for="tplNama"><i class="fas fa-tag"></i> Nama Template <span class="required">*</span></label>
                    <input type="text" id="tplNama" placeholder="Contoh: Surat Undangan" required>
                    <div class="helper-text">Nama template yang akan ditampilkan</div>
                </div>

                <div class="form-group full-width">
                    <label for="tplDeskripsi"><i class="fas fa-info-circle"></i> Deskripsi (opsional)</label>
                    <input type="text" id="tplDeskripsi" placeholder="Keterangan singkat tentang template ini">
                </div>

                <div class="form-section-title full-width">Format Surat</div>

                <div class="form-group">
                    <label for="tplNamaInstansi"><i class="fas fa-building"></i> Nama Instansi (opsional)</label>
                    <input type="text" id="tplNamaInstansi" value="" placeholder="Kosongkan jika nama tidak ingin ditampilkan">
                </div>

                <div class="form-group">
                    <label for="tplSubjudulInstansi"><i class="fas fa-heading"></i> Subjudul Instansi</label>
                    <input type="text" id="tplSubjudulInstansi" value="SEKRETARIAT" placeholder="Contoh: SEKRETARIAT">
                </div>

                <div class="form-group full-width">
                    <label for="tplAlamat"><i class="fas fa-location-dot"></i> Alamat dan Kontak</label>
                    <input type="text" id="tplAlamat" value="Gunung Agra - Cikadongdong - Singaparna - Tasikmalaya 4641" placeholder="Alamat lengkap | Telepon | Email">
                </div>

                <div class="form-group full-width">
                    <label for="tplLegalitas"><i class="fas fa-scale-balanced"></i> Legalitas Yayasan</label>
                    <textarea id="tplLegalitas" rows="4" placeholder="Keterangan akta, SK, NSPP, dan alamat. Satu baris untuk setiap keterangan."></textarea>
                    <div class="helper-text">Keterangan ini akan tampil pada kop surat di bawah nama instansi.</div>
                </div>

                <div class="form-group">
                    <label for="tplLogo"><i class="fas fa-image"></i> Logo</label>
                    <input type="file" id="tplLogo" accept="image/png,image/jpeg,image/webp">
                    <div class="helper-text">PNG/JPG/WebP, maksimal 1 MB</div>
                </div>

                <div class="form-group">
                    <label for="tplTempatTanggal"><i class="fas fa-calendar"></i> Tempat dan Tanggal</label>
                    <input type="text" id="tplTempatTanggal" placeholder="Tasikmalaya, 22 Agustus 2026">
                </div>

                <div class="form-group">
                    <label for="tplPenandatangan"><i class="fas fa-user-tie"></i> Nama Penandatangan</label>
                    <input type="text" id="tplPenandatangan" placeholder="Nama lengkap">
                </div>

                <div class="form-group">
                    <label for="tplJabatan"><i class="fas fa-id-badge"></i> Jabatan</label>
                    <input type="text" id="tplJabatan" placeholder="Contoh: Pimpinan Pondok">
                </div>

                <div class="form-group">
                    <label for="tplTandaTangan"><i class="fas fa-signature"></i> Tanda Tangan</label>
                    <input type="file" id="tplTandaTangan" accept="image/png,image/jpeg,image/webp">
                    <div class="helper-text">PNG transparan disarankan, maksimal 1 MB</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fas fa-save"></i> Simpan Template</button>
                    <button type="reset" class="btn btn-secondary" id="btnReset"><i class="fas fa-undo-alt"></i> Reset</button>
                </div>
            </div>
            <input type="hidden" id="editIndex" value="-1">
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalPreview" aria-hidden="true">
    <div class="preview-modal" role="dialog" aria-modal="true" aria-labelledby="previewTitle">
        <div class="modal-header preview-toolbar">
            <h3 id="previewTitle"><i class="fas fa-file-pdf"></i> Preview Surat</h3>
            <div>
                <button class="btn btn-primary" id="btnPrintPreview" type="button"><i class="fas fa-print"></i> Cetak</button>
                <button class="modal-close" id="previewClose" type="button" aria-label="Tutup preview"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="paper-wrap">
            <article class="letter-paper" id="letterPaper"></article>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/template_surat.js') }}"></script>
@endpush
