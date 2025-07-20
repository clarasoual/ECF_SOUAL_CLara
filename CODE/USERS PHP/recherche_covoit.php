<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Rechercher un covoiturage </title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
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

        <!-- Résultats de la recherche -->
        <div class="search-results">
            <article class="ride">
                <h3>Bordeaux → Périgueux</h3>
                <p>Départ : 08h00 - Arrivée : 10h00</p>
                <p>Durée estimée : 2h00</p>
                <p>Prix : 15 euros / passager</p>
                <p>Chauffeur : <img src="IMAGES/photo ld 2.jpg" alt="Photo chauffeur">Jean Dupont</p>
                <p>Places restantes : 2</p>
                <p>Voyage écologique</p>
                <button><a href="details_trajet.php">Voir les détails</a></button>
            </article>

            <article class="ride">
                <h3>Bordeaux → Trélissac</h3>
                <p>Départ : 11h30 - Arrivée : 14h00</p>
                <p>Durée estimée : 2h30</p>
                <p>Prix : 17 euros / passager</p>
                <p>Chauffeur : <img src="IMAGES/photo ld 3.jpg" alt="Photo chauffeur">Jean Patate</p>
                <p>Places restantes : 1</p>
                <p>Voyage écologique</p>
                <button><a href="details_trajet.php">Voir les détails</a></button>
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
- aligner coche
- Diminuer taille de la section filtres
- Styliser la section résultats