<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposer un trajet</title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html'); ?>

<main class="trip-step2">
    <h2 class="step-title">Résumé de votre trajet - Étape 2 sur 2</h2>
    <p>Vérifiez les informations de votre trajet avant de le confirmer.</p>

    <section class="trip-summary">
        <table class="summary-table">
            <tr>
                <td class="summary-label">Adresse de départ :</td>
                <td class="summary-value" id="summary-departure">123 Rue exemple, Paris</td>
            </tr>
            <tr>
                <td class="summary-label">Arrêts :</td>
                <td class="summary-value" id="summary-stops">Arrêt 1 : Boulevard exemple</td>
            </tr>
            <tr>
                <td class="summary-label">Adresse d'arrivée :</td>
                <td class="summary-value" id="summary-arrival">456 Avenue Exemple, Lyon</td>
            </tr>
            <tr>
                <td class="summary-label">Date :</td>
                <td class="summary-value" id="summary-date">20/01/09</td>
            </tr>
            <tr>
                <td class="summary-label">Heure :</td>
                <td class="summary-value" id="summary-time">14:30</td>
            </tr>
            <tr>
                <td class="summary-label">Véhicule utilisé</td>
                <td class="summary-value" id="summary-vehicle">Clio grise</td>
            </tr>
            <tr>
                <td class="summary-label">Places disponibles :</td>
                <td class="summary-value" id="summary-places">3</td>
            </tr>
            <tr>
                <td class="summary-label">Commentaires :</td>
                <td class="summary-value" id="summary-comments">Passage par autoroute (péages), coffre petit</td>
            </tr>


            
        </table>
        <section class="trip-actions">
                <form action="proposer_trajet3.php" method="POST">

                    <button type="submit" class="btn-submit">Confirmer le trajet</button>
                </form>

                <p class="edit-link"><a href="proposer_trajet.php">Modifier les informations du trajet</a></p>
            </section>
        <p>Ce trajet vous fera gagner 5 crédits par passager.</p>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>

</body>
</html>