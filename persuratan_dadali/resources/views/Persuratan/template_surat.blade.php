@extends('layouts.app')

@section('title', 'Template Surat - Manajemen Persuratan')

@section('page-title', 'Template Surat')
@section('page-subtitle', 'Manajemen & Kategori Template')

@push('styles')
<style>
    .template-page-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        max-width: 380px;
        min-width: 160px;
    }

    .search-box input {
        flex: 1;
        padding: 9px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 40px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        background: #fcfcfd;
        transition: border 0.2s, box-shadow 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }

    .category-tree {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .category-group {
        border: 1px solid #e9edf2;
        border-radius: 14px;
        overflow: hidden;
    }

    .category-group .cat-header {
        background: #f8fafc;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 15px;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid transparent;
    }

    .category-group .cat-header:hover {
        background: #f1f5f9;
    }

    .category-group .cat-header i:first-child {
        margin-right: 10px;
        color: #7ec8e3;
        width: 20px;
    }

    .category-group .cat-header .cat-count {
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
        background: #e9edf2;
        padding: 2px 12px;
        border-radius: 40px;
        margin-left: 8px;
    }

    .category-group .cat-header .toggle-icon {
        color: #94a3b8;
        transition: transform 0.25s ease;
    }

    .category-group .cat-header .toggle-icon.open {
        transform: rotate(180deg);
    }

    .category-group .cat-body {
        padding: 8px 12px 12px 20px;
        display: none;
        flex-direction: column;
        gap: 4px;
    }

    .category-group .cat-body.open {
        display: flex;
    }

    .template-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px 10px 8px;
        border-radius: 10px;
        transition: background 0.15s;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 8px;
    }

    .template-item:last-child {
        border-bottom: none;
    }

    .template-item:hover {
        background: #f8fafc;
    }

    .template-item .tpl-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 140px;
    }

    .template-item .tpl-info i {
        color: #7ec8e3;
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .template-item .tpl-info .tpl-name {
        font-weight: 500;
        font-size: 14px;
        color: #1e293b;
    }

    .template-item .tpl-info .tpl-desc {
        font-size: 12px;
        color: #94a3b8;
        margin-left: 4px;
    }

    .template-item .tpl-actions {
        display: flex;
        gap: 6px;
    }

    .template-item .tpl-actions button {
        background: none;
        border: none;
        padding: 4px 8px;
        border-radius: 8px;
        cursor: pointer;
        color: #94a3b8;
        transition: all 0.15s;
        font-size: 14px;
    }

    .template-item .tpl-actions button:hover {
        background: #e9edf2;
        color: #1e293b;
    }

    .template-item .tpl-actions .btn-edit:hover {
        color: #0f3b5e;
    }

    .template-item .tpl-actions .btn-delete:hover {
        color: #dc2626;
    }

    .empty-templates {
        padding: 24px 8px;
        text-align: center;
        color: #94a3b8;
        font-style: italic;
        font-size: 14px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 3000;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: #fff;
        border-radius: 24px;
        max-width: 720px;
        width: 100%;
        max-height: 92vh;
        overflow-y: auto;
        padding: 28px 32px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
        animation: modalIn 0.25s ease;
    }

    @keyframes modalIn {
        from {
            transform: scale(0.95) translateY(20px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e9edf2;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: #0f3b5e;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header h3 i {
        color: #7ec8e3;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .modal-close:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .modal .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
    }

    .modal .form-group.full-width {
        grid-column: 1 / -1;
    }

    .modal .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .modal .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal .form-group label i {
        color: #7ec8e3;
        width: 16px;
    }

    .modal .form-group label .required {
        color: #dc2626;
        font-size: 14px;
    }

    .modal .form-group input,
    .modal .form-group select,
    .modal .form-group textarea {
        font-family: 'Inter', sans-serif;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        transition: border 0.2s, box-shadow 0.2s;
        background: #fcfcfd;
        color: #1e293b;
        width: 100%;
    }

    .modal .form-group input:focus,
    .modal .form-group select:focus,
    .modal .form-group textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        background: #fff;
    }

    .modal .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .modal .form-group .helper-text {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .modal .form-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 12px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .modal {
            padding: 20px 16px;
        }

        .modal .form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .modal .form-group.full-width {
            grid-column: 1;
        }

        .modal .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .modal .btn {
            justify-content: center;
        }

        .template-item {
            flex-direction: column;
            align-items: stretch;
        }

        .template-item .tpl-actions {
            justify-content: flex-end;
        }

        .category-group .cat-header {
            font-size: 14px;
            padding: 12px 16px;
        }

        .category-group .cat-body {
            padding: 6px 8px 10px 14px;
        }
    }

    @media (max-width: 420px) {
        .template-page-actions {
            width: 100%;
        }

        .template-page-actions .btn,
        .template-page-actions .search-box {
            width: 100%;
            max-width: none;
        }
    }
</style>
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
                    <label for="tplIsi"><i class="fas fa-align-left"></i> Isi Template <span class="required">*</span></label>
                    <textarea id="tplIsi" rows="6" placeholder="Tuliskan isi template surat...&#10;&#10;Contoh:&#10;Kepada Yth. ...&#10;Dengan hormat, ..." required></textarea>
                    <div class="helper-text">Isi lengkap template surat, dapat menggunakan placeholder seperti {nama}, {tanggal}, dll.</div>
                </div>

                <div class="form-group full-width">
                    <label for="tplDeskripsi"><i class="fas fa-info-circle"></i> Deskripsi (opsional)</label>
                    <input type="text" id="tplDeskripsi" placeholder="Keterangan singkat tentang template ini">
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
@endsection

@push('scripts')
<script>
    const STORAGE_KEY = 'template_surat_items';
    const CATEGORIES = [
        'Administrasi Umum',
        'Santri',
        'Guru & Staff',
        'Kegiatan',
        'Kerja Sama & Eksternal'
    ];

    function getTemplates() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    }

    function setTemplates(data) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    function escapeHtml(text) {
        if (!text) {
            return '';
        }

        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return String(text).replace(/[&<>"']/g, function(character) {
            return map[character];
        });
    }

    function renderTree(filter = '') {
        const container = document.getElementById('categoryTree');
        const templates = getTemplates();
        const filterLower = filter.toLowerCase().trim();

        let html = '';

        CATEGORIES.forEach((category, categoryIndex) => {
            let items = templates.filter(item => item.kategori === category);

            if (filterLower) {
                items = items.filter(item => {
                    const nameMatch = item.nama.toLowerCase().includes(filterLower);
                    const descriptionMatch = item.deskripsi && item.deskripsi.toLowerCase().includes(filterLower);
                    const contentMatch = item.isi && item.isi.toLowerCase().includes(filterLower);

                    return nameMatch || descriptionMatch || contentMatch;
                });
            }

            const count = items.length;
            const categoryId = `cat-${categoryIndex}`;
            const isOpen = count > 0;

            html += `
                <div class="category-group">
                    <div class="cat-header" data-target="${categoryId}">
                        <span>
                            <i class="fas fa-folder"></i>
                            ${escapeHtml(category)}
                            <span class="cat-count">${count}</span>
                        </span>
                        <i class="fas fa-chevron-down toggle-icon ${isOpen ? 'open' : ''}"></i>
                    </div>
                    <div class="cat-body ${isOpen ? 'open' : ''}" id="${categoryId}">
            `;

            if (count === 0) {
                html += '<div class="empty-templates">Tidak ada template di kategori ini</div>';
            } else {
                items.forEach(item => {
                    const itemIndex = templates.indexOf(item);
                    html += `
                        <div class="template-item" data-idx="${itemIndex}">
                            <div class="tpl-info">
                                <i class="fas fa-file-alt"></i>
                                <span class="tpl-name">${escapeHtml(item.nama)}</span>
                                ${item.deskripsi ? `<span class="tpl-desc">— ${escapeHtml(item.deskripsi)}</span>` : ''}
                            </div>
                            <div class="tpl-actions">
                                <button class="btn-edit" data-idx="${itemIndex}" type="button" title="Edit template"><i class="fas fa-pen"></i></button>
                                <button class="btn-delete" data-idx="${itemIndex}" type="button" title="Hapus template"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                    `;
                });
            }

            html += `
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

        document.querySelectorAll('.cat-header').forEach(header => {
            header.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const body = document.getElementById(targetId);
                const icon = this.querySelector('.toggle-icon');

                if (body) {
                    body.classList.toggle('open');
                }

                if (icon) {
                    icon.classList.toggle('open');
                }
            });
        });

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation();
                openModal(parseInt(this.dataset.idx, 10));
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation();

                const index = parseInt(this.dataset.idx, 10);

                if (confirm('Yakin ingin menghapus template ini?')) {
                    const data = getTemplates();
                    const removed = data.splice(index, 1);
                    setTemplates(data);
                    renderTree(document.getElementById('searchInput').value);
                    showToast(`Template "${removed[0]?.nama || ''}" dihapus`, 'success');
                }
            });
        });
    }

    const modal = document.getElementById('modalTemplate');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('formTemplate');
    const editIndexInput = document.getElementById('editIndex');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnAddTemplate = document.getElementById('btnAddTemplate');
    const btnReset = document.getElementById('btnReset');
    const modalClose = document.getElementById('modalClose');
    const searchInput = document.getElementById('searchInput');

    let isEditMode = false;

    function openModal(index = -1) {
        isEditMode = index >= 0;
        editIndexInput.value = index;

        if (isEditMode) {
            const data = getTemplates();
            const item = data[index];

            if (!item) {
                showToast('Data template tidak ditemukan', 'error');
                return;
            }

            modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Template';
            btnSubmit.innerHTML = '<i class="fas fa-save"></i> Update Template';
            document.getElementById('tplKategori').value = item.kategori || '';
            document.getElementById('tplNama').value = item.nama || '';
            document.getElementById('tplIsi').value = item.isi || '';
            document.getElementById('tplDeskripsi').value = item.deskripsi || '';
        } else {
            modalTitle.innerHTML = '<i class="fas fa-plus"></i> Tambah Template';
            btnSubmit.innerHTML = '<i class="fas fa-save"></i> Simpan Template';
            form.reset();
            editIndexInput.value = '-1';
        }

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    btnAddTemplate.addEventListener('click', function() {
        openModal(-1);
    });

    modalClose.addEventListener('click', closeModal);

    modal.addEventListener('click', function(event) {
        if (event.target === this) {
            closeModal();
        }
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const kategori = document.getElementById('tplKategori').value;
        const nama = document.getElementById('tplNama').value.trim();
        const isi = document.getElementById('tplIsi').value.trim();
        const deskripsi = document.getElementById('tplDeskripsi').value.trim();
        const editIndex = parseInt(editIndexInput.value, 10);

        if (!kategori || !nama || !isi) {
            showToast('Kategori, nama, dan isi template wajib diisi', 'error');
            return;
        }

        const data = getTemplates();

        if (editIndex >= 0 && editIndex < data.length) {
            data[editIndex] = { kategori, nama, isi, deskripsi };
            setTemplates(data);
            showToast(`Template "${nama}" berhasil diperbarui`, 'success');
        } else {
            data.push({ kategori, nama, isi, deskripsi });
            setTemplates(data);
            showToast(`Template "${nama}" berhasil disimpan`, 'success');
        }

        closeModal();
        renderTree(searchInput.value);
    });

    btnReset.addEventListener('click', function(event) {
        event.preventDefault();

        if (isEditMode) {
            const index = parseInt(editIndexInput.value, 10);
            const data = getTemplates();
            const item = data[index];

            if (item) {
                document.getElementById('tplKategori').value = item.kategori || '';
                document.getElementById('tplNama').value = item.nama || '';
                document.getElementById('tplIsi').value = item.isi || '';
                document.getElementById('tplDeskripsi').value = item.deskripsi || '';
            }
        } else {
            form.reset();
        }

        showToast('Form template direset', 'success');
    });

    searchInput.addEventListener('input', function() {
        renderTree(this.value);
    });

    (function seedData() {
        const data = getTemplates();

        if (data.length === 0) {
            setTemplates([
                { kategori: 'Administrasi Umum', nama: 'Surat Undangan', isi: 'Kepada Yth. Bapak/Ibu/Saudara di tempat\n\nAssalamu\'alaikum Wr. Wb.\n\nDengan hormat, kami mengundang Bapak/Ibu/Saudara untuk menghadiri acara ...\n\nDemikian undangan ini, atas perhatiannya kami ucapkan terima kasih.\n\nWassalamu\'alaikum Wr. Wb.', deskripsi: 'Undangan resmi untuk berbagai kegiatan' },
                { kategori: 'Administrasi Umum', nama: 'Surat Pemberitahuan', isi: 'Kepada Yth. ...\n\nDiberitahukan dengan hormat bahwa ...\n\nDemikian pemberitahuan ini, atas perhatiannya diucapkan terima kasih.', deskripsi: 'Pemberitahuan resmi' },
                { kategori: 'Administrasi Umum', nama: 'Surat Permohonan', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mohon ...\n\nDemikian permohonan ini, atas perhatian dan bantuannya kami ucapkan terima kasih.', deskripsi: 'Permohonan resmi' },
                { kategori: 'Administrasi Umum', nama: 'Surat Pengantar', isi: 'Kepada Yth. ...\n\nBersama ini kami sampaikan ...\n\nDemikian surat pengantar ini, atas perhatiannya diucapkan terima kasih.', deskripsi: 'Surat pengantar dokumen' },
                { kategori: 'Administrasi Umum', nama: 'Surat Tugas', isi: 'Kepada Yth. ...\n\nDengan ini kami tugaskan ...\n\nDemikian surat tugas ini, untuk dilaksanakan dengan sebaik-baiknya.', deskripsi: 'Penugasan resmi' },
                { kategori: 'Administrasi Umum', nama: 'Surat Keterangan', isi: 'Yang bertanda tangan di bawah ini ...\n\nMenerangkan bahwa ...\n\nDemikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', deskripsi: 'Surat keterangan resmi' },
                { kategori: 'Administrasi Umum', nama: 'Surat Pernyataan', isi: 'Saya yang bertanda tangan di bawah ini ...\n\nMenyatakan dengan sesungguhnya bahwa ...\n\nDemikian pernyataan ini saya buat dengan sebenarnya.', deskripsi: 'Surat pernyataan' },
                { kategori: 'Administrasi Umum', nama: 'Surat Rekomendasi', isi: 'Kepada Yth. ...\n\nDengan hormat, kami merekomendasikan ...\n\nDemikian rekomendasi ini, atas perhatiannya kami ucapkan terima kasih.', deskripsi: 'Rekomendasi resmi' },
                { kategori: 'Administrasi Umum', nama: 'Surat Balasan', isi: 'Kepada Yth. ...\n\nMenindaklanjuti surat Bapak/Ibu nomor ... perihal ..., dengan ini kami sampaikan ...\n\nDemikian balasan ini, atas perhatiannya diucapkan terima kasih.', deskripsi: 'Balasan surat masuk' },
                { kategori: 'Administrasi Umum', nama: 'Surat Edaran', isi: 'Kepada Yth. ...\n\nDiberitahukan kepada seluruh ... bahwa ...\n\nDemikian edaran ini untuk diketahui dan dilaksanakan.', deskripsi: 'Edaran resmi' },
                { kategori: 'Santri', nama: 'Surat Keterangan Santri Aktif', isi: 'Yang bertanda tangan di bawah ini ...\n\nMenerangkan bahwa santri ... adalah santri aktif di Pondok Pesantren Dadali Dinillah.', deskripsi: 'Keterangan status santri aktif' },
                { kategori: 'Santri', nama: 'Surat Izin Santri', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mengizinkan santri ... untuk ...', deskripsi: 'Surat izin untuk santri' },
                { kategori: 'Santri', nama: 'Surat Keterangan Lulus', isi: 'Yang bertanda tangan di bawah ini ...\n\nMenerangkan bahwa santri ... telah lulus dari ...', deskripsi: 'Keterangan kelulusan santri' },
                { kategori: 'Santri', nama: 'Surat Rekomendasi Santri', isi: 'Kepada Yth. ...\n\nDengan hormat, kami merekomendasikan santri ... untuk ...', deskripsi: 'Rekomendasi untuk santri' },
                { kategori: 'Santri', nama: 'Surat Pemanggilan Orang Tua', isi: 'Kepada Yth. Bapak/Ibu Wali Santri ...\n\nDengan hormat, kami memanggil Bapak/Ibu untuk ...', deskripsi: 'Pemanggilan orang tua/wali santri' },
                { kategori: 'Guru & Staff', nama: 'Surat Tugas', isi: 'Kepada Yth. ...\n\nDengan ini kami tugaskan ...', deskripsi: 'Penugasan untuk guru/staff' },
                { kategori: 'Guru & Staff', nama: 'Surat Keterangan Mengajar', isi: 'Yang bertanda tangan di bawah ini ...\n\nMenerangkan bahwa ... adalah guru di ...', deskripsi: 'Keterangan mengajar' },
                { kategori: 'Guru & Staff', nama: 'Surat Keterangan Bekerja', isi: 'Yang bertanda tangan di bawah ini ...\n\nMenerangkan bahwa ... bekerja di ...', deskripsi: 'Keterangan bekerja' },
                { kategori: 'Guru & Staff', nama: 'Surat Pengangkatan', isi: 'Kepada Yth. ...\n\nDengan ini kami angkat ... sebagai ...', deskripsi: 'Pengangkatan guru/staff' },
                { kategori: 'Guru & Staff', nama: 'Surat Peringatan', isi: 'Kepada Yth. ...\n\nDengan hormat, kami sampaikan peringatan ...', deskripsi: 'Surat peringatan' },
                { kategori: 'Guru & Staff', nama: 'Surat Keputusan (SK)', isi: 'KEPUTUSAN ...\n\nMenimbang ...\n\nMEMUTUSKAN ...', deskripsi: 'Surat Keputusan' },
                { kategori: 'Kegiatan', nama: 'Surat Undangan Kegiatan', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mengundang ... untuk menghadiri kegiatan ...', deskripsi: 'Undangan kegiatan' },
                { kategori: 'Kegiatan', nama: 'Surat Pemberitahuan Kegiatan', isi: 'Kepada Yth. ...\n\nDiberitahukan bahwa akan diadakan kegiatan ...', deskripsi: 'Pemberitahuan kegiatan' },
                { kategori: 'Kegiatan', nama: 'Surat Permohonan Dana', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mohon bantuan dana untuk kegiatan ...', deskripsi: 'Permohonan dana kegiatan' },
                { kategori: 'Kegiatan', nama: 'Surat Permohonan Bantuan', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mohon bantuan ... untuk kegiatan ...', deskripsi: 'Permohonan bantuan kegiatan' },
                { kategori: 'Kegiatan', nama: 'Surat Tugas Panitia', isi: 'Kepada Yth. ...\n\nDengan ini kami tugaskan ... sebagai panitia ...', deskripsi: 'Penugasan panitia kegiatan' },
                { kategori: 'Kegiatan', nama: 'Surat Pengantar Proposal', isi: 'Kepada Yth. ...\n\nBersama ini kami sampaikan proposal kegiatan ...', deskripsi: 'Pengantar proposal kegiatan' },
                { kategori: 'Kerja Sama & Eksternal', nama: 'Surat Permohonan Kerja Sama', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mohon kerja sama ...', deskripsi: 'Permohonan kerja sama' },
                { kategori: 'Kerja Sama & Eksternal', nama: 'Surat Perjanjian Kerja Sama', isi: 'PERJANJIAN KERJA SAMA ...\n\nPasal 1 ...', deskripsi: 'Perjanjian kerja sama' },
                { kategori: 'Kerja Sama & Eksternal', nama: 'Surat Permohonan Narasumber', isi: 'Kepada Yth. ...\n\nDengan hormat, kami mohon kesediaan ... sebagai narasumber ...', deskripsi: 'Permohonan narasumber' },
                { kategori: 'Kerja Sama & Eksternal', nama: 'Surat Undangan Instansi', isi: 'Kepada Yth. Pimpinan ...\n\nDengan hormat, kami mengundang ...', deskripsi: 'Undangan untuk instansi' },
                { kategori: 'Kerja Sama & Eksternal', nama: 'Surat Rekomendasi', isi: 'Kepada Yth. ...\n\nDengan hormat, kami merekomendasikan ...', deskripsi: 'Rekomendasi untuk eksternal' },
            ]);
        }

        renderTree();
    })();
</script>
@endpush