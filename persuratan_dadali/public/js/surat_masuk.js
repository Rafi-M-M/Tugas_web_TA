document.addEventListener('DOMContentLoaded', function () {
    // ============================================================
    // 1. TABS
    // ============================================================
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = {
        manual: document.getElementById('tab-manual'),
        ai: document.getElementById('tab-ai')
    };

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tab = this.dataset.tab;
            Object.keys(tabPanes).forEach(key => {
                if (tabPanes[key]) {
                    tabPanes[key].classList.toggle('active', key === tab);
                }
            });
        });
    });

    // ============================================================
    // 2. AI OCR (SIMULASI)
    // ============================================================
    const gambarInput = document.getElementById('gambarSurat');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const prosesAIButton = document.getElementById('prosesAI');

    if (gambarInput) {
        gambarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    if (prosesAIButton) {
        prosesAIButton.addEventListener('click', function() {
            const file = gambarInput.files[0];
            if (!file) {
                alert('⚠️ Upload gambar terlebih dahulu!');
                return;
            }
            // Simulasi pemrosesan AI (delay)
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            setTimeout(() => {
                // Data dummy hasil OCR
                const dummyData = {
                    nomor: `AI-${String(Math.floor(Math.random()*1000)).padStart(3,'0')}/PDD/${String(new Date().getMonth()+1).padStart(2,'0')}/${new Date().getFullYear()}`,
                    pengirim: 'Dinas Pendidikan Kabupaten',
                    perihal: 'Undangan Rapat Koordinasi',
                    isi: 'Hasil ekstraksi AI: Surat undangan rapat koordinasi yang akan dilaksanakan pada ...'
                };
                document.getElementById('nomorAI').value = dummyData.nomor;
                document.getElementById('pengirimAI').value = dummyData.pengirim;
                document.getElementById('perihalAI').value = dummyData.perihal;
                document.getElementById('isiAI').value = dummyData.isi;
                
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('tanggalAI').value = today;

                this.disabled = false;
                this.innerHTML = '<i class="fas fa-brain"></i> Proses dengan AI (OCR)';
                alert('🤖 AI selesai memproses, data telah diisi!');
            }, 2000);
        });
    }

    // ============================================================
    // 3. RESET FORM AI
    // ============================================================
    const resetAIButton = document.getElementById('resetAI');
    if(resetAIButton) {
        resetAIButton.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('nomorAI').value = '';
            document.getElementById('pengirimAI').value = '';
            document.getElementById('perihalAI').value = '';
            document.getElementById('isiAI').value = '';
            if(gambarInput) gambarInput.value = '';
            if(previewContainer) previewContainer.style.display = 'none';
            if(previewImage) previewImage.src = '#';
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggalAI').value = today;
            alert('🔄 Form AI direset');
        });
    }
});
