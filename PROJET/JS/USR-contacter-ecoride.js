// ---------------------- CONTACT ECO RIDE ----------------------
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".contact-form");

    if (!form) return;

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Récupérer les valeurs
        const nom = form.nom.value.trim();
        const email = form.email.value.trim();
        const sujet = form.sujet.value.trim();
        const message = form.message.value.trim();

        // Vérification des champs
        if (!nom || !email || !sujet || !message) {
            alert("Merci de remplir tous les champs avant d’envoyer votre message.");
            return;
        }

        // Vérification du format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("L’adresse e-mail n’est pas valide.");
            return;
        }

        // Message de confirmation
        const confirmation = document.createElement("div");
        confirmation.className = "message visible";
        confirmation.textContent = `Merci ${nom}, ton message a bien été envoyé ! 🌿`;
        document.body.appendChild(confirmation);

        setTimeout(() => confirmation.remove(), 3000);

        // Réinitialisation du formulaire
        form.reset();
    });
});
