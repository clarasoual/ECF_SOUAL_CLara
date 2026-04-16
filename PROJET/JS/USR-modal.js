// ────────────────────────────────────────
// Modal — ouverture / fermeture
// ────────────────────────────────────────

const modal   = document.getElementById('modal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');

openBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

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
// Références champs
// ────────────────────────────────────────

const inputPhoto = document.getElementById('photo');
const inputDate  = document.getElementById('date_naissance');
const inputBio   = document.getElementById('bio');
const form       = document.querySelector('#modal form');

// ────────────────────────────────────────
// Compteur bio
// ────────────────────────────────────────

const compteur = document.createElement('small');
compteur.style.color   = '#888';
compteur.style.display = 'block';
compteur.style.marginTop = '4px';
compteur.textContent   = `${inputBio.value.length}/200 caractères`;
inputBio.parentNode.insertBefore(compteur, inputBio.nextSibling);

inputBio.addEventListener('input', () => {
    const len = inputBio.value.trim().length;
    compteur.textContent = `${len}/200 caractères`;
    compteur.style.color = len > 200 ? '#e74c3c' : '#888';
});

// ────────────────────────────────────────
// Règles de validation
// ────────────────────────────────────────

function validerPhoto() {
    if (!inputPhoto.files || !inputPhoto.files[0]) return true; // optionnel
    const file     = inputPhoto.files[0];
    const typeOk   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type);
    const tailleOk = file.size <= 2 * 1024 * 1024; // 2 Mo max

    if (!typeOk) {
        afficherErreur(inputPhoto, 'Format accepté : JPG, PNG, WEBP, GIF.');
        return false;
    }
    if (!tailleOk) {
        afficherErreur(inputPhoto, 'La photo ne doit pas dépasser 2 Mo.');
        return false;
    }
    supprimerErreur(inputPhoto);
    return true;
}

function validerDate() {
    if (!inputDate.value) return true; // optionnel

    const dateNaissance = new Date(inputDate.value);
    const aujourd_hui   = new Date();

    if (dateNaissance >= aujourd_hui) {
        afficherErreur(inputDate, 'La date de naissance ne peut pas être dans le futur.');
        return false;
    }

    const age = aujourd_hui.getFullYear() - dateNaissance.getFullYear();
    const anniversairePassé =
        aujourd_hui.getMonth() > dateNaissance.getMonth() ||
        (aujourd_hui.getMonth() === dateNaissance.getMonth() &&
         aujourd_hui.getDate() >= dateNaissance.getDate());
    const ageFinal = anniversairePassé ? age : age - 1;

    if (ageFinal < 18) {
        afficherErreur(inputDate, 'Vous devez avoir au moins 18 ans.');
        return false;
    }

    supprimerErreur(inputDate);
    return true;
}

function validerBio() {
    if (inputBio.value.trim().length > 200) {
        afficherErreur(inputBio, `La bio ne doit pas dépasser 200 caractères.`);
        return false;
    }
    supprimerErreur(inputBio);
    return true;
}

// ────────────────────────────────────────
// Validation en live
// ────────────────────────────────────────

inputPhoto.addEventListener('change', validerPhoto);
inputDate.addEventListener('blur',    validerDate);
inputDate.addEventListener('input',   () => supprimerErreur(inputDate));
inputBio.addEventListener('blur',     validerBio);

// ────────────────────────────────────────
// Validation à la soumission
// ────────────────────────────────────────

form.addEventListener('submit', (e) => {
    let valide = true;

    if (!validerPhoto()) valide = false;
    if (!validerDate())  valide = false;
    if (!validerBio())   valide = false;

    if (!valide) e.preventDefault();
});