window.addEventListener('load', () => {

    const form          = document.querySelector('form');
    const inputDate     = document.querySelector('input[name="date_naissance"]');
    const inputBio      = document.querySelector('textarea[name="bio"]');
    const radios        = document.querySelectorAll('input[name="role"]');
    const btnSubmit     = document.querySelector('button[type="submit"]');

    if (!form) return;

    // ────────────────────────────────────────
    // Toast
    // ────────────────────────────────────────

    function showToast(message, duration = 6000) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), duration);
    }

    // Toast si rôle conducteur
    radios.forEach(input => {
        input.addEventListener('change', () => {
            if (input.value === 'conducteur' || input.value === 'passager-conducteur') {
                showToast("⚠️ Votre rôle nécessite des infos véhicule. Elles seront à compléter sur la page suivante.");
            }
            supprimerErreurRadio();
        });
    });

    // ────────────────────────────────────────
    // Utilitaires
    // ────────────────────────────────────────

    function afficherErreur(element, message) {
        const ancienne = element.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();

        element.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className   = 'erreur-champ';
        msg.textContent = message;
        msg.style.color     = '#e74c3c';
        msg.style.fontSize  = '0.8rem';
        msg.style.display   = 'block';
        msg.style.marginTop = '4px';
        element.parentNode.appendChild(msg);
    }

    function supprimerErreur(element) {
        const ancienne = element.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();
        element.classList.remove('input-error');
    }

    // Pour les radios — l'erreur est sur le conteneur parent
    function afficherErreurRadio(message) {
        supprimerErreurRadio();
        const conteneur = radios[0].closest('div') || radios[0].parentNode;
        const msg = document.createElement('span');
        msg.className   = 'erreur-champ erreur-radio';
        msg.textContent = message;
        msg.style.color     = '#e74c3c';
        msg.style.fontSize  = '0.8rem';
        msg.style.display   = 'block';
        msg.style.marginTop = '4px';
        conteneur.appendChild(msg);
    }

    function supprimerErreurRadio() {
        const ancienne = document.querySelector('.erreur-radio');
        if (ancienne) ancienne.remove();
    }

    // ────────────────────────────────────────
    // Règles de validation
    // ────────────────────────────────────────

    function validerRole() {
        const coche = [...radios].some(r => r.checked);
        if (!coche) {
            afficherErreurRadio('Veuillez sélectionner un rôle.');
            return false;
        }
        supprimerErreurRadio();
        return true;
    }

    function validerDate() {
        const val = inputDate.value;
        if (!val) {
            afficherErreur(inputDate, 'La date de naissance est obligatoire.');
            return false;
        }

        const dateNaissance = new Date(val);
        const aujourd_hui   = new Date();

        // Date dans le futur
        if (dateNaissance >= aujourd_hui) {
            afficherErreur(inputDate, 'La date de naissance ne peut pas être dans le futur.');
            return false;
        }

        // Moins de 18 ans
        const age = aujourd_hui.getFullYear() - dateNaissance.getFullYear();
        const anniversairePassé =
            aujourd_hui.getMonth() > dateNaissance.getMonth() ||
            (aujourd_hui.getMonth() === dateNaissance.getMonth() &&
             aujourd_hui.getDate() >= dateNaissance.getDate());
        const ageFinal = anniversairePassé ? age : age - 1;

        if (ageFinal < 18) {
            afficherErreur(inputDate, 'Vous devez avoir au moins 18 ans pour vous inscrire.');
            return false;
        }

        supprimerErreur(inputDate);
        return true;
    }

    function validerBio() {
        const val = inputBio.value.trim();
        if (val.length > 200) {
            afficherErreur(inputBio, `La bio ne doit pas dépasser 200 caractères (${val.length}/200).`);
            return false;
        }
        supprimerErreur(inputBio);
        return true;
    }

    // ────────────────────────────────────────
    // Compteur de caractères bio
    // ────────────────────────────────────────

    const compteur = document.createElement('small');
    compteur.style.color = '#888';
    compteur.style.display = 'block';
    compteur.style.marginTop = '4px';
    compteur.textContent = '0/200 caractères';
    inputBio.parentNode.insertBefore(compteur, inputBio.nextSibling);

    inputBio.addEventListener('input', () => {
        const len = inputBio.value.trim().length;
        compteur.textContent = `${len}/200 caractères`;
        compteur.style.color = len > 200 ? '#e74c3c' : '#888';
        validerBio();
    });

    // ────────────────────────────────────────
    // Validation en live
    // ────────────────────────────────────────

    inputDate.addEventListener('blur',  validerDate);
    inputDate.addEventListener('input', () => supprimerErreur(inputDate));

    // ────────────────────────────────────────
    // Validation à la soumission
    // ────────────────────────────────────────

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        let valide = true;

        if (!validerRole()) valide = false;
        if (!validerDate()) valide = false;
        if (!validerBio())  valide = false;

        if (valide) form.submit();
    });

});