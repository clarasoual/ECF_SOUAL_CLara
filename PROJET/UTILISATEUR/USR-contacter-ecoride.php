<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte - Crédits </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-contacter-ecoride.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Menu latéral -->
<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<main class="contact-page">
    <h1>Contactez Eco Ride</h1>
    <p>Vous pouvez nous envoyer un message via le formulaire si vous ne trouvez pas réponses à vos questions dans la <a href="faq">F.A.Q.</a></p>

    <form action="envoyer_message.php" method="POST" class="contact-form">
        <label for="nom">Nom : </label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email : </label>
        <input type="email" id="email" name="email" required>

        <label for="sujet">Sujet : </label>
        <input type="text" id="sujet" name="sujet" required>

        <label for="message">Message : </label>
        <textarea id="message" name="message" rows="6"></textarea>

        <button type="submit">Envoyer</button>
    </form>

    <section class="contact-info">
        <h2>Nos coordonnées</h2>
        <p>Email : <a href="mailto:contact@ecoride.com">contact@ecoride.com</a></p>
        <p>Téléphone : 01 23 45 67 89</p>
        <p>Adresse : 123 Rue de la Gaité, 33000 Bordeaux</p>
    </section>
</main>

 <script src="JS/ecoride_js.js"></script>
    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>