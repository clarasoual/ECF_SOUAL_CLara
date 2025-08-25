<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Rechercher un covoiturage </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-rechercher-trajet.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html') ; ?>

    <!-- Section de recherche de trajet -->
    <section class="search-section">
        <form action ="recherche.php" method ="get">
            <div class = "search-container">
                <div class="form-group">
                <label for="departure">Je pars de ...</label>
                <input type ="text" id="departure" name="departure" placeholder="Ville de départ" required>
                </div>

                <div class="form-group">
                <label for="destination">Je vais à ...</label>
                <input type ="text" id="destination" name="destination" placeholder="Ville d'arrivée" required>
                </div>

                <div class="form-group">
                <label for ="date">Date</label>
                <input type ="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                <label for="passenger">Passagers</label>
                <input type="number" id="passenger" name="passenger" min="1" value="1" required>
                </div>
                
                <button type="submit" class="search-btn">
                    <img src="../../IMAGES/logo recherche.png" alt="Rechercher"  class="search-icon">
                </button>
            </div>
        </form>
    </section> 

    <!-- Section filtres -->
    <section class="results-section">
        <div class="filters">
            <h2>Filtrer</h2>
            <button type="button" class="fillers-clear-btn">Tout effacer</button>

            <label for="max-price">Prix max.</label>
            <input type="number" id="max-price" name="max-price" placeholder="Ex : 20 euros" class="filter-input">

            <label for="eco">Trajet écologique :</label>
            <input type="checkbox" id="eco" name="eco" class="filter-input">

            <label for="note">Note chauffeur minimale :</label>
            <input type="number" id="note" name="note" step="0.1" placeholder="Ex : 4.5" class="filter-input">
        </div>

        <!-- Résultats de la recherche "Bordeaux - Bayonne -->
        <div class="search-results">
            <!-- Trajet 1 -->
            <article class="ride">
                <h3>Bordeaux → Bayonne</h3>
                <p>Départ : 08h30</p>
                <p>Durée estimée : 2h00</p>
                <p>Prix : 5 crédits / passager</p>
                <p>Chauffeur : <img src="../../IMAGES/nino.jpg" alt="Photo chauffeur">Nino C.</p>
                <p>Places restantes : 2</p>
                <p>Voyage écologique</p>
                <a href="details_trajet.php" class="btn">Voir les détails</a>
            </article>

            <div class="day-navigation">
                <button>Jour précédent</button>
                <button>Jour suivant</button>
            </div>
        </div>
    </section>

    

    <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A modifier ici : 
- Titre des filtres a modifier
- Aligner checkbox
- Ajouter des filtres ?
- Diminuer taille de la section filtres
- Styliser la section résultats
- Remplacer les informations des trajets par les trajets types crées dans personas.txt
- Prix max à enlever 
- Photo à adapter-->