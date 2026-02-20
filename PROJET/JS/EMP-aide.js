// ---------------------- FORMULAIRE AIDE ----------------------
const helpForm = document.querySelector('form');
const fileInput = document.querySelector('#screenshot');

if (helpForm) {
    helpForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Récupérer les valeurs des champs
        const name = document.querySelector('#emp_name').value.trim();
        const email = document.querySelector('#emp_email').value.trim();
        const empId = document.querySelector('#emp_id').value.trim();
        const subject = document.querySelector('#subject').value.trim();
        const description = document.querySelector('#description').value.trim();
        const severity = document.querySelector('input[name="severity"]:checked').value;

        // Vérifier les champs obligatoires
        if (!name || !email || !empId || !subject || !description) {
            alert("Veuillez remplir tous les champs obligatoires.");
            return;
        }

        // Vérifier format e-mail simple
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Veuillez entrer une adresse e-mail valide.");
            return;
        }

        // Vérifier la taille du fichier (si fourni)
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 5 * 1024 * 1024; // 5 Mo
            if (file.size > maxSize) {
                alert("La pièce jointe est trop volumineuse (max 5 Mo).");
                return;
            }
        }

        // Générer un numéro de ticket aléatoire
        const ticketNumber = 'TKT-' + Math.floor(Math.random() * 90000 + 10000);

        // Message de confirmation
        alert(`Signalement envoyé !\nNuméro de ticket : ${ticketNumber}`);

        // Réinitialiser le formulaire
        helpForm.reset();
    });
}
