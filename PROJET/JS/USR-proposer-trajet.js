document.addEventListener("DOMContentLoaded", () => {

    // --- Préremplir les champs si on vient de la page 2 ---
    const fromStep2 = sessionStorage.getItem('fromStep2') === 'true';
    if(fromStep2){
        const storedDeparture = sessionStorage.getItem('departure') || '';
        const storedArrival = sessionStorage.getItem('arrival') || '';
        const storedDate = sessionStorage.getItem('date') || '';
        const storedTime = sessionStorage.getItem('time') || '';
        const storedVehicle = sessionStorage.getItem('vehicle') || '';
        const storedPlaces = sessionStorage.getItem('places') || '';
        const storedComments = sessionStorage.getItem('comments') || '';
        const storedStops = JSON.parse(sessionStorage.getItem('stops') || '[]');

        if(storedDeparture) document.getElementById('departure').value = storedDeparture;
        if(storedArrival) document.getElementById('arrival').value = storedArrival;
        if(storedDate) document.getElementById('date').value = storedDate;
        if(storedTime) document.getElementById('time').value = storedTime;
        if(storedVehicle) document.getElementById('vehicle-used').value = storedVehicle;
        if(storedPlaces) document.getElementById('places').value = storedPlaces;
        if(storedComments) document.getElementById('commentaire').value = storedComments;

        // Recréer les arrêts supplémentaires
        const firstStop = document.getElementById('step1');
        if(storedStops.length){
            firstStop.value = storedStops[0] || '';
            for(let i = 1; i < storedStops.length; i++){
                const stopNumber = i + 1;
                const stopContainer = document.createElement('div');
                stopContainer.classList.add('stop-container');
                stopContainer.style.marginTop = '10px';
                stopContainer.innerHTML = `
                    <label for="step${stopNumber}">Arrêt n°${stopNumber} (optionnel)</label>
                    <input type="text" id="step${stopNumber}" name="step${stopNumber}" value="${storedStops[i]}">
                    <button type="button" class="remove-stop">Supprimer</button>
                `;
                const lastStop = document.querySelector('.stop-container') || firstStop;
                lastStop.parentNode.insertBefore(stopContainer, lastStop.nextSibling);

                const removeBtn = stopContainer.querySelector('.remove-stop');
                removeBtn.addEventListener('click', () => {
                    stopContainer.remove();
                    const remainingStops = document.querySelectorAll('.stop-container');
                    remainingStops.forEach((c, i) => {
                        const label = c.querySelector('label');
                        const input = c.querySelector('input');
                        label.setAttribute('for', `step${i+2}`);
                        label.textContent = `Arrêt n°${i+2} (optionnel)`;
                        input.id = `step${i+2}`;
                        input.name = `step${i+2}`;
                    });
                });
            }
        }

        // --- Après préremplissage, on remet le drapeau à false ---
        sessionStorage.setItem('fromStep2', 'false');
    }

    // --- Ajouter un nouvel arrêt ---
    const addStopBtn = document.querySelector('.trip-step1 button[type="button"]');
    addStopBtn.addEventListener('click', () => {
        const existingStops = document.querySelectorAll('.stop-container');
        const stopNumber = existingStops.length + 2;
        if(stopNumber > 6){ alert("Vous ne pouvez ajouter que 5 arrêts maximum."); return; }

        const stopContainer = document.createElement('div');
        stopContainer.classList.add('stop-container');
        stopContainer.style.marginTop = '10px';
        stopContainer.innerHTML = `
            <label for="step${stopNumber}">Arrêt n°${stopNumber} (optionnel)</label>
            <input type="text" id="step${stopNumber}" name="step${stopNumber}" placeholder="Ajouter un arrêt">
            <button type="button" class="remove-stop">Supprimer</button>
        `;
        const lastStop = existingStops[existingStops.length - 1] || document.getElementById('step1');
        lastStop.parentNode.insertBefore(stopContainer, lastStop.nextSibling);

        const removeBtn = stopContainer.querySelector('.remove-stop');
        removeBtn.addEventListener('click', () => {
            stopContainer.remove();
            const remainingStops = document.querySelectorAll('.stop-container');
            remainingStops.forEach((c, i) => {
                const label = c.querySelector('label');
                const input = c.querySelector('input');
                label.setAttribute('for', `step${i+2}`);
                label.textContent = `Arrêt n°${i+2} (optionnel)`;
                input.id = `step${i+2}`;
                input.name = `step${i+2}`;
            });
        });
    });

    // --- Submit : enregistrer dans sessionStorage ---
    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        const departure = document.getElementById('departure').value.trim();
        const arrival = document.getElementById('arrival').value.trim();
        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;
        const places = document.getElementById('places').value;
        const vehicle = document.getElementById('vehicle-used').value;
        const comments = document.getElementById('commentaire').value.trim();

        const stopsInputs = document.querySelectorAll('input[id^="step"]');
        const stops = [];
        stopsInputs.forEach(input => {
            if(input.value.trim() !== '') stops.push(input.value.trim());
        });

        // Stockage uniquement pour retour depuis la page 2
        sessionStorage.setItem('departure', departure);
        sessionStorage.setItem('arrival', arrival);
        sessionStorage.setItem('date', date);
        sessionStorage.setItem('time', time);
        sessionStorage.setItem('places', places);
        sessionStorage.setItem('vehicle', vehicle);
        sessionStorage.setItem('comments', comments);
        sessionStorage.setItem('stops', JSON.stringify(stops));

        // --- Validation ---
        if(!departure || !arrival || !date || !time || !places || !vehicle){
            e.preventDefault();
            alert("Merci de remplir tous les champs obligatoires.");
            return;
        }
        const maxPlaces = vehicle === 'Kangoo blanc' ? 6 : 4;
        if(places > maxPlaces){
            e.preventDefault();
            alert(`Le véhicule choisi ne peut transporter que ${maxPlaces} passagers.`);
            return;
        }

        // --- Définir le drapeau pour que la page 2 sache qu'on vient de la page 1 ---
        sessionStorage.setItem('fromStep2', 'true');
    });

});
