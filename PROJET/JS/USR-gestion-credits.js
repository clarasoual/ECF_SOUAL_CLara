// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {

    // -------------------- 1. Alerte demande de crédits --------------------
    const creditForm = document.querySelector('form');

    creditForm.addEventListener('submit', (e) => {
        e.preventDefault(); // éviter le rechargement de page
        alert("Votre demande de crédits a été envoyée ! Vous recevrez une réponse sous peu.");
    });

    // -------------------- 2. Infos-bulles sur les trajets --------------------
    const creditRows = document.querySelectorAll('tbody tr');

    creditRows.forEach(row => {
        const descCell = row.cells[2]; // 3ème colonne = description
        descCell.addEventListener('mouseenter', () => {
            const tooltip = document.createElement('span');
            tooltip.className = 'tooltip';
            tooltip.textContent = `Plus d'infos sur : ${descCell.textContent}`;
            tooltip.style.position = 'absolute';
            tooltip.style.background = '#2e2b28';
            tooltip.style.color = '#f0f0f0';
            tooltip.style.padding = '5px 8px';
            tooltip.style.borderRadius = '6px';
            tooltip.style.fontSize = '0.9rem';
            tooltip.style.top = `${descCell.getBoundingClientRect().top - 30 + window.scrollY}px`;
            tooltip.style.left = `${descCell.getBoundingClientRect().left}px`;
            tooltip.style.zIndex = 1000;
            document.body.appendChild(tooltip);

            descCell.addEventListener('mouseleave', () => {
                tooltip.remove();
            }, { once: true });
        });
    });

    // -------------------- 3. Tri du tableau avec flèches --------------------
    const table = document.querySelector('table');
    const headers = table.querySelectorAll('th');
    const tbody = table.querySelector('tbody');

    headers.forEach((header, index) => {
        // Ajouter curseur pointeur et triangle par CSS
        header.style.cursor = 'pointer';
        header.classList.add('sortable'); // pour CSS si besoin

        header.addEventListener('click', () => {
            const rowsArray = Array.from(tbody.querySelectorAll('tr'));
            const ascending = !header.classList.contains('asc');

            rowsArray.sort((a, b) => {
                const cellA = a.cells[index].textContent.trim();
                const cellB = b.cells[index].textContent.trim();

                // Comparer nombres si possible
                const numA = parseFloat(cellA.replace(/[^\d.-]/g, ''));
                const numB = parseFloat(cellB.replace(/[^\d.-]/g, ''));

                if (!isNaN(numA) && !isNaN(numB)) {
                    return ascending ? numA - numB : numB - numA;
                }
                // Sinon comparer en texte
                return ascending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            });

            // Supprimer l’ordre précédent
            headers.forEach(h => h.classList.remove('asc', 'desc'));

            // Ajouter classe à la colonne triée
            header.classList.add(ascending ? 'asc' : 'desc');

            // Réorganiser le tableau
            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });
});
