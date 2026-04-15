<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de votre compte - ECO RIDE</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-inscription.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
    <div class="account-creation-container">
        <h1>Création de votre compte</h1>
        <p class="required-notes">* Champs obligatoires</p>

        <form action="../PHP/inscription.php" method="POST" class="form-inscription" novalidate>

            <label for="prenom">Prénom * :</label>
            <input type="text" id="prenom" name="prenom">

            <label for="nom">Nom * :</label>
            <input type="text" id="nom" name="nom">

            <label for="email">Adresse mail * :</label>
            <input type="email" id="email" name="email">

            <label for="password">Mot de passe * :</label>
            <input type="password" id="password" name="password">
            <p class="conditions-password">
                Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
            </p>

            <label for="password_confirm">Confirmer le mot de passe * :</label>
            <input type="password" id="password_confirm" name="password_confirm">

            <label class="accept-conditions">
                <input type="checkbox" name="accept-conditions">
                J'accepte les <a href="#">conditions d'utilisation</a>
            </label>

            <button type="submit" class="btn-submit">Créer mon compte</button>
        </form>
    </div>
</main>

<script src="../JS/USR-inscription.js"></script>

<?php include('../COMPONENTS/COMP-footer.html'); ?>

</body>
</html>