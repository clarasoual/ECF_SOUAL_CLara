document.addEventListener('DOMContentLoaded', () => {

    const form            = document.querySelector('.form-inscription');
    const inputPrenom     = document.getElementById('prenom');
    const inputNom        = document.getElementById('nom');
    const inputEmail      = document.getElementById('email');
    const inputPassword   = document.getElementById('password');
    const inputConfirm    = document.getElementById('password_confirm');
    const inputCGU        = document.querySelector('input[name="accept-conditions"]');
    const btnSubmit       = document.querySelector('.btn-submit');

    // ── Désactiver le bouton au chargement ──
    btnSubmit.disabled = true;
    btnSubmit.style.opacity = '0.5';
    btnSubmit.style.cursor  = 'not-allowed';

    // ────────────────────────────────────────
    // Utilitaires
    // ────────────────────────────────────────

    function afficherErreur(input, message) {
        supprimerErreur(input);
        input.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className   = 'erreur-champ';
        msg.textContent = message;
        msg.style.color      = '#e74c3c';
        msg.style.fontSize   = '0.8rem';
        msg.style.display    = 'block';
        msg.style.marginTop  = '4px';
        input.parentNode.insertBefore(msg, input.nextSibling);
    }

    function afficherSucces(input) {
        supprimerErreur(input);
        input.classList.remove('input-error');
        input.classList.add('input-ok');
    }

    function supprimerErreur(input) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();
        input.classList.remove('input-error', 'input-ok');
    }

    // ────────────────────────────────────────
    // Règles de validation
    // ────────────────────────────────────────

    function validerPrenom() {
        const val = inputPrenom.value.trim();
        if (!val) {
            afficherErreur(inputPrenom, 'Le prénom est obligatoire.');
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s\-']{2,50}$/.test(val)) {
            afficherErreur(inputPrenom, 'Le prénom ne doit contenir que des lettres (2 à 50 caractères).');
            return false;
        }
        afficherSucces(inputPrenom);
        return true;
    }

    function validerNom() {
        const val = inputNom.value.trim();
        if (!val) {
            afficherErreur(inputNom, 'Le nom est obligatoire.');
            return false;
        }
        if (!/^[a-zA-ZÀ-ÿ\s\-']{2,50}$/.test(val)) {
            afficherErreur(inputNom, 'Le nom ne doit contenir que des lettres (2 à 50 caractères).');
            return false;
        }
        afficherSucces(inputNom);
        return true;
    }

    function validerEmail() {
        const val = inputEmail.value.trim();
        if (!val) {
            afficherErreur(inputEmail, 'L\'adresse mail est obligatoire.');
            return false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val)) {
            afficherErreur(inputEmail, 'L\'adresse mail n\'est pas valide.');
            return false;
        }
        afficherSucces(inputEmail);
        return true;
    }

    function validerPassword() {
        const val = inputPassword.value;
        if (!val) {
            afficherErreur(inputPassword, 'Le mot de passe est obligatoire.');
            return false;
        }
        if (val.length < 8) {
            afficherErreur(inputPassword, 'Le mot de passe doit contenir au moins 8 caractères.');
            return false;
        }
        if (!/[A-Z]/.test(val)) {
            afficherErreur(inputPassword, 'Le mot de passe doit contenir au moins une majuscule.');
            return false;
        }
        if (!/[a-z]/.test(val)) {
            afficherErreur(inputPassword, 'Le mot de passe doit contenir au moins une minuscule.');
            return false;
        }
        if (!/[0-9]/.test(val)) {
            afficherErreur(inputPassword, 'Le mot de passe doit contenir au moins un chiffre.');
            return false;
        }
        if (!/[^a-zA-Z0-9]/.test(val)) {
            afficherErreur(inputPassword, 'Le mot de passe doit contenir au moins un caractère spécial.');
            return false;
        }
        afficherSucces(inputPassword);
        return true;
    }

    function validerConfirmation() {
        const val = inputConfirm.value;
        if (!val) {
            afficherErreur(inputConfirm, 'Veuillez confirmer votre mot de passe.');
            return false;
        }
        if (val !== inputPassword.value) {
            afficherErreur(inputConfirm, 'Les mots de passe ne correspondent pas.');
            return false;
        }
        afficherSucces(inputConfirm);
        return true;
    }

    function validerCGU() {
        return inputCGU.checked;
    }

    // ────────────────────────────────────────
    // Activation/désactivation du bouton
    // ────────────────────────────────────────

    function mettreAJourBouton() {
        const tout_ok =
            /^[a-zA-ZÀ-ÿ\s\-']{2,50}$/.test(inputPrenom.value.trim()) &&
            /^[a-zA-ZÀ-ÿ\s\-']{2,50}$/.test(inputNom.value.trim()) &&
            /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(inputEmail.value.trim()) &&
            inputPassword.value.length >= 8 &&
            /[A-Z]/.test(inputPassword.value) &&
            /[a-z]/.test(inputPassword.value) &&
            /[0-9]/.test(inputPassword.value) &&
            /[^a-zA-Z0-9]/.test(inputPassword.value) &&
            inputConfirm.value === inputPassword.value &&
            inputCGU.checked;

        btnSubmit.disabled      = !tout_ok;
        btnSubmit.style.opacity = tout_ok ? '1' : '0.5';
        btnSubmit.style.cursor  = tout_ok ? 'pointer' : 'not-allowed';
    }

    // ────────────────────────────────────────
    // Validation en live (au fur et à mesure)
    // ────────────────────────────────────────

    inputPrenom.addEventListener('blur',  validerPrenom);
    inputPrenom.addEventListener('input', () => { validerPrenom(); mettreAJourBouton(); });

    inputNom.addEventListener('blur',  validerNom);
    inputNom.addEventListener('input', () => { validerNom(); mettreAJourBouton(); });

    inputEmail.addEventListener('blur',  validerEmail);
    inputEmail.addEventListener('input', () => { validerEmail(); mettreAJourBouton(); });

    inputPassword.addEventListener('blur',  validerPassword);
    inputPassword.addEventListener('input', () => {
        validerPassword();
        if (inputConfirm.value) validerConfirmation();
        mettreAJourBouton();
    });

    inputConfirm.addEventListener('blur',  validerConfirmation);
    inputConfirm.addEventListener('input', () => { validerConfirmation(); mettreAJourBouton(); });

    inputCGU.addEventListener('change', mettreAJourBouton);

    // ────────────────────────────────────────
    // Validation finale à la soumission
    // ────────────────────────────────────────

    form.addEventListener('submit', (e) => {
        const ok =
            validerPrenom() &
            validerNom() &
            validerEmail() &
            validerPassword() &
            validerConfirmation() &
            validerCGU();

        if (!ok) {
            e.preventDefault();
        }
    });

});