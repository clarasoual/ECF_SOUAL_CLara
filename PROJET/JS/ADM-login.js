window.addEventListener('load', () => {

    const form          = document.getElementById('formulaire-connexion');
    const inputEmail    = document.getElementById('email-connexion');
    const inputPassword = document.getElementById('password');

    if (!form) return;

    // ────────────────────────────────────────
    // Utilitaires
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
    // Validation
    // ────────────────────────────────────────

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
        supprimerErreur(inputEmail);
        return true;
    }

    function validerPassword() {
        const val = inputPassword.value;
        if (!val) {
            afficherErreur(inputPassword, 'Le mot de passe est obligatoire.');
            return false;
        }
        supprimerErreur(inputPassword);
        return true;
    }

    // ────────────────────────────────────────
    // Live
    // ────────────────────────────────────────

    inputEmail.addEventListener('blur',  validerEmail);
    inputEmail.addEventListener('input', () => supprimerErreur(inputEmail));

    inputPassword.addEventListener('blur',  validerPassword);
    inputPassword.addEventListener('input', () => supprimerErreur(inputPassword));

    // ────────────────────────────────────────
    // Soumission
    // ────────────────────────────────────────

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        let valide = true;
        if (!validerEmail())    valide = false;
        if (!validerPassword()) valide = false;

        if (valide) form.submit();
    });

});