document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('.reviews-list table tbody');
    const form = document.querySelector('.search-container');
    const selectAllCheckbox = document.getElementById('select-all');
    const bulkActions = document.querySelector('.bulk-actions');

    // ---------------- Filtrage et tri ----------------
    function filterAndSortTable() {
        const text = form.q.value.toLowerCase().trim();
        const status = form['status'].value;
        const note = form['note'].value;
        const from = form['from'].value;
        const to = form['to'].value;
        const sort = form['sort'].value;

        const statusMap = {
            flagged: "signalé",
            published: "publié",
            archivec: "archivé"
        };
        const statusValue = statusMap[status] || "";

        const rows = Array.from(table.querySelectorAll('tr'));

        rows.forEach(row => {
            const cells = row.children;
            const rowText = `${cells[3].textContent} ${cells[4].textContent} ${cells[6].textContent}`.toLowerCase();
            const rowStatus = cells[7].textContent.toLowerCase();
            const rowNote = cells[5].textContent;
            const rowDate = cells[2].textContent;

            let show = true;
            if (text && !rowText.includes(text)) show = false;
            if (statusValue && rowStatus !== statusValue) show = false;
            if (note && rowNote !== note) show = false;
            if (from && new Date(rowDate) < new Date(from)) show = false;
            if (to && new Date(rowDate) > new Date(to)) show = false;

            row.style.display = show ? '' : 'none';
        });

        // Trier après filtrage
        const visibleRows = Array.from(table.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
        visibleRows.sort((a, b) => {
            let valA, valB;
            switch (sort) {
                case 'newest':
                    valA = new Date(a.children[2].textContent);
                    valB = new Date(b.children[2].textContent);
                    return valB - valA;
                case 'oldest':
                    valA = new Date(a.children[2].textContent);
                    valB = new Date(b.children[2].textContent);
                    return valA - valB;
                case 'rating_desc':
                    valA = parseInt(a.children[5].textContent);
                    valB = parseInt(b.children[5].textContent);
                    return valB - valA;
                case 'rating_asc':
                    valA = parseInt(a.children[5].textContent);
                    valB = parseInt(b.children[5].textContent);
                    return valA - valB;
                default:
                    return 0;
            }
        });

        visibleRows.forEach(row => table.appendChild(row));
    }

    form.addEventListener('submit', e => e.preventDefault());
    form.addEventListener('input', filterAndSortTable);
    form.addEventListener('change', filterAndSortTable);

    // ---------------- Réinitialisation ----------------
    const resetButton = form.querySelector('.btn-reset');
    if (resetButton) {
        resetButton.addEventListener('click', e => {
            e.preventDefault();
            form.reset();
            filterAndSortTable();
        });
    }

    // ---------------- Sélection "Tout cocher" ----------------
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            const checkboxes = table.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        });
    }

    // ---------------- Actions sur la selection ----------------
    if (bulkActions) {
        const archiveBtn = bulkActions.querySelector('button[value="archive"]');
        const flagBtn = bulkActions.querySelector('button[value="flag"]');

        function performAction(action) {
            const selectedRows = table.querySelectorAll('input[type="checkbox"]:checked');
            if (selectedRows.length === 0) {
                alert("Veuillez sélectionner au moins un avis.");
                return;
            }

            selectedRows.forEach(cb => {
                const row = cb.closest('tr');
                if (action === 'archive') row.children[7].textContent = 'Archivé';
                if (action === 'flag') row.children[7].textContent = 'Signalé';
                cb.checked = false; // décocher après action
            });

            // décocher la checkbox "select-all"
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
        }

        archiveBtn.addEventListener('click', e => {
            e.preventDefault();
            performAction('archive');
        });

        flagBtn.addEventListener('click', e => {
            e.preventDefault();
            performAction('flag');
        });
    }
});
