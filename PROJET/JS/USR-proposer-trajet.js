document.addEventListener("DOMContentLoaded", () => {

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

    // ────────────────────────────────────────
    // Règles de validation
    // ────────────────────────────────────────

    function estSaine(valeur) {
        return /^[a-zA-ZÀ-ÿ0-9\s\-',.]+$/.test(valeur);
    }

    function validerDepart() {
        const val = inputDepart.value.trim();
        if (!val) { afficherErreur(inputDepart, 'L\'adresse de départ est obligatoire.'); return false; }
        if (!estSaine(val)) { afficherErreur(inputDepart, 'L\'adresse de départ contient des caractères non autorisés.'); return false; }
        supprimerErreur(inputDepart);
        return true;
    }

    function validerArrivee() {
        const val = inputArrivee.value.trim();
        if (!val) { afficherErreur(inputArrivee, 'L\'adresse d\'arrivée est obligatoire.'); return false; }
        if (!estSaine(val)) { afficherErreur(inputArrivee, 'L\'adresse d\'arrivée contient des caractères non autorisés.'); return false; }
        if (val.toLowerCase() === inputDepart.value.trim().toLowerCase()) { afficherErreur(inputArrivee, 'L\'adresse d\'arrivée doit être différente du départ.'); return false; }
        supprimerErreur(inputArrivee);
        return true;
    }

    function validerVehicule() {
        if (!selectVehicule.value) { afficherErreurSelect(selectVehicule, 'Veuillez sélectionner un véhicule.'); return false; }
        supprimerErreurSelect(selectVehicule);
        return true;
    }

    function validerDate() {
        const val = inputDate.value;
        if (!val) { afficherErreur(inputDate, 'La date de départ est obligatoire.'); return false; }
        const aujourd_hui = new Date();
        aujourd_hui.setHours(0, 0, 0, 0);
        if (new Date(val) < aujourd_hui) { afficherErreur(inputDate, 'La date ne peut pas être dans le passé.'); return false; }
        supprimerErreur(inputDate);
        return true;
    }

    function validerHeure() {
        const valDate  = inputDate.value;
        const valHeure = inputTime.value;
        if (!valHeure) { afficherErreur(inputTime, 'L\'heure de départ est obligatoire.'); return false; }
        if (valDate) {
            const maintenant  = new Date();
            const dateChoisie = new Date(valDate);
            const aujourd_hui = new Date();
            aujourd_hui.setHours(0, 0, 0, 0);
            dateChoisie.setHours(0, 0, 0, 0);
            if (dateChoisie.getTime() === aujourd_hui.getTime()) {
                const [h, m] = valHeure.split(':').map(Number);
                const heureChoisie = new Date();
                heureChoisie.setHours(h, m, 0, 0);
                if (heureChoisie <= maintenant) { afficherErreur(inputTime, 'L\'heure de départ ne peut pas être dans le passé.'); return false; }
            }
        }
        supprimerErreur(inputTime);
        return true;
    }

    function validerHeureArrivee() {
        const valHeure        = inputTime.value;
        const valHeureArrivee = inputTimeArrivee.value;
        if (!valHeureArrivee) { afficherErreur(inputTimeArrivee, 'L\'heure d\'arrivée est obligatoire.'); return false; }
        if (valHeure && valHeureArrivee <= valHeure) {
            afficherErreur(inputTimeArrivee, 'L\'heure d\'arrivée doit être après l\'heure de départ.');
            return false;
        }
        supprimerErreur(inputTimeArrivee);
        return true;
    }

    function validerPlaces() {
        const val = parseInt(inputPlaces.value, 10);
        if (!inputPlaces.value || isNaN(val)) { afficherErreur(inputPlaces, 'Le nombre de places est obligatoire.'); return false; }
        if (val < 1) { afficherErreur(inputPlaces, 'Il faut au moins 1 place disponible.'); return false; }
        if (val > 8) { afficherErreur(inputPlaces, 'Le nombre de places ne peut pas dépasser 8.'); return false; }
        supprimerErreur(inputPlaces);
        return true;
    }

    function validerPrix() {
        const val = parseInt(inputPrix.value, 10);
        if (!inputPrix.value || isNaN(val)) { afficherErreur(inputPrix, 'Le prix est obligatoire.'); return false; }
        if (val < 2) { afficherErreur(inputPrix, 'Le prix minimum est de 2 crédits.'); return false; }
        if (val > 20) { afficherErreur(inputPrix, 'Le prix maximum est de 20 crédits.'); return false; }
        supprimerErreur(inputPrix);
        return true;
    }

    function validerCommentaire() {
        const val = inputCommentaire.value.trim();
        if (!val) { afficherErreur(inputCommentaire, 'Le point de rendez-vous est obligatoire.'); return false; }
        supprimerErreur(inputCommentaire);
        return true;
    }

    // ────────────────────────────────────────
    // Références champs
    // ────────────────────────────────────────

    const inputDepart       = document.getElementById('departure');
    const inputArrivee      = document.getElementById('arrival');
    const selectVehicule    = document.getElementById('vehicle-used');
    const inputDate         = document.getElementById('date');
    const inputTime         = document.getElementById('time');
    const inputTimeArrivee  = document.getElementById('time_arrivee');
    const inputPlaces       = document.getElementById('places');
    const inputPrix         = document.getElementById('prix');
    const inputCommentaire  = document.getElementById('commentaire');

    // ────────────────────────────────────────
    // Validation en live
    // ────────────────────────────────────────

    inputDepart.addEventListener('blur',  validerDepart);
    inputDepart.addEventListener('input', () => supprimerErreur(inputDepart));

    inputArrivee.addEventListener('blur',  validerArrivee);
    inputArrivee.addEventListener('input', () => supprimerErreur(inputArrivee));

    selectVehicule.addEventListener('change', validerVehicule);

    inputDate.addEventListener('blur',   validerDate);
    inputDate.addEventListener('change', () => { validerDate(); if (inputTime.value) validerHeure(); });

    inputTime.addEventListener('blur',  validerHeure);
    inputTime.addEventListener('input', () => supprimerErreur(inputTime));

    inputTimeArrivee.addEventListener('blur',  validerHeureArrivee);
    inputTimeArrivee.addEventListener('input', () => supprimerErreur(inputTimeArrivee));

    inputPlaces.addEventListener('blur',  validerPlaces);
    inputPlaces.addEventListener('input', () => supprimerErreur(inputPlaces));

    inputPrix.addEventListener('blur',  validerPrix);
    inputPrix.addEventListener('input', () => {
        supprimerErreur(inputPrix);
        const prix  = parseInt(inputPrix.value) || 0;
        const gains = Math.max(0, prix - 2);
        document.getElementById('gains-calcul').textContent = gains;
    });

    inputCommentaire.addEventListener('blur',  validerCommentaire);
    inputCommentaire.addEventListener('input', () => supprimerErreur(inputCommentaire));

    // ────────────────────────────────────────
    // SessionStorage — retour depuis étape 2
    // ────────────────────────────────────────

    const urlParams = new URLSearchParams(window.location.search);
    const isNew = urlParams.get('new') === '1';

    if (isNew) {
        sessionStorage.clear();
        sessionStorage.setItem('fromStep2', 'false');
    }

    const fromStep2 = sessionStorage.getItem('fromStep2') === 'true';
    if (fromStep2) {
        const storedDeparture    = sessionStorage.getItem('departure')    || '';
        const storedArrival      = sessionStorage.getItem('arrival')      || '';
        const storedDate         = sessionStorage.getItem('date')         || '';
        const storedTime         = sessionStorage.getItem('time')         || '';
        const storedTimeArrivee  = sessionStorage.getItem('time_arrivee') || '';
        const storedVehicle      = sessionStorage.getItem('vehicle')      || '';
        const storedPlaces       = sessionStorage.getItem('places')       || '';
        const storedComments     = sessionStorage.getItem('comments')     || '';
        const storedStops        = JSON.parse(sessionStorage.getItem('stops') || '[]');

        if (storedDeparture)   inputDepart.value        = storedDeparture;
        if (storedArrival)     inputArrivee.value       = storedArrival;
        if (storedDate)        inputDate.value          = storedDate;
        if (storedTime)        inputTime.value          = storedTime;
        if (storedTimeArrivee) inputTimeArrivee.value   = storedTimeArrivee;
        if (storedVehicle)     selectVehicule.value     = storedVehicle;
        if (storedPlaces)      inputPlaces.value        = storedPlaces;
        if (storedComments)    inputCommentaire.value   = storedComments;

        const firstStop = document.getElementById('step1');
        if (storedStops.length) {
            firstStop.value = storedStops[0] || '';
            for (let i = 1; i < storedStops.length; i++) {
                const stopNumber    = i + 1;
                const stopContainer = document.createElement('div');
                stopContainer.classList.add('stop-container');
                stopContainer.style.marginTop = '10px';
                stopContainer.innerHTML = `
                    <label for="step${stopNumber}">Arrêt n°${stopNumber} (optionnel)</label>
                    <input type="text" id="step${stopNumber}" name="step${stopNumber}" value="${storedStops[i]}">
                    <button type="button" class="remove-stop">Supprimer</button>
                `;
                firstStop.parentNode.appendChild(stopContainer);
                stopContainer.querySelector('.remove-stop').addEventListener('click', () => {
                    stopContainer.remove();
                    renumerotterArrets();
                });
            }
        }

        sessionStorage.setItem('fromStep2', 'false');
    }

    // ────────────────────────────────────────
    // Ajouter / supprimer un arrêt
    // ────────────────────────────────────────

    function renumerotterArrets() {
        document.querySelectorAll('.stop-container').forEach((c, i) => {
            const label = c.querySelector('label');
            const input = c.querySelector('input');
            if (label) { label.setAttribute('for', `step${i+2}`); label.textContent = `Arrêt n°${i+2} (optionnel)`; }
            if (input) { input.id = `step${i+2}`; input.name = `step${i+2}`; }
        });
    }

    document.getElementById('add-stop-btn').addEventListener('click', () => {
        const existingStops = document.querySelectorAll('.stop-container');
        const stopNumber    = existingStops.length + 2;
        if (stopNumber > 6) { alert("Vous ne pouvez ajouter que 5 arrêts maximum."); return; }
        const stopContainer = document.createElement('div');
        stopContainer.classList.add('stop-container');
        stopContainer.style.marginTop = '10px';
        stopContainer.innerHTML = `
            <label for="step${stopNumber}">Arrêt n°${stopNumber} (optionnel)</label>
            <input type="text" id="step${stopNumber}" name="step${stopNumber}" placeholder="Ajouter un arrêt">
            <button type="button" class="remove-stop">Supprimer</button>
        `;
        const lastStop = existingStops[existingStops.length - 1] || document.getElementById('step1').parentNode;
        lastStop.parentNode.insertBefore(stopContainer, lastStop.nextSibling);
        stopContainer.querySelector('.remove-stop').addEventListener('click', () => {
            stopContainer.remove();
            renumerotterArrets();
        });
    });

    // ────────────────────────────────────────
    // Soumission
    // ────────────────────────────────────────

    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        [inputDepart, inputArrivee, inputDate, inputTime, inputTimeArrivee, inputPlaces, inputPrix, inputCommentaire].forEach(supprimerErreur);
        supprimerErreurSelect(selectVehicule);

        let valide = true;
        if (!validerDepart())       valide = false;
        if (!validerArrivee())      valide = false;
        if (!validerVehicule())     valide = false;
        if (!validerDate())         valide = false;
        if (!validerHeure())        valide = false;
        if (!validerHeureArrivee()) valide = false;
        if (!validerPlaces())       valide = false;
        if (!validerPrix())         valide = false;
        if (!validerCommentaire())  valide = false;

        if (!valide) return;

        const stopsInputs = document.querySelectorAll('input[id^="step"]');
        const stops = [];
        stopsInputs.forEach(input => { if (input.value.trim()) stops.push(input.value.trim()); });

        sessionStorage.setItem('departure',    inputDepart.value.trim());
        sessionStorage.setItem('arrival',      inputArrivee.value.trim());
        sessionStorage.setItem('date',         inputDate.value);
        sessionStorage.setItem('time',         inputTime.value);
        sessionStorage.setItem('time_arrivee', inputTimeArrivee.value);
        sessionStorage.setItem('places',       inputPlaces.value);
        sessionStorage.setItem('vehicle',      selectVehicule.value);
        sessionStorage.setItem('comments',     inputCommentaire.value.trim());
        sessionStorage.setItem('stops',        JSON.stringify(stops));
        sessionStorage.setItem('fromStep2',    'true');

        form.submit();
    });

});