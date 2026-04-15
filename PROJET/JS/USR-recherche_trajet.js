document.addEventListener('DOMContentLoaded', () => {

    // -------------------- Validation formulaire de recherche --------------------

    function estSaine(valeur) {
        return /^[a-zA-ZÀ-ÿ0-9\s\-',.]+$/.test(valeur);
    }

    function dateValide(dateStr) {
        if (!dateStr) return false;
        const aujourd_hui = new Date();
        aujourd_hui.setHours(0, 0, 0, 0);
        const dateChoisie = new Date(dateStr);
        return dateChoisie >= aujourd_hui;
    }

    function afficherErreur(input, message) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();

        input.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className = 'erreur-champ';
        msg.textContent = message;
        msg.style.color = '#e74c3c';
        msg.style.fontSize = '0.8rem';
        msg.style.display = 'block';
        msg.style.marginTop = '4px';
        input.parentNode.appendChild(msg);
    }

    function supprimerErreur(input) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();
        input.classList.remove('input-error');
    }

    const form = document.querySelector('.search-section form');

    if (form) {
        const inputDepart      = form.querySelector('input[name="departure"]');
        const inputDestination = form.querySelector('input[name="destination"]');
        const inputDate        = form.querySelector('input[name="date"]');
        const inputPassager    = form.querySelector('input[name="passenger"]');

        [inputDepart, inputDestination, inputDate, inputPassager].forEach(input => {
            input.addEventListener('input', () => supprimerErreur(input));
        });

        form.addEventListener('submit', (e) => {
            [inputDepart, inputDestination, inputDate, inputPassager].forEach(supprimerErreur);

            let valide = true;

            const depart      = inputDepart.value.trim();
            const destination = inputDestination.value.trim();
            const date        = inputDate.value;
            const passager    = parseInt(inputPassager.value, 10);

            // --- Départ ---
            if (!depart) {
                afficherErreur(inputDepart, 'Veuillez indiquer une ville de départ.');
                valide = false;
            } else if (!estSaine(depart)) {
                afficherErreur(inputDepart, 'La ville de départ contient des caractères non autorisés.');
                valide = false;
            }

            // --- Destination ---
            if (!destination) {
                afficherErreur(inputDestination, 'Veuillez indiquer une ville d\'arrivée.');
                valide = false;
            } else if (!estSaine(destination)) {
                afficherErreur(inputDestination, 'La ville d\'arrivée contient des caractères non autorisés.');
                valide = false;
            } else if (destination.toLowerCase() === depart.toLowerCase()) {
                afficherErreur(inputDestination, 'La ville d\'arrivée doit être différente du départ.');
                valide = false;
            }

            // --- Date ---
            if (!date) {
                afficherErreur(inputDate, 'Veuillez sélectionner une date.');
                valide = false;
            } else if (!dateValide(date)) {
                afficherErreur(inputDate, 'La date ne peut pas être dans le passé.');
                valide = false;
            }

            // --- Passagers ---
            if (isNaN(passager) || passager < 1) {
                afficherErreur(inputPassager, 'Le nombre de passagers doit être au moins 1.');
                valide = false;
            } else if (passager > 8) {
                afficherErreur(inputPassager, 'Le nombre de passagers ne peut pas dépasser 8.');
                valide = false;
            }

            if (!valide) {
                e.preventDefault();
            }
        });
    }

});