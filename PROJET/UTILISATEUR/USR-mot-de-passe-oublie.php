<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mot de passe oublié - ECO RIDE </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-mot-de-passe-oublie.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

<main class="password-forgotten-container">
    <section class="password-forgotten-content">
        <h1>Mot de passe oublié</h1>
        <p>Veuillez entrer votre adresse e-mail pour recevoir un lien de réinitialisation de votre mot de passe</p>

        <form action="passwordforgotten_treatment.php" method="POST" class="password-forgotten-form">
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" placeholder="exemple@mail.com" required>

            <button type="submit">Envoyer</button>

            <p class="back-connexion">
                <a href="connexion_inscription.php">Retour à la page de connexion</a>
            </p>
        </form>
    </section>

</main>
<script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>
</body>

