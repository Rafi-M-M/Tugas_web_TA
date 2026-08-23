(function () {
    const STORAGE_KEY = 'template_surat_items';
    const DEFAULT_LEGALITAS = 'YAYASAN DADALI DINILLAH\nAkta Notaris Heri Hendriana, S.H.,M.H. No. 52 Tanggal 16 November 2023\nSK KemenKumHam dan Hak Asasi Manusia Republik Indonesia\nNo. AHU-AH.01.06-0044455 Tahun 2023\nNSPP/No.Reg. 5.1.0.0.32.06.1866';
    const templateSelect = document.getElementById('template_surat');

    if (!templateSelect) {
        return;
    }

    function getTemplates() {
        try {
            const templates = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            return Array.isArray(templates) ? templates : [];
        } catch (error) {
            return [];
        }
    }

    function renderTemplateOptions() {
        const selectedValue = templateSelect.dataset.selected || templateSelect.value;
        const templates = getTemplates();

        templateSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = templates.length ? '-- Pilih Template --' : '-- Belum ada template --';
        templateSelect.appendChild(placeholder);

        templates.forEach(function (template) {
            if (!template.nama) {
                return;
            }

            const option = document.createElement('option');
            option.value = template.nama;
            option.textContent = template.kategori ? `${template.nama} (${template.kategori})` : template.nama;
            option.selected = template.nama === selectedValue;
            templateSelect.appendChild(option);
        });
    }

    renderTemplateOptions();

    function applyTemplate(template, askBeforeReplace) {
        if (!template) {
            return;
        }

        const content = document.getElementById('isi_surat');
        const format = document.getElementById('format_surat');

        if (content && (!content.value.trim() || !askBeforeReplace || confirm('Ganti isi surat dengan isi dari template ini?'))) {
            content.value = template.isi || '';
        }

        if (format && template.format) {
            format.value = JSON.stringify({ legalitas: DEFAULT_LEGALITAS, ...(template.format || {}) });
        }
    }

    templateSelect.addEventListener('change', function () {
        const template = getTemplates().find(function (item) {
            return item.nama === templateSelect.value;
        });
        applyTemplate(template, true);
    });
})();
