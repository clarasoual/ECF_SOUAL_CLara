document.addEventListener("DOMContentLoaded", () => {

    const departure = sessionStorage.getItem('departure') || '';
    const stops = JSON.parse(sessionStorage.getItem('stops') || '[]');
    const arrival = sessionStorage.getItem('arrival') || '';
    const date = sessionStorage.getItem('date') || '';
    const time = sessionStorage.getItem('time') || '';
    const vehicle = sessionStorage.getItem('vehicle') || '';
    const places = sessionStorage.getItem('places') || '';
    const comments = sessionStorage.getItem('comments') || '';

    function formatDate(inputDate) {
        if(!inputDate) return '';
        const d = new Date(inputDate);
        const day = String(d.getDate()).padStart(2,'0');
        const month = String(d.getMonth()+1).padStart(2,'0');
        const year = String(d.getFullYear());
        return `${day}/${month}/${year}`;
    }

    document.getElementById('summary-departure').textContent = departure;
    document.getElementById('summary-stops').textContent = stops.length ? stops.join(', ') : 'Aucun arrêt';
    document.getElementById('summary-arrival').textContent = arrival;
    document.getElementById('summary-date').textContent = formatDate(date);
    document.getElementById('summary-time').textContent = time;
    document.getElementById('summary-vehicle').textContent = vehicle;
    document.getElementById('summary-places').textContent = places;
    document.getElementById('summary-comments').textContent = comments;

    // --- Cliquer sur modifier : définir le drapeau pour revenir sur page 1 ---
    const editLink = document.getElementById('edit-link');
    editLink.addEventListener('click', () => {
        sessionStorage.setItem('fromStep2', 'true');
    });

    const submitBtn = document.querySelector('.btn-submit');
    if(submitBtn){
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();

            const confirmed = confirm(`Confirmez-vous ce trajet ?\nDépart : ${departure}\nArrêts : ${stops.join(', ') || 'Aucun'}\nArrivée : ${arrival}\nDate : ${formatDate(date)}\nHeure : ${time}\nVéhicule : ${vehicle}\nPlaces : ${places}\nCommentaires : ${comments}`);
            
            if(confirmed){
                alert("Trajet confirmé ! Vous gagnerez vos crédits après le trajet.");
                sessionStorage.clear(); // vider tout après confirmation
                submitBtn.closest('form').submit();
            }
        });
    }
});
