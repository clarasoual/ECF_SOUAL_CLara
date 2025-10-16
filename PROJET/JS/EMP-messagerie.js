document.addEventListener("DOMContentLoaded", () => {
    const convList = document.querySelectorAll(".conversations-list li");
    const messageArea = document.querySelector(".message-area");
    const thread = document.querySelector(".message-thread");
    const sendBtn = document.querySelector(".send-message-area button");
    const textarea = document.querySelector("#new-message");

    // 🔽 Création de la flèche retour en bas
    const scrollBtn = document.createElement("button");
    scrollBtn.textContent = "⬇ Revenir à la fin";
    scrollBtn.classList.add("scroll-bottom-btn");
    scrollBtn.style.display = "none";
    messageArea.appendChild(scrollBtn);

    // 🔸 Stockage temporaire des conversations
    const conversations = {
        "Administrateur": [
            { sender: "Admin", text: "Merci, nous allons corriger cela rapidement.", time: "9:50" }
        ],
        "Employé": [
            { sender: "Moi", text: "Je te tiens au courant.", time: "10:02" }
        ]
    };

    let activeConv = "Administrateur";

    // 🔹 Fonction pour afficher une conversation
    function displayConversation(name) {
        thread.innerHTML = ""; // on vide le fil
        const msgs = conversations[name] || [];
        msgs.forEach(msg => {
            const div = document.createElement("div");
            div.classList.add(msg.sender === "Moi" ? "message-sent" : "message-received");
            div.innerHTML = `<p><strong>${msg.sender} : </strong>${msg.text}</p><small>${msg.time}</small>`;
            thread.appendChild(div);
        });
        messageArea.querySelector("h3").textContent = `Conversation avec ${name}`;
        activeConv = name;
        thread.scrollTop = thread.scrollHeight; // scroll en bas
    }

    // 🔸 Sélection d'une conversation
    convList.forEach(li => {
        li.addEventListener("click", () => {
            convList.forEach(el => el.classList.remove("active"));
            li.classList.add("active");
            const name = li.querySelector("strong").textContent;
            displayConversation(name);
        });
    });

    // 🔹 Fonction pour envoyer un message
    function sendMessage() {
        const text = textarea.value.trim();
        if (text === "") {
            showError("Le message ne peut pas être vide !");
            return;
        }

        const time = new Date();
        const formattedTime = `${time.getHours()}:${String(time.getMinutes()).padStart(2, "0")}`;

        // ajout au tableau
        conversations[activeConv] = conversations[activeConv] || [];
        conversations[activeConv].push({ sender: "Moi", text, time: formattedTime });

        // affichage
        const div = document.createElement("div");
        div.classList.add("message-sent");
        div.innerHTML = `<p><strong>Moi : </strong>${text}</p><small>${formattedTime}</small>`;
        thread.appendChild(div);

        textarea.value = ""; // reset textarea
        thread.scrollTop = thread.scrollHeight; // scroll en bas
    }

    // 🔸 Message d’erreur
    function showError(msg) {
        let error = document.querySelector(".error-msg");
        if (!error) {
            error = document.createElement("p");
            error.classList.add("error-msg");
            textarea.parentElement.appendChild(error);
        }
        error.textContent = msg;
        setTimeout(() => { error.textContent = ""; }, 3000);
    }

    // 🔹 Gestion du bouton envoyer
    sendBtn.addEventListener("click", sendMessage);

    // 🔹 Envoi avec Entrée
    textarea.addEventListener("keypress", e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // 🔹 Afficher la flèche quand on scroll vers le haut
    thread.addEventListener("scroll", () => {
        if (thread.scrollTop < thread.scrollHeight - thread.clientHeight - 200) {
            scrollBtn.style.display = "block";
        } else {
            scrollBtn.style.display = "none";
        }
    });

    // 🔸 Clic sur la flèche
    scrollBtn.addEventListener("click", () => {
        thread.scrollTo({ top: thread.scrollHeight, behavior: "smooth" });
    });

    // 🔹 Affichage initial
    displayConversation(activeConv);
    convList[0].classList.add("active");
});
