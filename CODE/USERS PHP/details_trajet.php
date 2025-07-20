<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Détails trajet </title>
   <link rel="stylesheet" href="../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html') ; ?>

    <!-- Le titre du trajet -->
    <div class="page-header">
        <div class="date-title">
            <h1>Samedi 12 mai 2025</h1>
        </div>

        <!-- Bouton de réservation -->
        <div class="reservation-btn">
                <a href="reservation.html">Demande de réservation</a>
        </div>
    </div>

    <!-- Détails du trajet -->
    <section class="plan-section">
        <div class="plan-town">
            <h2>Plan schématique du trajet</h2>
            <p>Ville de départ : Bordeaux</p>
            <p>Ville d'arrivée : Périgueux</p>
            <p>Arrêt 1 : Libourne</p>
            <p>Arrêt 2 : Saint Astier</p>
        </div>

        <!-- Bouton de demande de la durée -->
        <div class="btn-duration">
            <button type="button">
                Demander la durée max. au chauffeur
            </button>
        </div>
    </section>

    <!-- Section condcuteur -->
    <section class="driver">
    <h2>Conducteur</h2>
    <div class="driver-infos">
        <img src="../../IMAGES/thierry.jpg" alt="Photo de Thierry" class="driver-photo">
        <div class="driver-details">
            <p><strong>Pseudo :</strong> Thierry</p>
            <p><strong>Nombre de trajets :</strong> 8</p>
            <p><strong>Note moyenne :</strong> 4.7/5</p>
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
            <img src="../../IMAGES/claire.jpg" alt="Photo de Claire" class="passenger-photo">
            <div class="passenger-info">
                <p><strong>Pseudo :</strong>Claire</p>
                <p><em>De l'arrêt 1 à Périgueux</em></p>
            </div>
        </article>

        <article class="passenger-card">
            <img src="../../IMAGES/marc.jpg" alt="Photo de Marc" class="passenger-photo">
            <div class="passenger-info">
                <p><strong>Pseudo :</strong>Marc</p>
                <p><em>De l'arrêt Bordeaux à Périgueux</em></p>
            </div>
        </article>
    </section>

    <!-- Avis du conducteur -->
    <section class="driver-reviews">
        <h2>Ce que les passagers pensent de Thierry</h2>

        <article class="reviews">
            <div class="author-review">
                <img src="../../IMAGES/sophie.jpeg" alt="Photo de sophie" class="author-photo">
                <p><strong>Sophie</strong></p>
                <p><strong>Note :</strong> 5 / 5</p>
            </div>
            <h3>Très bon conducteur</h3>
            <p>Thierry est toujours ponctuel et très sympa, je reccomande !</p>
        </article>

        <article class="review">
            <div class="author-review">
                <img src="../../IMAGES/lucas.jpg" alt="Photo de Lucas" class="author-photo">
                <p><strong>Lucas</strong></p>
                <p><strong>Note :</strong> 4.6 / 5</p>
            </div>
            <h3>Conduite agréable</h3>
            <p>Trajet confortable, Thierry est très respectueux et à l'écoute de ses passagers.</p>
        </article>
    </section>

   
    <script src="JS/ecoride_js.js"></script>

<!-- Footer commun -->
<?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A modifier ici :
    - Moderniser plan shcématique du trajet ( dessin ? )
    - Mettre passagers sur la droite en séparant
    - Relier demander durée max au chauffeur à la section messages
    - Relier le nom du condcuteur à son profil/historique
    - Note à modernister
    - Scroll a enlever avis
    
    A ajouter : 

    - Guillemets avis
    - Créer et relier une nouvelle page résumé de la demande de réservation 