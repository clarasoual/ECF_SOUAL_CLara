<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profil du conducteur </title>
    <link rel="stylesheet" href="../../CSS/ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Profil du conducteur -->
<section class="driver-profile-section">
    <h2>Profil de <strong>Finn</strong></h2>
    <img src="../../../IMAGES/finn.png"class="profile-picture" alt="Photo de profil" width="150" height="150">
    <p>Age : 23 ans</p>
    <p>Note globale : ★★★★★ (4.8 / 5)</p>
    <p>Nombre d'avis : 3 <a href="#">Voir les avis</a></p>
</section>

<hr>

<section class="-profile-driver-details">
    <h3 class="section-title">En savoir plus</h3>

    <h4 class="subsection-title">Biographie</h4>
    <p class="bio">Conducteur passionné de voyage et de covoiturage, toujours ponctuel et à l'écoute !</p>

    <h4 class="subsection-title">Préférences</h4>

    <ul class="preferences-list">
        <li>Fumeur : Non</li>
        <li>Animaux acceptés : Oui</li>
        <li>Musique : Oui</li>
        <li>Discussion : Avec plaisir</li>
    </ul>

    <h4 class="subsection-title">Véhicule(s)</h4>
    <ul>
        <li>Peugeot 208 - Bleue</li>
        <li>Renault Clio II - Grise</li>
    </ul>
</section>

<hr>

<section class="trip-history">
    <h3 class="section-title">Historique de trajets</h3>

    <div class="trip">
        <h4 class="trip-title">Trajet du 7 février 2024</h4>
        <p class="trip-route">Saint Pierre de Chignac - Bordeaux</p>
        <p class="trip-passengers">Théo K, Nino C., Nina R.</p>
    </div>

</section>

<hr>

<section class="contact-driver">
    <h3 class="section-title">Contacter ce conducteur</h3>
    <p><a href="#" class="btn-message">Envoyer un message</a></p>

    <p><a href="#" class="btn-report">Signaler ce profil</a></p>
</section>
   <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A faire : 
 - CSS
 - Mettre les avis des passagers
 - Revoir encardé profil
 - Faire les autres pages profil
 - Etoiles à moderniser
 - Ancre vers les avis qui sont plus bas
 - Relier envoyer un message vers la messagerie
 - Relier signaler ce profil vers un formulaire de signalement -->

