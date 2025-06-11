<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<?php include('COMPONENTS/menu-profil.html'); ?>

<section>
    <h2>Mes trajets</h2>
    <nav class="trips-tab">
        <ul>
            <li><a href="#a-venir">A venir</a></li>
            <li><a href="#en-cours">En cours</a></li>
            <li><a href="#passes">Passés</a></li>
        </ul>
    </nav>

    <div id="upcoming">
        <h3>Trajets à venir</h3>
        <p>Vous n'avez pas de trajet prévu actuellement.</p>
    </div>

    <div id="ongoing">
        <h3>Trajets en cours</h3>
        <p>Aucun trajet en cours.</p>
    </div>

    <div id="past">
        <h3>Historique des trajets</h3>

        <div class="past-trips">
            <div class="trip-card">
                <p><strong>Date :</strong> 15/05/2025</p>
                <p><strong>Départ :</strong> Lyon</p>
                <p><strong>Arrivée :</strong> Marseille</p>
                <p><strong>Arrêts :</strong> Valence, Avignon</p>
                <p><strong>Note moyenne :</strong> 4.5/5</p>
                <button>Voir les avis</button>
            </div>

            <div class="trip-card">
                <p><strong>Date :</strong> 03/04/2025</p>
                <p><strong>Départ :</strong> Lille</p>
                <p><strong>Arrivée :</strong> Paris</p>
                <p><strong>Arrêts :</strong> Arras, Amiens</p>
                <p><strong>Note moyenne :</strong> 5/5</p>
                <button>Voir les avis</button>

            </div>
        </div>
    </div>
</section>
 <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>