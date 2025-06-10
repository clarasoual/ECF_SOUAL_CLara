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
    <h2>Mes crédits</h2>

    <p><strong>Solde actuel :</strong> 10 crédits</p>

    <p>Vous gagnez des crédits en proposant des trajets à d'autres utilisateurs, lorsque vous êtes conducteur.</p>

    <p><em>Besoin de plus de crédits pour continuer à covoiturer ?</em></p>

    <form action="demande-credits" method="post">
        <button type="submit">Demander des crédits à Eco Ride</button>
    </form>
    <p><strong> Remarque :</strong>Votre demande sera étudiée par notre équipe. Vous recevrez une réponse sous peu.</p>


<h3>Historique des crédits</h3>
<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th>Montant</th>
            <th>Solde à ce jour</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>21/05/2025</td>
            <td>Sortie</td>
            <td>Réservation du trajet n°13</td>
            <td>- 10</td>
            <td>10</td>
        </tr>
        <tr>

        <td>03/05/2025</td>
        <td>Entrée</td>
        <td>Crédits de bienvenue</td>
        <td>+ 20</td>
        <td>20</td>
        </tr>
    </tbody>
</table>

<h3>A propos des crédits</h3>
<p>Chez <strong>EcoRide</strong>, chaque trajet partagé est un pas vers un monde plus solidaire et écologique.</p>
<p>Notre système de crédit permet de vérifier régulièrement si l'ensemble de nos trajets se passent dans le respect de notre charte de confiance.(sécurité, respect, fiabilité.), tout en encourageant les utulisateurs</p>

</section>

 <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>