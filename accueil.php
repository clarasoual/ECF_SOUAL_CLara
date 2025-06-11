<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Accueil </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
    <?php include('COMPONENTS/header.html') ; ?>
    <section class="hero">
        <div class="hero-image">
            <img src="images/pexels-cottonbro-5329298.jpg" alt ="Image libre de droit covoiturage">
            <div class="slogan">
                <h1>Ensemble, roulons vers un futur plus vert</h1>
            </div>
        </div>
    </section>

    <section class="search-section">
        <form action ="recherche.php" method ="get">
            <div class = "search-container">
                <label for="departure">Je pars de ... </label>
                <input type ="text" id="departure" name="departure" placeholder="Ville de départ" required>

                <label for="destination">Je vais à ... </label>
                <input type ="text" id="destination" name="destination" placeholder="Ville d'arrivée" required>

                <label for ="date"> Aujourd'hui </label>
                <input type ="date" id="date" name="date" required>

                <label for="passenger">1 passager </label>
                <input type="number" id="passenger" name="passenger" min="1" value="1" required>

                <button type="submit" class="search-btn">
                    <img src="" alt="Rechercher"  class="search-icon">
                </button>
            </div>
        </form>
    </section> 

   
<script src="JS/ecoride_js.js"></script> 
</body>
</html>
<?php include('COMPONENTS/footer.html'); ?>

