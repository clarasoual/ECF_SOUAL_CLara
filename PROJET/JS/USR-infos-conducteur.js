const vehiclesContainer = document.getElementById('vehicles-container');

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

function afficherErreurSelect(select, message) {
    supprimerErreurSelect(select);
    select.classList.add('input-error');
    const msg = document.createElement('span');
    msg.className   = 'erreur-champ';
    msg.textContent = message;
    msg.style.color     = '#e74c3c';
    msg.style.fontSize  = '0.8rem';
    msg.style.display   = 'block';
    msg.style.marginTop = '4px';
    select.parentNode.insertBefore(msg, select.nextSibling);
}

function supprimerErreurSelect(select) {
    const ancienne = select.parentNode.querySelector('.erreur-champ');
    if (ancienne) ancienne.remove();
    select.classList.remove('input-error');
}

function afficherErreurRadio(container, message) {
    supprimerErreurRadio(container);
    const msg = document.createElement('span');
    msg.className   = 'erreur-champ';
    msg.textContent = message;
    msg.style.color     = '#e74c3c';
    msg.style.fontSize  = '0.8rem';
    msg.style.display   = 'block';
    msg.style.marginTop = '4px';
    container.appendChild(msg);
}

function supprimerErreurRadio(container) {
    const ancienne = container.querySelector('.erreur-champ');
    if (ancienne) ancienne.remove();
}

// ────────────────────────────────────────
// Filtrer les modèles selon la marque
// ────────────────────────────────────────

function filterModels(form) {
    const brand = form.querySelector('.brand').value;
    const model = form.querySelector('.model');
    Array.from(model.options).forEach(opt => {
        opt.hidden = opt.dataset.marque && opt.dataset.marque !== brand;
    });
    if (model.selectedOptions.length && model.selectedOptions[0].hidden) model.value = '';
}

// ────────────────────────────────────────
// Validation plaque
// ────────────────────────────────────────

function validatePlate(plate) {
    return /^[A-Z]{2}-\d{3}-[A-Z]{2}$/.test(plate);
}

// ────────────────────────────────────────
// Carte récap véhicule
// ────────────────────────────────────────

function createVehicleCard(data, index) {
    const card = document.createElement('div');
    card.className = 'vehicle-card';
    card.dataset.index = index;
    card.innerHTML = `
        <h2>Véhicule numéro ${index + 1}</h2>
        <p><strong>Plaque :</strong> ${data.plate}</p>
        <p><strong>Date :</strong> ${data.date}</p>
        <p><strong>Marque :</strong> ${data.brand}</p>
        <p><strong>Modèle :</strong> ${data.model}</p>
        <p><strong>Couleur :</strong> ${data.color}</p>
        <p><strong>Places :</strong> ${data.seats}</p>
        <p><strong>Animaux :</strong> ${data.pets || 'Non renseigné'}</p>
        <p><strong>Fumeur :</strong> ${data.smoking || 'Non renseigné'}</p>
        <p><strong>Musique :</strong> ${data.music || 'Non renseigné'}</p>
    `;
    return card;
}

function showVehicleCard(container, data, index) {
    container.innerHTML = '';
    container.appendChild(createVehicleCard(data, index));
}

// ────────────────────────────────────────
// Enregistrer le formulaire
// ────────────────────────────────────────

function saveForm(e) {
    e.preventDefault();
    const form = e.target;
    const containerDiv = form.closest('.driver-info-container');
    const index = Array.from(vehiclesContainer.children).indexOf(containerDiv);

    const inputPlate   = form.querySelector('.plate');
    const inputDate    = form.querySelector('.date');
    const selectBrand  = form.querySelector('.brand');
    const selectModel  = form.querySelector('.model');
    const selectColor  = form.querySelector('.color');
    const inputSeats   = form.querySelector('.seats');
    const selectMusic  = form.querySelector('.music');
    const petsContainer    = form.querySelector('.pets-container');
    const smokingContainer = form.querySelector('.smoking-container');

    // Réinitialiser les erreurs
    [inputPlate, inputDate, inputSeats].forEach(supprimerErreur);
    [selectBrand, selectModel, selectColor, selectMusic].forEach(supprimerErreurSelect);
    if (petsContainer)    supprimerErreurRadio(petsContainer);
    if (smokingContainer) supprimerErreurRadio(smokingContainer);

    let valide = true;

    // Plaque
    const plate = inputPlate.value.trim().toUpperCase();
    if (!plate) {
        afficherErreur(inputPlate, 'La plaque d\'immatriculation est obligatoire.');
        valide = false;
    } else if (!validatePlate(plate)) {
        afficherErreur(inputPlate, 'Format invalide — ex : AB-123-CD');
        valide = false;
    } else {
        supprimerErreur(inputPlate);
        inputPlate.value = plate; // forcer majuscules
    }

    // Date
    if (!inputDate.value) {
        afficherErreur(inputDate, 'La date de première immatriculation est obligatoire.');
        valide = false;
    } else {
        const dateImmat  = new Date(inputDate.value);
        const aujourd_hui = new Date();
        if (dateImmat > aujourd_hui) {
            afficherErreur(inputDate, 'La date ne peut pas être dans le futur.');
            valide = false;
        } else {
            supprimerErreur(inputDate);
        }
    }

    // Marque
    if (!selectBrand.value) {
        afficherErreurSelect(selectBrand, 'Veuillez choisir une marque.');
        valide = false;
    } else supprimerErreurSelect(selectBrand);

    // Modèle
    if (!selectModel.value) {
        afficherErreurSelect(selectModel, 'Veuillez choisir un modèle.');
        valide = false;
    } else supprimerErreurSelect(selectModel);

    // Couleur
    if (!selectColor.value) {
        afficherErreurSelect(selectColor, 'Veuillez choisir une couleur.');
        valide = false;
    } else supprimerErreurSelect(selectColor);

    // Places
    const seats = parseInt(inputSeats.value, 10);
    if (!inputSeats.value || isNaN(seats)) {
        afficherErreur(inputSeats, 'Le nombre de places est obligatoire.');
        valide = false;
    } else if (seats < 1 || seats > 8) {
        afficherErreur(inputSeats, 'Le nombre de places doit être entre 1 et 8.');
        valide = false;
    } else supprimerErreur(inputSeats);

    // Animaux
    const petsChecked = form.querySelector(`input[name="pets${index}"]:checked`);
    if (petsContainer && !petsChecked) {
        afficherErreurRadio(petsContainer, 'Veuillez indiquer si les animaux sont acceptés.');
        valide = false;
    }

    // Fumeur
    const smokingChecked = form.querySelector(`input[name="smoking${index}"]:checked`);
    if (smokingContainer && !smokingChecked) {
        afficherErreurRadio(smokingContainer, 'Veuillez indiquer si le véhicule est fumeur.');
        valide = false;
    }

    // Musique
    if (!selectMusic.value) {
        afficherErreurSelect(selectMusic, 'Veuillez choisir une préférence musicale.');
        valide = false;
    } else supprimerErreurSelect(selectMusic);

    if (!valide) return;

    // Données valides
    const data = {
        plate   : plate,
        date    : inputDate.value,
        brand   : selectBrand.value,
        model   : selectModel.value,
        color   : selectColor.value,
        seats   : inputSeats.value,
        pets    : petsChecked?.value || '',
        smoking : smokingChecked?.value || '',
        music   : selectMusic.value
    };

    // Sauvegarde localStorage
    const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
    dataList[index] = data;
    localStorage.setItem('driverInfoList', JSON.stringify(dataList));

    // Envoi en BDD via fetch
    const formData = new FormData(form);
    fetch(form.action, { method: 'POST', body: formData })
        .then(res => res.text())
        .then(() => {
            showVehicleCard(containerDiv, data, index);
            // Afficher écran succès + confettis
            const screen = document.getElementById('success-screen');
            if (screen) {
                screen.style.display = 'flex';
                launchConfetti(150);
                setTimeout(() => { window.location.href = '../UTILISATEUR/USR-infos-perso.php'; }, 3000);
            }
        })
        .catch(err => {
            console.error('Erreur fetch :', err);
            const errMsg = document.createElement('p');
            errMsg.textContent = '❌ Une erreur est survenue, réessayez.';
            errMsg.style.color = '#e74c3c';
            form.appendChild(errMsg);
        });
}

// ────────────────────────────────────────
// Préremplir formulaire
// ────────────────────────────────────────

function prefillForm(form, index) {
    const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
    const data = dataList[index] || {};
    form.querySelector('.plate').value  = data.plate || '';
    form.querySelector('.date').value   = data.date  || '';
    form.querySelector('.brand').value  = data.brand || '';
    filterModels(form);
    form.querySelector('.model').value  = data.model || '';
    form.querySelector('.color').value  = data.color || '';
    form.querySelector('.seats').value  = data.seats || '';
    form.querySelector('.music').value  = data.music || '';
    if (data.pets)    { const r = form.querySelector(`input[name="pets${index}"][value="${data.pets}"]`);    if (r) r.checked = true; }
    if (data.smoking) { const r = form.querySelector(`input[name="smoking${index}"][value="${data.smoking}"]`); if (r) r.checked = true; }
}

// ────────────────────────────────────────
// Setup formulaire
// ────────────────────────────────────────

function setupForm(form, index) {
    form.querySelectorAll('input[type="radio"]').forEach(input => {
        if (input.name === 'pets')    input.name = `pets${index}`;
        if (input.name === 'smoking') input.name = `smoking${index}`;
    });

    form.querySelector('.brand').addEventListener('change', () => filterModels(form));
    form.addEventListener('submit', saveForm);
    prefillForm(form, index);
}

// ────────────────────────────────────────
// Initialisation
// ────────────────────────────────────────

document.querySelectorAll('.driver-info-container form').forEach((form, i) => setupForm(form, i));

// ────────────────────────────────────────
// Ajouter un véhicule
// ────────────────────────────────────────

document.getElementById('addVehicleBtn').addEventListener('click', () => {
    const template = document.querySelector('.driver-info-container');
    const clone    = template.cloneNode(true);
    const index    = vehiclesContainer.children.length;

    clone.querySelectorAll('input, select').forEach(el => {
        if (el.type === 'radio') el.checked = false;
        else el.value = '';
    });
    clone.querySelectorAll('.erreur-champ').forEach(e => e.remove());
    clone.querySelectorAll('.input-error').forEach(e => e.classList.remove('input-error'));

    clone.querySelector('h2').textContent = `Véhicule numéro ${index + 1}`;
    vehiclesContainer.appendChild(clone);

    const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
    dataList.push({});
    localStorage.setItem('driverInfoList', JSON.stringify(dataList));

    setupForm(clone.querySelector('form'), index);
});

// ────────────────────────────────────────
// Supprimer un véhicule — sans prompt()
// ────────────────────────────────────────

document.getElementById('removeVehicleBtn').addEventListener('click', () => {
    const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
    if (dataList.length === 0) {
        afficherMessageFlash('Aucun véhicule à supprimer.', 'error');
        return;
    }
    // Supprimer le dernier véhicule
    vehiclesContainer.lastElementChild.remove();
    dataList.pop();
    localStorage.setItem('driverInfoList', JSON.stringify(dataList));

    Array.from(vehiclesContainer.children).forEach((child, i) => {
        const h2 = child.querySelector('h2');
        if (h2) h2.textContent = `Véhicule numéro ${i + 1}`;
        const form = child.querySelector('form');
        if (form) setupForm(form, i);
    });

    afficherMessageFlash('Dernier véhicule supprimé.', 'success');
});

// ────────────────────────────────────────
// Message flash (remplace alert/prompt)
// ────────────────────────────────────────

function afficherMessageFlash(message, type = 'success') {
    const ancien = document.getElementById('flash-message');
    if (ancien) ancien.remove();

    const flash = document.createElement('div');
    flash.id = 'flash-message';
    flash.textContent = message;
    flash.style.position   = 'fixed';
    flash.style.bottom     = '20px';
    flash.style.left       = '50%';
    flash.style.transform  = 'translateX(-50%)';
    flash.style.background = type === 'success' ? '#4CAF50' : '#e74c3c';
    flash.style.color      = '#fff';
    flash.style.padding    = '0.8rem 1.5rem';
    flash.style.borderRadius = '8px';
    flash.style.fontSize   = '0.9rem';
    flash.style.zIndex     = 9999;
    flash.style.transition = 'opacity 0.5s';
    document.body.appendChild(flash);
    setTimeout(() => { flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }, 3000);
}