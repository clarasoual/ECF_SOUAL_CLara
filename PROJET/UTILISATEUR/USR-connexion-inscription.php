<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription ECO RIDE</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-connexion-inscription.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
    <div class="container-connexion">

        <!-- INSCRIPTION -->
        <div id="inscription">
            <h2>S'inscrire</h2>
            <a class="btn-inscription-mail" href="USR-inscription.php">
                Continuer avec une adresse mail
            </a>
        </div>

        <!-- CONNEXION -->
        <div id="connexion">
            <h2>Se connecter</h2>

            <!-- ✅ ACTION CORRIGÉE -->
            <form id="formulaire-connexion"
                  action="../PHP/login.php"
                  method="POST">

                <label for="email">Adresse mail :</label>
                <input type="email"
                       id="email"
                       name="email"
                       required>

                <label for="password">Mot de passe :</label>
                <input type="password"
                       id="password"
                       name="password"
                       required>

                <button type="submit" class="btn-connexion">
                    Connexion
                </button>
            </form>
        </div>

    </div>
</main>

<?php include('../COMPONENTS/COMP-footer.html'); ?>

</body>
</html>
