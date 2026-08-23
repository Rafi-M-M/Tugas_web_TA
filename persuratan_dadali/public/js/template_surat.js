const STORAGE_KEY = 'template_surat_items';
const STORAGE_RESET_KEY = 'template_surat_items_reset_v1';
const DEFAULT_LEGALITAS = 'YAYASAN DADALI DINILLAH\nAkta Notaris Heri Hendriana, S.H.,M.H. No. 52 Tanggal 16 November 2023\nSK KemenKumHam dan Hak Asasi Manusia Republik Indonesia\nNo. AHU-AH.01.06-0044455 Tahun 2023\nNSPP/No.Reg. 5.1.0.0.32.06.1866';
const DEFAULT_ALAMAT = 'Gunung Agra - Cikadongdong - Singaparna - Tasikmalaya 4641';
const CATEGORIES = [
    'Administrasi Umum',
    'Santri',
    'Guru & Staff',
    'Kegiatan',
    'Kerja Sama & Eksternal'
];

if (!localStorage.getItem(STORAGE_RESET_KEY)) {
    localStorage.removeItem(STORAGE_KEY);
    localStorage.setItem(STORAGE_RESET_KEY, '1');
}

function getTemplates() {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]').map(item => ({
        ...item,
        format: {
            legalitas: DEFAULT_LEGALITAS,
            ...(item.format || {}),
            namaInstansi: item.format?.namaInstansi === 'PONDOK PESANTREN DADALI DINILLAH' ? '' : item.format?.namaInstansi || ''
        }
    }));
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
                            ${item.deskripsi ? `<span class="tpl-desc">- ${escapeHtml(item.deskripsi)}</span>` : ''}
                        </div>
                        <div class="tpl-actions">
                            <button class="btn-preview" data-idx="${itemIndex}" type="button" title="Lihat hasil template"><i class="fas fa-eye"></i></button>
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

    document.querySelectorAll('.btn-preview').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            openPreview(parseInt(this.dataset.idx, 10));
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
const previewModal = document.getElementById('modalPreview');
const letterPaper = document.getElementById('letterPaper');
const previewClose = document.getElementById('previewClose');
const btnPrintPreview = document.getElementById('btnPrintPreview');

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
        document.getElementById('tplDeskripsi').value = item.deskripsi || '';
        fillFormatForm(item.format);
    } else {
        modalTitle.innerHTML = '<i class="fas fa-plus"></i> Tambah Template';
        btnSubmit.innerHTML = '<i class="fas fa-save"></i> Simpan Template';
        form.reset();
        editIndexInput.value = '-1';
        fillFormatForm();
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
}

function closeModal() {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
}

function readImage(file) {
    if (!file) {
        return Promise.resolve('');
    }

    if (file.size > 1024 * 1024) {
        return Promise.reject(new Error('Ukuran gambar maksimal 1 MB'));
    }

    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = () => reject(new Error('Gambar tidak dapat dibaca'));
        reader.readAsDataURL(file);
    });
}

function fillFormatForm(format = {}) {
    document.getElementById('tplNamaInstansi').value = format.namaInstansi || '';
    document.getElementById('tplSubjudulInstansi').value = format.subjudulInstansi || '';
    document.getElementById('tplAlamat').value = format.alamat || DEFAULT_ALAMAT;
    document.getElementById('tplLegalitas').value = format.legalitas || DEFAULT_LEGALITAS;
    document.getElementById('tplTempatTanggal').value = format.tempatTanggal || '';
    document.getElementById('tplPenandatangan').value = format.penandatangan || '';
    document.getElementById('tplJabatan').value = format.jabatan || '';
    document.getElementById('tplLogo').value = '';
    document.getElementById('tplTandaTangan').value = '';
}

function openPreview(index) {
    const item = getTemplates()[index];

    if (!item) {
        showToast('Data template tidak ditemukan', 'error');
        return;
    }

    const format = item.format || {};
    const legalitas = (format.legalitas || DEFAULT_LEGALITAS).split(/\r?\n/).map((line, index) => index === 0 ? `<strong>${escapeHtml(line)}</strong>` : escapeHtml(line)).join('<br>');
    const body = escapeHtml(item.isi || '').replace(/\r?\n/g, '<br>');
    const logo = format.logo ? `<img class="letter-logo" src="${format.logo}" alt="Logo instansi">` : '';
    const signature = format.tandaTangan ? `<img class="letter-signature" src="${format.tandaTangan}" alt="Tanda tangan">` : '<div class="signature-space"></div>';

    letterPaper.innerHTML = `
        <header class="letter-head">
            ${logo}
            <div class="letter-identity">
                ${format.namaInstansi ? `<div class="letter-institution">${escapeHtml(format.namaInstansi)}</div>` : ''}
                ${format.subjudulInstansi ? `<div class="letter-subtitle">${escapeHtml(format.subjudulInstansi)}</div>` : ''}
                <div class="letter-legalitas">${legalitas}</div>
                <div class="letter-address">${escapeHtml(format.alamat || 'Alamat dan kontak instansi')}</div>
            </div>
        </header>
        <div class="letter-rule"></div>
        <div class="letter-meta">
            <span>Nomor: ................................</span>
            <span>Perihal: ${escapeHtml(item.nama)}</span>
        </div>
        <div class="letter-content">${body}</div>
        <div class="letter-signoff">
            <div>${escapeHtml(format.tempatTanggal || 'Tasikmalaya, ................')}</div>
            <div>${escapeHtml(format.jabatan || 'Pimpinan Pondok')}</div>
            ${signature}
            <strong>${escapeHtml(format.penandatangan || '................................')}</strong>
        </div>
    `;

    previewModal.classList.add('active');
    previewModal.setAttribute('aria-hidden', 'false');
}

function closePreview() {
    previewModal.classList.remove('active');
    previewModal.setAttribute('aria-hidden', 'true');
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

form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const kategori = document.getElementById('tplKategori').value;
    const nama = document.getElementById('tplNama').value.trim();
    const isi = '';
    const deskripsi = document.getElementById('tplDeskripsi').value.trim();
    const editIndex = parseInt(editIndexInput.value, 10);

    if (!kategori || !nama) {
        showToast('Kategori dan nama template wajib diisi', 'error');
        return;
    }

    const data = getTemplates();
    const currentFormat = editIndex >= 0 && data[editIndex] ? data[editIndex].format || {} : {};

    try {
        const format = {
            namaInstansi: document.getElementById('tplNamaInstansi').value.trim(),
            subjudulInstansi: document.getElementById('tplSubjudulInstansi').value.trim(),
            alamat: document.getElementById('tplAlamat').value.trim(),
            legalitas: document.getElementById('tplLegalitas').value.trim(),
            tempatTanggal: document.getElementById('tplTempatTanggal').value.trim(),
            penandatangan: document.getElementById('tplPenandatangan').value.trim(),
            jabatan: document.getElementById('tplJabatan').value.trim(),
            logo: await readImage(document.getElementById('tplLogo').files[0]) || currentFormat.logo || '',
            tandaTangan: await readImage(document.getElementById('tplTandaTangan').files[0]) || currentFormat.tandaTangan || ''
        };

        if (editIndex >= 0 && editIndex < data.length) {
            data[editIndex] = { kategori, nama, isi, deskripsi, format };
            showToast(`Template "${nama}" berhasil diperbarui`, 'success');
        } else {
            data.push({ kategori, nama, isi, deskripsi, format });
            showToast(`Template "${nama}" berhasil disimpan`, 'success');
        }

        setTemplates(data);
    } catch (error) {
        showToast(error.message, 'error');
        return;
    }

    closeModal();
    renderTree(searchInput.value);
});

previewClose.addEventListener('click', closePreview);

previewModal.addEventListener('click', function(event) {
    if (event.target === this) {
        closePreview();
    }
});

btnPrintPreview.addEventListener('click', function() {
    window.print();
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

renderTree();
