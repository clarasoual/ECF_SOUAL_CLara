<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte </title>
    <link rel="stylesheet" href="../../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Menu latéral -->
<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<!-- Section principale "Mes trajets" -->
<section>
    <h2>Mes trajets</h2>

    <!-- Navigation  entre les différents types de trajets -->
    <nav class="trips-tab">
        <ul>
            <li><a href="#a-venir">A venir</a></li>
            <li><a href="#en-cours">En cours</a></li>
            <li><a href="#passes">Passés</a></li>
        </ul>
    </nav>

    <div id="upcoming">
        <h3>Trajets à venir</h3>
        <!-- 1 : Nino
        Bordeaux à Bayonne
        25 juillet 2024
        8h30
        Passagers : Théo et Nina -->
    </div>

    <div id="ongoing">
        <h3>Trajets en cours</h3>
        <p>Aucun trajet en cours.</p>
    </div>

    <div id="past">
        <h3>Historique des trajets</h3>

        <div class="past-trips">

            <!-- Cartes trajets passés -->
            <div class="trip-card">
                <p><strong>Date :</strong> 15/05/2025</p>
                <p><strong>Départ :</strong> Lyon</p>
                <p><strong>Arrivée :</strong> Marseille</p>
                <p><strong>Arrêts :</strong> Valence, Avignon</p>
                <p><strong>Note moyenne :</strong> 4.5/5</p>
                <button>Voir les avis</button>
            </div>

            <!-- Remplacer par Trajet 2.1 : Finn : Saint Pierre de Chignac - Bordeaux avec Théo, Nino et Nina le 7 février 2024
            Théo : 5/5
            Nina : 4/5
            Nino : 5/5 -->
        </div>
    </div>
</section>
 <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A faire :

- remplacer le trajet

-->