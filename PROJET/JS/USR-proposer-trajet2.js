document.addEventListener("DOMContentLoaded", () => {

    // === Récupérer les valeurs depuis localStorage ===
    const departure = localStorage.getItem('departure') || '';
    const stops = JSON.parse(localStorage.getItem('stops') || '[]');
    const arrival = localStorage.getItem('arrival') || '';
    const date = localStorage.getItem('date') || '';
    const time = localStorage.getItem('time') || '';
    const vehicle = localStorage.getItem('vehicle') || '';
    const places = localStorage.getItem('places') || '';
    const comments = localStorage.getItem('comments') || '';

    // === Formater la date pour l'affichage (jj/mm/aaaa) ===
    function formatDate(inputDate) {
        if(!inputDate) return '';
        const d = new Date(inputDate);
        const day = String(d.getDate()).padStart(2,'0');
        const month = String(d.getMonth()+1).padStart(2,'0');
        const year = String(d.getFullYear());
        return `${day}/${month}/${year}`;
    }

    // === Afficher les valeurs dans le tableau ===
    document.getElementById('summary-departure').textContent = departure;
    document.getElementById('summary-stops').textContent = stops.length ? stops.join(', ') : 'Aucun arrêt';
    document.getElementById('summary-arrival').textContent = arrival;
    document.getElementById('summary-date').textContent = formatDate(date);
    document.getElementById('summary-time').textContent = time;
    document.getElementById('summary-vehicle').textContent = vehicle;
    document.getElementById('summary-places').textContent = places;
    document.getElementById('summary-comments').textContent = comments;

    // === Ajouter alerte / confirmation lors de la soumission ===
    const submitBtn = document.querySelector('.btn-submit');
    if(submitBtn){
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();

            const confirmed = confirm(`Confirmez-vous ce trajet ?\nDépart : ${departure}\nArrêts : ${stops.join(', ') || 'Aucun'}\nArrivée : ${arrival}\nDate : ${formatDate(date)}\nHeure : ${time}\nVéhicule : ${vehicle}\nPlaces : ${places}\nCommentaires : ${comments}`);
            
            if(confirmed){
                // Pop-up de confirmation
                alert("Trajet confirmé ! Vous gagnerez vos crédits après le trajet.");
                // Soumettre le formulaire après confirmation
                submitBtn.closest('form').submit();
            }
        });
    }

});
