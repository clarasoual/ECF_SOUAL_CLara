<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte - Crédits </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-gestion-credits.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Menu latéral -->
<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<!-- Section crédits -->
<section>
    <h2>Mes crédits</h2>

    <p><strong>Solde actuel :</strong> 15 crédits</p>

    <p>Vous gagnez des crédits en proposant des trajets à d'autres utilisateurs, lorsque vous êtes conducteur.</p>

    <!-- Demande de crédits -->
    <p><em>Besoin de plus de crédits pour continuer à covoiturer ?</em></p>

    <form action="#" method="post">
        <button type="submit">Demander des crédits à Eco Ride</button>
    </form>
    <p><strong> Remarque :</strong>Votre demande sera étudiée par notre équipe. Vous recevrez une réponse sous peu.</p>

<!-- Historique des crédits -->
<h3>Historique des crédits</h3>
<table border="1">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Type</th>
            <th scope="col">Description</th>
            <th scope="col">Montant</th>
            <th scope="col">Solde à ce jour</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>04/02/2024</td>
            <td>Sortie</td>
            <td>Réservation du trajet n°2.1</td>
            <td>- 5</td>
            <td>15</td>
        </tr>
        <tr>

        <td>01/02/2024</td>
        <td>Entrée</td>
        <td>Crédits de bienvenue</td>
        <td>+ 20</td>
        <td>20</td>
        </tr>
    </tbody>
</table>

<!-- Infos générales sur le système de crédits -->
<h3>A propos des crédits</h3>
<p>Chez <strong>EcoRide</strong>, chaque trajet partagé est un pas vers un monde plus solidaire et écologique.</p>
<p>Notre système de crédit permet de vérifier régulièrement si l'ensemble de nos trajets se passent dans le respect de notre charte de confiance (sécurité, respect, fiabilité.), tout en encourageant les utilisateurs à adopter un comportement éco-responsable.</p>

</section>

 <script src="JS/ecoride_js.js"></script>
    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A FAIRE :

 - CSS
 - Aérer le tout
 - Mettre lien vers la trajet en question dans le tableau
 - Mettre historique avant la demande de crédits ? -->