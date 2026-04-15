// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {

    // -------------------- 1. Bouton remonter en haut --------------------
    const backToTop = document.createElement('button');
    backToTop.id = 'backToTop';
    backToTop.textContent = '↑';
    document.body.appendChild(backToTop);

    backToTop.style.position = 'fixed';
    backToTop.style.bottom = '20px';
    backToTop.style.right = '20px';
    backToTop.style.padding = '10px 15px';
    backToTop.style.border = 'none';
    backToTop.style.borderRadius = '50%';
    backToTop.style.background = '#a8d5ba';
    backToTop.style.color = '#fff';
    backToTop.style.fontSize = '1.2rem';
    backToTop.style.cursor = 'pointer';
    backToTop.style.boxShadow = '0 2px 8px rgba(0,0,0,0.3)';
    backToTop.style.display = 'none';
    backToTop.style.zIndex = 1000;

    window.addEventListener('scroll', () => {
        backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // -------------------- 2. Validation du formulaire de recherche --------------------

    function estSaine(valeur) {
        return /^[a-zA-ZÀ-ÿ0-9\s\-',.]+$/.test(valeur);
    }

    function dateValide(dateStr) {
        if (!dateStr) return false;
        const aujourd_hui = new Date();
        aujourd_hui.setHours(0, 0, 0, 0);
        const dateChoisie = new Date(dateStr);
        return dateChoisie >= aujourd_hui;
    }

    function afficherErreur(input, message) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();

        input.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className = 'erreur-champ';
        msg.textContent = message;
        msg.style.color = '#e74c3c';
        msg.style.fontSize = '0.8rem';
        msg.style.display = 'block';
        msg.style.marginTop = '4px';
        input.parentNode.appendChild(msg);
    }

    function supprimerErreur(input) {
        const ancienne = input.parentNode.querySelector('.erreur-champ');
        if (ancienne) ancienne.remove();
        input.classList.remove('input-error');
    }

    const form = document.querySelector('.search-section form');

    if (form) {
        const inputDepart      = document.getElementById('departure');
        const inputDestination = document.getElementById('destination');
        const inputDate        = document.getElementById('date');
        const inputPassager    = document.getElementById('passenger');

        [inputDepart, inputDestination, inputDate, inputPassager].forEach(input => {
            input.addEventListener('input', () => supprimerErreur(input));
        });

        form.addEventListener('submit', (e) => {
            // Réinitialisation de toutes les erreurs avant de revalider
            [inputDepart, inputDestination, inputDate, inputPassager].forEach(supprimerErreur);

            let valide = true;

            const depart      = inputDepart.value.trim();
            const destination = inputDestination.value.trim();
            const date        = inputDate.value;
            const passager    = parseInt(inputPassager.value, 10);

            // --- Départ ---
            if (!depart) {
                afficherErreur(inputDepart, 'Veuillez indiquer une ville de départ.');
                valide = false;
            } else if (!estSaine(depart)) {
                afficherErreur(inputDepart, 'La ville de départ contient des caractères non autorisés.');
                valide = false;
            }

            // --- Destination ---
            if (!destination) {
                afficherErreur(inputDestination, 'Veuillez indiquer une ville d\'arrivée.');
                valide = false;
            } else if (!estSaine(destination)) {
                afficherErreur(inputDestination, 'La ville d\'arrivée contient des caractères non autorisés.');
                valide = false;
            } else if (destination.toLowerCase() === depart.toLowerCase()) {
                afficherErreur(inputDestination, 'La ville d\'arrivée doit être différente du départ.');
                valide = false;
            }

            // --- Date ---
            if (!date) {
                afficherErreur(inputDate, 'Veuillez sélectionner une date.');
                valide = false;
            } else if (!dateValide(date)) {
                afficherErreur(inputDate, 'La date ne peut pas être dans le passé.');
                valide = false;
            }

            // --- Passagers ---
            if (isNaN(passager) || passager < 1) {
                afficherErreur(inputPassager, 'Le nombre de passagers doit être au moins 1.');
                valide = false;
            } else if (passager > 8) {
                afficherErreur(inputPassager, 'Le nombre de passagers ne peut pas dépasser 8.');
                valide = false;
            }

            if (!valide) {
                e.preventDefault();
            }
        });
    }

    // -------------------- 3. Suggestions de villes --------------------
    const villes = ["Bordeaux","Paris","Lyon","Marseille","Toulouse","Nice","Nantes","Montpellier","Strasbourg","Lille","Rennes","Reims"];

    function setupAutocomplete(input) {
        let currentFocus = -1;

        input.addEventListener("input", function () {
            const val = this.value;
            closeAllLists();
            if (!val) return;

            const list = document.createElement("div");
            list.setAttribute("id", this.id + "-autocomplete-list");
            list.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(list);

            villes.forEach(ville => {
                if (ville.substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                    const item = document.createElement("div");
                    item.innerHTML = "<strong>" + ville.substr(0, val.length) + "</strong>" + ville.substr(val.length);
                    item.innerHTML += "<input type='hidden' value='" + ville + "'>";
                    item.addEventListener("click", function () {
                        input.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    list.appendChild(item);
                }
            });
        });

        input.addEventListener("keydown", function (e) {
            let list = document.getElementById(this.id + "-autocomplete-list");
            if (list) list = list.getElementsByTagName("div");
            if (e.keyCode === 40) {
                currentFocus++;
                addActive(list);
            } else if (e.keyCode === 38) {
                currentFocus--;
                addActive(list);
            } else if (e.keyCode === 13) {
                e.preventDefault();
                if (currentFocus > -1 && list) list[currentFocus].click();
            }
        });

        function addActive(list) {
            if (!list) return;
            removeActive(list);
            if (currentFocus >= list.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = list.length - 1;
            list[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(list) {
            for (let i = 0; i < list.length; i++) {
                list[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            const items = document.getElementsByClassName("autocomplete-items");
            for (let i = 0; i < items.length; i++) {
                if (elmnt !== items[i] && elmnt !== input) {
                    items[i].parentNode.removeChild(items[i]);
                }
            }
        }

        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    setupAutocomplete(document.getElementById('departure'));
    setupAutocomplete(document.getElementById('destination'));

});