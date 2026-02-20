document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".password-forgotten-form");
    const emailInput = document.querySelector("#email");

    form.addEventListener("submit", (e) => {
        e.preventDefault(); // Empêche l'envoi automatique du formulaire

        const email = emailInput.value.trim();

        // Vérification du format de l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Veuillez entrer une adresse e-mail valide !");
            emailInput.focus();
            return;
        }

        // Confirmation avant envoi
        const confirmSend = confirm(`Voulez-vous envoyer un lien de réinitialisation à ${email} ?`);
        if (confirmSend) {
            form.submit(); // envoie le formulaire si confirmé
        }
    });
});
