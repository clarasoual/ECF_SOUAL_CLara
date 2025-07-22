<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Espace employé - Suivi signalement </title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/headeremploye.html') ; ?>

<?php include('../COMPONENTS/menuemploye.html') ; ?>

<hr>

<main>

    <!--Contenu principal suivi signalement -->
    <section class="report-tracking">
        <h2>Suivi signalement</h2>

        <article class="report-details">
            <h3>Signalement #023</h3>
            <p><strong>Conducteur :</strong>Finn</p>
            <p><strong>Trajet :</strong>Paris - Rennes</p>
            <p><strong>Téléphone :</strong>06 12 34 56 78</p>
            <p><strong>Email :</strong>finn@exemple.com</p>
            <p><strong>Commentaire :</strong>Le conducteur a été en retard et irrespectueux.</p>
        </article>

        <hr>

        <h3>Passagers concernés</h3>

        <div class="passenger-card">

        <h4>Passager 1</h4>
        <img src="#" alt="Photo de profil" width="60">
        <p><strong>Pseudo :</strong> Alex</p>
        <p><strong>Téléphone :</strong>06 11 12 13 14</p>
        <p><strong>Email :</strong>alex@exemple.com</p>
        <p><strong>Commentaire :</strong>Le conducteur était agressif.</p>
        </div>
        
        <hr>

        <div class="passenger-card">

        <h4>Passager 2</h4>
        <img src="" alt="Photo de profil" width="60">
        <p><strong>Pseudo :</strong> Zoé</p>
        <p><strong>Téléphone :</strong>06 20 21 22 23</p>
        <p><strong>Email :</strong>zoe@exemple.com</p>
        <p><strong>Commentaire :</strong>Je ne me suis pas du tout sentie en sécurité.</p>
        </div>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>
    <?php include('../COMPONENTS/footer.html') ?>
</body>
</html>

<!-- A faire : 

- CSS
- Modifier les passagers
-->
