<?php include('COMPONENTS/header.html') ; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Rechercher un covoiturage </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
    <section class="search-section">
        <form action="recherche.php" method="get">
            <div class="search-container">
                <label for="departure"> -Je pars de...</label>
                <input type="text" id="departure" name="departure" value="Bordeaux" required>

                <label for="destination"> -Je vais à...</label>
                <input type="text" id="destination" name="destination" value="Perigueux" required>

                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="2025-05-12" required>

                <label for="passenger">Passager.s</label>
                <input type="number" id="passenger" name="passenger" value="1" min="1" required>

                <button type="submit" class="search-btn">
                    <img src="" alt="Rechercher" class="search-icon">
                </button>
            </div>
        </form>
    </section>

    <section class="results-section">
        <div class="filters">
            <h2>Filtrer</h2>
            <button type="button">Tout effacer</button>

            <label for="max-price">Prix max.</label>
            <input type="number" id="max-price" name="max-price" placeholder="Ex : 20 euros">

            <label for="eco">Trajet écologique :</label>
            <input type="checkbox" id="eco" name="eco">

            <label for="note">Note chauffeur minimale :</label>
            <input type="number" id="note" name="note" step="0.1" placeholder="Ex : 4.5">
        </div>

        <div class="search-results">
            <article class="trajet">
                <h3>Bordeaux → Périgueux</h3>
                <p>Départ : 08h00 - Arrivée : 10h00</p>
                <p>Durée estimée : 2h00</p>
                <p>Prix : 15 euros / passager</p>
                <p>Chauffeur : <img src="" alt="Photo chauffeur">Jean Dupont</p>
                <p>Places restantes : 2</p>
                <p>Voyage écologique</p>
                <button>Voir les détails</button>
            </article>

            <article class="trajet">
                <h3>Bordeaux → Bayonne</h3>
                <p>Départ : 11h30 - Arrivée : 14h00</p>
                <p>Durée estimée : 2h30</p>
                <p>Prix : 17 euros /> passager</p>
                <p>Chauffeur : <img src="" alt="Photo chauffeur">Jean Patate</p>
                <p>Places restantes : 1</p>
                <p>Voyage écologique</p>
                <button>Voir les détails</button>
            </article>

            <div class="day-navigation">
                <button>Jour précédent</button>
                <button>Jour suivant</button>
            </div>
        </div>
    </section>

    

    <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>