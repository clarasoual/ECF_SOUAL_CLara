document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast-success');
    if (!toast) return;

    // Disparaît après 3 secondes
    setTimeout(() => {
        toast.classList.add('hide');
    }, 3000);

    // Supprimer du DOM après l’animation
    setTimeout(() => {
        toast.remove();
    }, 3500);
});
