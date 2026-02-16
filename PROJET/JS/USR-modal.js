// --- Modal ---
const modal = document.getElementById('modal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');

// Ouvrir le modal
openBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
});

// Fermer le modal
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Fermer si clic en dehors du contenu
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
