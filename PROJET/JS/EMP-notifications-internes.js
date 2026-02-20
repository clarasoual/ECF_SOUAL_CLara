document.addEventListener("DOMContentLoaded", () => {
    const notifFeed = document.querySelector(".notifications-feed");
    const form = document.querySelector(".post-notification form");
    const titleInput = document.querySelector("#notif-title");
    const contentInput = document.querySelector("#notif-content");
    const isAdmin = true; // ⬅️ mets à false si l'utilisateur n'est pas admin

    // 🔸 Barre de filtres
    const filterBar = document.createElement("div");
    filterBar.classList.add("filter-bar");
    filterBar.innerHTML = `
        <input type="text" id="searchInput" placeholder="Rechercher une notification...">
        <select id="filterSelect">
            <option value="all">Toutes</option>
            <option value="admin">Administrateur</option>
            <option value="employee">Employés</option>
        </select>
    `;
    notifFeed.prepend(filterBar);

    const searchInput = document.querySelector("#searchInput");
    const filterSelect = document.querySelector("#filterSelect");

    // 🔹 Fonction d’affichage d’un message d’erreur
    function showError(msg) {
        let error = document.querySelector(".error-msg");
        if (!error) {
            error = document.createElement("p");
            error.classList.add("error-msg");
            form.appendChild(error);
        }
        error.textContent = msg;
        setTimeout(() => { error.textContent = ""; }, 3000);
    }

    // 🔹 Fonction pour créer une notification
    function createNotification(title, message, author = "Moi", role = "Employé") {
        const article = document.createElement("article");
        article.classList.add("notification-item");

        // heure actuelle
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, "0");
        const minutes = now.getMinutes().toString().padStart(2, "0");

        article.innerHTML = `
            <h3>${title}</h3>
            <p>${message}</p>
            <small>Posté par ${author} - ${role} - ${hours}:${minutes}</small>
        `;

        // si admin → bouton supprimer
        if (isAdmin) {
            const deleteBtn = document.createElement("button");
            deleteBtn.textContent = "✕";
            deleteBtn.classList.add("delete-btn");
            deleteBtn.title = "Supprimer la notification";
            deleteBtn.addEventListener("click", () => {
                article.remove();
            });
            article.appendChild(deleteBtn);
        }

        notifFeed.insertBefore(article, document.querySelector(".post-notification"));
    }

    // 🔹 Validation et envoi
    function handleSubmit(e) {
        e.preventDefault();
        const title = titleInput.value.trim();
        const message = contentInput.value.trim();

        if (title === "" || message === "") {
            showError("Veuillez remplir le titre et le message avant de publier.");
            return;
        }

        createNotification(title, message);
        titleInput.value = "";
        contentInput.value = "";
    }

    // 🔹 Publier avec le bouton
    form.addEventListener("submit", handleSubmit);

    // 🔹 Publier avec Entrée (sauf si Shift+Entrée)
    contentInput.addEventListener("keypress", e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleSubmit(e);
        }
    });

    // 🔹 Filtres
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilter = filterSelect.value;

        document.querySelectorAll(".notification-item").forEach(item => {
            const text = item.textContent.toLowerCase();
            const isAdminPost = text.includes("administrateur");

            let visible = true;

            if (searchTerm && !text.includes(searchTerm)) visible = false;
            if (roleFilter === "admin" && !isAdminPost) visible = false;
            if (roleFilter === "employee" && isAdminPost) visible = false;

            item.style.display = visible ? "block" : "none";
        });
    }

    searchInput.addEventListener("input", applyFilters);
    filterSelect.addEventListener("change", applyFilters);
});
