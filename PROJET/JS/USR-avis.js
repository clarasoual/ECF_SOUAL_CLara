document.addEventListener("DOMContentLoaded", () => {
    const receivedSection = document.getElementById("reviews-received");
    const givenSection = document.getElementById("reviews-given");
    const reviewsSection = document.querySelector(".reviews-section");
    const btnContainer = reviewsSection.querySelector(".btn-container");

    // Boutons
    const btnReceived = document.createElement("button");
    btnReceived.textContent = "Avis reçus";
    const btnGiven = document.createElement("button");
    btnGiven.textContent = "Avis donnés";

    btnContainer.appendChild(btnReceived);
    btnContainer.appendChild(btnGiven);

    btnReceived.addEventListener("click", () => {
        receivedSection.style.display = "block";
        givenSection.style.display = "none";
    });
    btnGiven.addEventListener("click", () => {
        receivedSection.style.display = "none";
        givenSection.style.display = "block";
    });

    // Tri
    const sortSelect = document.createElement("select");
    sortSelect.innerHTML = `
        <option value="">-- Trier par --</option>
        <option value="date-desc">Date décroissante</option>
        <option value="date-asc">Date croissante</option>
        <option value="note-desc">Note décroissante</option>
        <option value="note-asc">Note croissante</option>
    `;
    btnContainer.appendChild(sortSelect);

    sortSelect.addEventListener("change", () => {
        const visibleSection = receivedSection.style.display !== "none" ? receivedSection : givenSection;
        const reviews = Array.from(visibleSection.querySelectorAll(".review"));
        const option = sortSelect.value;

        reviews.sort((a,b) => {
            const dateA = new Date(a.querySelector("p:nth-of-type(1)").textContent.replace("Date : ","").split("/").reverse().join("-"));
            const dateB = new Date(b.querySelector("p:nth-of-type(1)").textContent.replace("Date : ","").split("/").reverse().join("-"));
            const noteA = parseInt(a.querySelector("p:nth-of-type(4)").textContent.replace("Note : ","").split("/")[0]);
            const noteB = parseInt(b.querySelector("p:nth-of-type(4)").textContent.replace("Note : ","").split("/")[0]);

            if(option === "date-desc") return dateB - dateA;
            if(option === "date-asc") return dateA - dateB;
            if(option === "note-desc") return noteB - noteA;
            if(option === "note-asc") return noteA - noteB;
            return 0;
        });

        reviews.forEach(r => visibleSection.appendChild(r));
    });

    // Voir plus / réduire
    const allReviews = document.querySelectorAll(".review");
    allReviews.forEach(review => {
        const comment = review.querySelector("p:last-of-type");
        const fullText = comment.textContent;
        const shortText = fullText.slice(0, 50) + (fullText.length > 50 ? "..." : "");
        comment.textContent = shortText;
        if(fullText.length > 50){
            const toggleBtn = document.createElement("button");
            toggleBtn.textContent = "Voir plus";
            toggleBtn.style.marginLeft = "10px";
            comment.appendChild(toggleBtn);

            toggleBtn.addEventListener("click", () => {
                if(toggleBtn.textContent === "Voir plus"){
                    comment.textContent = fullText;
                    toggleBtn.textContent = "Réduire";
                    comment.appendChild(toggleBtn);
                } else {
                    comment.textContent = shortText;
                    toggleBtn.textContent = "Voir plus";
                    comment.appendChild(toggleBtn);
                }
            });
        }
    });

    // Diagramme
    const chartContainer = reviewsSection.querySelector(".chart-container");

    function updateChart() {
        chartContainer.innerHTML = "<h4>Répartition des notes :</h4>";
        const visibleSection = receivedSection.style.display !== "none" ? receivedSection : givenSection;
        const reviews = Array.from(visibleSection.querySelectorAll(".review"));
        const counts = [0,0,0,0,0];

        reviews.forEach(r => {
            const note = parseInt(r.querySelector("p:nth-of-type(4)").textContent.replace("Note : ","").split("/")[0]);
            counts[note-1]++;
        });

        counts.forEach((c,i) => {
            const bar = document.createElement("div");
            bar.style.width = `${c*30}px`;
            bar.style.background = "#007bff";
            bar.style.color = "white";
            bar.style.padding = "2px 5px";
            bar.style.margin = "2px 0";
            bar.textContent = `${i+1} étoile : ${c}`;
            chartContainer.appendChild(bar);
        });
    }

    updateChart();
    sortSelect.addEventListener("change", updateChart);
});
