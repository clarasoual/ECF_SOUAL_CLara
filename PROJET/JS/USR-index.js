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
        if(window.scrollY > 300){
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // -------------------- 2. Vérification simple des champs --------------------
    const form = document.querySelector('.search-section form');
    form.addEventListener('submit', (e) => {
        const departure = document.getElementById('departure').value.trim();
        const destination = document.getElementById('destination').value.trim();
        const date = document.getElementById('date').value;
        const passenger = document.getElementById('passenger').value;

        if(!departure || !destination){
            alert("Veuillez remplir les villes de départ et d'arrivée.");
            e.preventDefault();
            return;
        }

        if(passenger < 1){
            alert("Le nombre de passagers doit être au moins 1.");
            e.preventDefault();
            return;
        }

        if(!date){
            alert("Veuillez sélectionner une date.");
            e.preventDefault();
            return;
        }
    });

    // -------------------- 3. Suggestions de villes --------------------
    const villes = ["Bordeaux","Paris","Lyon","Marseille","Toulouse","Nice","Nantes","Montpellier","Strasbourg","Lille","Rennes","Reims"];
    
    function setupAutocomplete(input){
        let currentFocus;

        input.addEventListener("input", function(){
            let val = this.value;
            closeAllLists();
            if(!val) return false;

            const list = document.createElement("div");
            list.setAttribute("id", this.id + "-autocomplete-list");
            list.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(list);

            villes.forEach(ville => {
                if(ville.substr(0, val.length).toUpperCase() == val.toUpperCase()){
                    const item = document.createElement("div");
                    item.innerHTML = "<strong>" + ville.substr(0, val.length) + "</strong>" + ville.substr(val.length);
                    item.innerHTML += "<input type='hidden' value='" + ville + "'>";
                    item.addEventListener("click", function(){
                        input.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    list.appendChild(item);
                }
            });
        });

        input.addEventListener("keydown", function(e){
            let list = document.getElementById(this.id + "-autocomplete-list");
            if(list) list = list.getElementsByTagName("div");
            if(e.keyCode == 40){
                currentFocus++;
                addActive(list);
            } else if(e.keyCode == 38){
                currentFocus--;
                addActive(list);
            } else if(e.keyCode == 13){
                e.preventDefault();
                if(currentFocus > -1){
                    if(list) list[currentFocus].click();
                }
            }
        });

        function addActive(list){
            if(!list) return false;
            removeActive(list);
            if(currentFocus >= list.length) currentFocus = 0;
            if(currentFocus < 0) currentFocus = list.length - 1;
            list[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(list){
            for(let i = 0; i < list.length; i++){
                list[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt){
            const items = document.getElementsByClassName("autocomplete-items");
            for(let i = 0; i < items.length; i++){
                if(elmnt != items[i] && elmnt != input){
                    items[i].parentNode.removeChild(items[i]);
                }
            }
        }

        document.addEventListener("click", function (e){
            closeAllLists(e.target);
        });
    }

    setupAutocomplete(document.getElementById('departure'));
    setupAutocomplete(document.getElementById('destination'));

});
