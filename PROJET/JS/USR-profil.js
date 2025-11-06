/* USR profil Finn */

document.addEventListener("DOMContentLoaded", () => {
    const avisLink = document.querySelector('.driver-profile-section a[href="#"]');
    const driverSection = document.querySelector('.driver-profile-section');

    // === Ouvrir / fermer les avis en accordéon ===
    if (avisLink && driverSection) {
        avisLink.addEventListener("click", (e) => {
            e.preventDefault();

            // Vérifie si l'accordéon existe déjà
            let existingAccordion = document.querySelector('.avis-accordeon');
            if (existingAccordion) {
                existingAccordion.remove(); // referme si déjà ouvert
                return;
            }

            // Création du conteneur accordéon
            const accordeon = document.createElement("div");
            accordeon.classList.add("avis-accordeon");

            accordeon.innerHTML = `
                <h3>Commentaires et notes</h3>

                <div class="avis-item">
                    <button class="avis-titre">Nino — 5 ★</button>
                    <div class="avis-contenu">
                        <p>Super trajet, très sympa !</p>
                        <div class="stars" data-note="5"></div>
                    </div>
                </div>

                <div class="avis-item">
                    <button class="avis-titre">Nina — 4 ★</button>
                    <div class="avis-contenu">
                        <p>Très ponctuel.</p>
                        <div class="stars" data-note="4"></div>
                    </div>
                </div>

                <div class="avis-item">
                    <button class="avis-titre">Théo — 5 ★</button>
                    <div class="avis-contenu">
                        <p>Bonne ambiance !</p>
                        <div class="stars" data-note="5"></div>
                    </div>
                </div>
            `;

            driverSection.insertAdjacentElement("afterend", accordeon);
            remplirEtoiles();
            activerAccordeon();
        });
    }

    // === Remplir les étoiles ===
    function remplirEtoiles() {
        const starBlocks = document.querySelectorAll(".stars");
        starBlocks.forEach(block => {
            const note = parseInt(block.dataset.note);
            block.innerHTML = "";
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement("span");
                star.textContent = i <= note ? "★" : "☆";
                block.appendChild(star);
            }
        });
    }

    // === Fonction accordéon ===
    function activerAccordeon() {
        const boutons = document.querySelectorAll(".avis-titre");
        boutons.forEach(btn => {
            btn.addEventListener("click", () => {
                const contenu = btn.nextElementSibling;
                const ouvert = contenu.classList.contains("ouvert");

                document.querySelectorAll(".avis-contenu").forEach(c => c.classList.remove("ouvert"));
                if (!ouvert) contenu.classList.add("ouvert");
            });
        });
    }

    // === Ouvrir la messagerie avec Finn sélectionné ===
    const messageBtn = document.querySelector(".btn-message");
    if (messageBtn) {
        messageBtn.addEventListener("click", (e) => {
            e.preventDefault();
            // Redirige vers ta page PHP
            window.location.href = "../MESSAGERIE/USR-messagerie.php?contact=Finn";
        });
    }

    // (Suppression de la partie sur les trajets)
});
