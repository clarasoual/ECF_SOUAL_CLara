<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé du trajet</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-proposer-trajet-2.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main class="trip-step2">
    <h2 class="step-title">Résumé de votre trajet - Étape 2 sur 2</h2>
    <p>Vérifiez les informations de votre trajet avant de le confirmer.</p>

    <section class="trip-summary">
        <table class="summary-table">
            <tr><td class="summary-label">Adresse de départ :</td><td class="summary-value" id="summary-departure"></td></tr>
            <tr><td class="summary-label">Arrêts :</td><td class="summary-value" id="summary-stops"></td></tr>
            <tr><td class="summary-label">Adresse d'arrivée :</td><td class="summary-value" id="summary-arrival"></td></tr>
            <tr><td class="summary-label">Date :</td><td class="summary-value" id="summary-date"></td></tr>
            <tr><td class="summary-label">Heure :</td><td class="summary-value" id="summary-time"></td></tr>
            <tr><td class="summary-label">Véhicule utilisé :</td><td class="summary-value" id="summary-vehicle"></td></tr>
            <tr><td class="summary-label">Places disponibles :</td><td class="summary-value" id="summary-places"></td></tr>
            <tr><td class="summary-label">Commentaires :</td><td class="summary-value" id="summary-comments"></td></tr>
        </table>

        <section class="trip-actions">
            <form action="../UTILISATEUR/USR-proposer-trajet-3.php" method="POST">
                <button type="submit" class="btn-submit">Confirmer le trajet</button>
            </form>
            <p class="edit-link"><a href="USR-proposer-trajet.php" id="edit-link">Modifier les informations du trajet</a></p>
        </section>

        <p>Ce trajet vous fera gagner 5 crédits par passager.</p>
    </section>
</main>

<script src="../JS/USR-proposer-trajet2.js"></script>
<?php include('../COMPONENTS/COMP-footer.html'); ?>
</body>
</html>
