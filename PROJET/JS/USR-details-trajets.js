// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
    
    // -------------------- Bouton Demander Durée --------------------
    const btnDuration = document.querySelector('.btn-duration button');

    btnDuration.addEventListener('click', () => {
        // Ici tu pourrais faire un fetch pour récupérer la durée réelle
        // Pour l'instant, on simule avec une alerte
        alert("Demande envoyée au chauffeur ! Il vous répondra bientôt.");
    });

    // -------------------- Effet sur les préférences (optionnel) --------------------
    const preferencesList = document.querySelectorAll('.preferences li');

    preferencesList.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.color = '#f4b183'; // survol orange doux
            item.style.cursor = 'pointer';
        });
        item.addEventListener('mouseleave', () => {
            item.style.color = '#f0f0f0'; // revenir à la couleur texte
        });
    });

    // -------------------- Effet sur les véhicules (optionnel) --------------------
    const vehicleBlock = document.querySelector('.vehicle');
    vehicleBlock.addEventListener('click', () => {
        vehicleBlock.style.backgroundColor = '#2a2724'; // foncé au clic
        setTimeout(() => {
            vehicleBlock.style.backgroundColor = '#2e2b28'; // revenir couleur initiale
        }, 300);
    });

});
