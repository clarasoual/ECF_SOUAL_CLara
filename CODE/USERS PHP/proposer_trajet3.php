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

<main class="trip-confirmation">
    <section class="confirmation-message">
        <h2 class="step-title">Votre trajet est en ligne !</h2>
        <p>Félicitations ! Votre proposition de trajet a été enregistrée et est désormais visible par les passagers.</p>

        <p>Vous pouvez :</p>
        <ul>
            <li><a href="mestrajets.php">Voir vos trajets.</a></li>
            <li><a href="proposertrajet.php">Proposer un nouveau trajet</a></li>
            <li><a href="accueil.php">Retourner à l'accueil</a></li>
        </ul>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>

</body>
</html>