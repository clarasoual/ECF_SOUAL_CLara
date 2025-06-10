<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte - Avis </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<?php include('COMPONENTS/menu-profil.html'); ?>
<section>
    <h2>Mes avis</h2>
<div id="avis-recus">
    <h3>Avis reçus</h3>

    <div class="avis">
        <p><strong>Date :</strong> 12/05/2025</p>
        <p><strong>Trajet :</strong> Toulouse - Bordeaux</p>
        <p><strong>Rôle :</strong> Conducteur</p>
        <p><strong>Note :</strong> 4/5</p>
        <p><strong>Commentaire :</strong> Trajet très agréable, merci.</p>
    </div>

    <div class="avis">
        <p><strong>Date :</strong> 28/04/2025</p>
        <p><strong>Trajet :</strong> Nantes - Rennes</p>
        <p><strong>Rôle :</strong> Passager</p>
        <p><strong>Note :</strong> 4/5</p>
        <p><strong>Commentaire :</strong> Très ponctuelle et sympa !</p>
    </div>
</div>

<div id="avis-donnes">
    <h3>Avis donnés</h3>

    <div class="avis">
        <p><strong>Date :</strong> 19/04/2025</p>
        <p><strong>Trajet :</strong> Lyon - Grenoble</p>
        <p><strong>Rôle :</strong> Passager</p>
        <p><strong>Note :</strong> 3/5</p>
        <p><strong>Commentaire :</strong> Chauffeur correct, un peu en retard.</p>
    </div>

    <div class="avis">
        <p><strong>Date :</strong> 03/04/2025</p>
        <p><strong>Trajet :</strong> Lille - Paris</p>
        <p><strong>Rôle :</strong> Conducteur</p>
        <p><strong>Note :</strong> 5/5</p>
        <p><strong>Commentaire :</strong> Très bon passager, respectueux et à l'heure.</p>
    </div>
</div>
</section>
 <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>