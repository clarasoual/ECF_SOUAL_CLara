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
        "Nino C.": [
            { sender: "Nino", text: "Salut, c'est toujours bon pour toi pour le trajet de demain ?", time: "20:16" },
            { sender: "Moi", text: "Oui c'est toujours bon pour moi !", time: "20:18" },
            { sender: "Nino", text: "Super, à demain alors ! Bonne soirée", time: "20:26" }
        ],
        "Finn M.": [
            { sender: "Moi", text: "Merci pour le trajet", time: "18:45" }
        ]
    };

    let activeConv = "Nino C.";

    // 🔹 Fonction pour afficher une conversation
    function displayConversation(name) {
        thread.innerHTML = ""; // vider le fil
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
    convList.forEach(li => li.classList.remove("active"));
    convList[0].classList.add("active");
});
