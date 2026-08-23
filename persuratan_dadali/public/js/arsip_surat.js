/**
 * Arsip Surat — tab switching and search
 */
(function () {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = {
        masuk: document.getElementById('tab-masuk'),
        keluar: document.getElementById('tab-keluar'),
    };

    if (tabBtns.length) {
        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tabBtns.forEach(function (b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                const tab = this.dataset.tab;
                Object.keys(tabPanes).forEach(function (key) {
                    if (tabPanes[key]) {
                        tabPanes[key].classList.toggle('active', key === tab);
                    }
                });
            });
        });
    }

    const searchInputs = document.querySelectorAll('.search-input');

    searchInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            const pane = this.closest('.tab-pane');
            const rows = pane ? pane.querySelectorAll('tbody .searchable-row') : [];
            const emptyRow = pane ? pane.querySelector('.search-empty-row') : null;
            let hasMatch = false;

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(query);
                row.style.display = matches ? '' : 'none';
                if (matches) {
                    hasMatch = true;
                }
            });

            if (emptyRow) {
                emptyRow.style.display = hasMatch || query === '' ? 'none' : '';
            }
        });
    });
})();
