document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    if (!searchInput) {
        return;
    }

    const rows = Array.from(document.querySelectorAll('.searchable-row'));
    const emptyRow = document.querySelector('.search-empty-row');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        let visible = 0;

        rows.forEach(function (row) {
            const match = row.textContent.toLowerCase().includes(keyword);
            row.style.display = match ? '' : 'none';
            if (match) {
                visible += 1;
            }
        });

        if (emptyRow) {
            emptyRow.style.display = rows.length && visible === 0 ? '' : 'none';
        }
    });
});