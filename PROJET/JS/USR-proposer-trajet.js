document.addEventListener("DOMContentLoaded", () => {

    const addStopBtn = document.querySelector('.trip-step1 button[type="button"]');

    addStopBtn.addEventListener('click', () => {

        const existingStops = document.querySelectorAll('.stop-container');
        const stopNumber = existingStops.length + 1;

        if(stopNumber > 5){
            alert("Vous ne pouvez ajouter que 5 arrêts maximum.");
            return;
        }

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

            // Renumérotation automatique
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

    // === Validation et résumé du formulaire ===
    const submitBtn = document.querySelector('.btn-submit');
    if(submitBtn) {
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();

            const departure = document.getElementById('departure').value.trim();
            const arrival = document.getElementById('arrival').value.trim();
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const places = parseInt(document.getElementById('places').value);
            const vehicle = document.getElementById('vehicle-used').value;

            if(!departure || !arrival || !date || !time || !places || !vehicle){
                alert("Merci de remplir tous les champs obligatoires.");
                return;
            }

            const maxPlaces = vehicle === 'veh2' ? 6 : 4;
            if(places > maxPlaces){
                alert(`Le véhicule choisi ne peut transporter que ${maxPlaces} passagers.`);
                return;
            }

            // Récupérer tous les arrêts
            const stops = [];
            const allStops = document.querySelectorAll('input[id^="step"]');
            allStops.forEach(input => {
                if(input.value.trim() !== '') stops.push(input.value.trim());
            });

            // Résumé
            const summary = `Résumé du trajet :\nDépart : ${departure}\nArrêts : ${stops.join(', ') || 'aucun'}\nArrivée : ${arrival}\nDate : ${date}\nHeure : ${time}\nVéhicule : ${vehicle}\nPlaces : ${places}`;
            if(confirm(summary + "\n\nConfirmez-vous ce trajet ?")){
                window.location.href = "proposer_trajet2.php";
            }
        });
    }

});
