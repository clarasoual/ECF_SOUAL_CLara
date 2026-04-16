document.addEventListener('DOMContentLoaded', () => {

    // ────────────────────────────────────────
    // Utilitaires erreurs
    // ────────────────────────────────────────

    function afficherErreur(input, message) {
        supprimerErreur(input);
        input.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className   = 'erreur-champ';
        msg.textContent = message;
        msg.style.color     = '#e74c3c';
        msg.style.fontSize  = '0.8rem';
        msg.style.display   = 'block';
        msg.style.marginTop = '4px';
        input.parentNode.insertBefore(msg, input.nextSibling);
    }

    function supprimerErreur(input) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();
        input.classList.remove('input-error');
    }

    // ────────────────────────────────────────
    // Formulaire avis
    // ────────────────────────────────────────

    const formAvis       = document.querySelector('form[action*="soumettre-avis"]');
    const selectNote     = document.getElementById('note');
    const textareaAvis   = document.getElementById('commentaire');

    if (formAvis) {

        // Compteur commentaire avis
        const compteurAvis = document.createElement('small');
        compteurAvis.style.color     = '#888';
        compteurAvis.style.display   = 'block';
        compteurAvis.style.marginTop = '4px';
        compteurAvis.textContent     = '0/500 caractères';
        textareaAvis.parentNode.insertBefore(compteurAvis, textareaAvis.nextSibling);

        textareaAvis.addEventListener('input', () => {
            const len = textareaAvis.value.trim().length;
            compteurAvis.textContent = `${len}/500 caractères`;
            compteurAvis.style.color = len > 500 ? '#e74c3c' : '#888';
        });

        selectNote.addEventListener('change', () => supprimerErreur(selectNote));

        formAvis.addEventListener('submit', (e) => {
            supprimerErreur(selectNote);
            supprimerErreur(textareaAvis);

            let valide = true;

            // Note obligatoire
            if (!selectNote.value) {
                afficherErreur(selectNote, 'Veuillez choisir une note.');
                valide = false;
            }

            // Commentaire optionnel mais limité
            if (textareaAvis.value.trim().length > 500) {
                afficherErreur(textareaAvis, 'Le commentaire ne doit pas dépasser 500 caractères.');
                valide = false;
            }

            if (!valide) e.preventDefault();
        });
    }

    // ────────────────────────────────────────
    // Formulaire signalement
    // ────────────────────────────────────────

    const formSignalement   = document.querySelector('form[action*="signaler-trajet"]');
    const textareaSignalement = document.getElementById('commentaire_signalement');

    if (formSignalement) {

        // Compteur commentaire signalement
        const compteurSignalement = document.createElement('small');
        compteurSignalement.style.color     = '#888';
        compteurSignalement.style.display   = 'block';
        compteurSignalement.style.marginTop = '4px';
        compteurSignalement.textContent     = '0/500 caractères';
        textareaSignalement.parentNode.insertBefore(compteurSignalement, textareaSignalement.nextSibling);

        textareaSignalement.addEventListener('input', () => {
            const len = textareaSignalement.value.trim().length;
            compteurSignalement.textContent = `${len}/500 caractères`;
            compteurSignalement.style.color = len > 500 ? '#e74c3c' : '#888';
        });

        textareaSignalement.addEventListener('blur', () => supprimerErreur(textareaSignalement));

        formSignalement.addEventListener('submit', (e) => {
            supprimerErreur(textareaSignalement);

            let valide = true;
            const val = textareaSignalement.value.trim();

            if (!val) {
                afficherErreur(textareaSignalement, 'Veuillez décrire le problème.');
                valide = false;
            } else if (val.length < 10) {
                afficherErreur(textareaSignalement, 'Le signalement doit contenir au moins 10 caractères.');
                valide = false;
            } else if (val.length > 500) {
                afficherErreur(textareaSignalement, 'Le signalement ne doit pas dépasser 500 caractères.');
                valide = false;
            }

            if (!valide) e.preventDefault();
        });
    }

});