<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profil du conducteur </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<section>
    <h2>Profil de <strong>Finn</strong></h2>
    <img src="" alt="Photo de profil" width="150" height="150">
    <p>Age : 34 ans</p>
    <p>Note globale : ★★★★☆ (4.2 / 5)</p>
    <p>Nombre d'avis : 12 <a href="#">Voir les avis</a></p>
</section>

<hr>

<section>
    <h3>En savoir plus</h3>

    <h4>Biographie</h4>
    <p>Conducteur passionné de voyage et de covoiturage, toujours ponctuel et à l'écoute !</p>

    <h4>Préférences</h4>

    <ul>
        <li>Fumeur : Non</li>
        <li>Animaux acceptés : Oui</li>
        <li>Musique : Oui</li>
        <li>Discussion : Avec plaisir</li>
    </ul>

    <h4>Véhicule(s)</h4>
    <ul>
        <li>Peugeot 208 - Bleue</li>
        <li>Renault Clio - Grise</li>
    </ul>
</section>

<hr>

<section>
    <h3>Historique de trajets</h3>

    <div>
        <h4>Trajet 1</h4>
        <p>Paris - Lyon</p>
        <p>Date : 12 mai 2025</p>
    </div>

     <div>
        <h4>Trajet 2</h4>
        <p>Marseille - Toulouse</p>
        <p>Date : 5 avril 2025</p>
    </div>

     <div>
        <h4>Trajet 3</h4>
        <p>Lille - Strasbourg</p>
        <p>Date : 12 mai 2025</p>
    </div>

</section>

<hr>

<section>
    <h3>Contacter ce conducteur</h3>
    <p><a href="#">Envoyer un message</a></p>

    <p><a href="#">Signaler ce profil</a></p>
</section>
   <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>