<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Détails trajet </title>
   <link rel="stylesheet" href="../CSS/ecoride_style.css">
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html') ; ?>

    <!-- Le titre du trajet -->
    <div class="page-header">
        <div class="date-title">
            <h1>Jeudi 25 juillet 2024</h1>
        </div>

        <!-- Bouton de réservation -->
        <!-- Mettre que ça coute 5 crédits par passagers -->
        <div class="reservation-btn">
                <a href="reservation.html">Demande de réservation</a>
        </div>
    </div>

    <!-- Détails du trajet -->
    <section class="plan-section">
        <div class="plan-town">
            <h2>Plan schématique du trajet</h2>
            <p>Ville de départ : Bordeaux</p>
            <p>Arrêt 1 : Saugnac-et-Muret</p>
            <p>Arrêt 2 : Soorts-Hossegor</p>
            <p>Ville d'arrivée : Bayonne</p>
        </div>

        <!-- Bouton de demande de la durée -->
        <div class="btn-duration">
            <button type="button">
                Demander la durée max. au chauffeur
            </button>
        </div>
    </section>

    <!-- Section conducteur -->
    <section class="driver">
    <h2>Conducteur</h2>
    <div class="driver-infos">
        <img src="../../IMAGES/nino.jpg" alt="Photo de nino" class="driver-photo">
        <div class="driver-details">
            <p><strong>Pseudo :</strong> Nino</p>
            <p><strong>Nombre de trajets :</strong> 8</p>
            <p><strong>Note moyenne :</strong> 4.5/5</p>
        </div>
    </div>
    <div class="preferences">
        <h3>Préférences</h3>
        <ul>
            <li>Non fumeur</li>
            <li>Animaux acceptés</li>
        </ul>
    </div>

    <div class="vehicle">
        <h3>Véhicule</h3>
        <p><strong>Marque :</strong> Renault</p>
        <p><strong>Modèle :</strong> Clio</p>
        <p><strong>Couleur :</strong> Bleu</p>
        <p><strong>Système :</strong> Essence</p>
    </div>
    </section>

    <section class="passenger">
        <h2>Passagers</h2>

        <article class="passenger-card">
            <img src="../../IMAGES/nina.jpg" alt="Photo de Nina" class="passenger-photo">
            <div class="passenger-info">
                <p><strong>Pseudo :</strong> Nina R.</p>
                <p><em>De Saugnac-et-Muret à Bayonne</em></p>
            </div>
        </article>

        <article class="passenger-card">
            <img src="../../IMAGES/theo.jpg" alt="Photo de Theo" class="passenger-photo">
            <div class="passenger-info">
                <p><strong>Pseudo :</strong> Théo K.</p>
                <p><em>De Bordeaux à Bayonne</em></p>
            </div>
        </article>
    </section>

    <!-- Avis du conducteur -->
    <section class="driver-reviews">
        <h2>Ce que les passagers pensent de Nino</h2>

        <article class="reviews">
            <div class="author-review">
                <img src="../../IMAGES/antoine.jpg" alt="Photo d'antoine" class="author-photo">
                <p><strong>Antoine</strong></p>
                <p><strong>Note :</strong> 5 / 5</p>
            </div>
            <h3>Très bon conducteur</h3>
            <p>Nino est toujours ponctuel et très sympa, je recommande !</p>
        </article>

        <article class="review">
            <div class="author-review">
                <img src="../../IMAGES/finn.png" alt="Photo de Finn" class="author-photo">
                <p><strong>Finn</strong></p>
                <p><strong>Note :</strong> 4 / 5</p>
            </div>
            <h3>Conduite agréable malgré le retard.</h3>
            <p>Trajet confortable, Nino est très respectueux et à l'écoute de ses passagers. Petit bémol, il y a eu un peu de retard à l'arrivée.</p>
        </article>
    </section>

   
    <script src="JS/ecoride_js.js"></script>

<!-- Footer commun -->
<?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A faire ici :
    - Moderniser plan schématique du trajet ( dessin ? )
    - Mettre passagers sur la droite en séparant du reste (en colonne ?)
    - Relier demander durée max au chauffeur à la section messages
    - Relier le nom du conducteur à son profil/historique
    - Note à modernister (étoiles ?)
    - Overflow a enlever avis
    - Noter que ça coute 5 crédits par passagers
    - Gros guillements avis
    - Créer et relier une nouvelle page résumé de la demande de réservation
    - Mettre date des avis et nom du trajet en question
    - Mettre places restantes -->