<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte employés</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
        <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-login.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="connexion-page">

<!-- Header commun employés -->
<?php include('../COMPONENTS/headeradmin.html') ; ?>

    <main>
        <!-- Section globale de la partie connexion/inscription-->
        <div class="container-connexion">
            <div id="connexion">
                <h2>Se connecter</h2>
                <form id="formulaire-connexion" action="#" method="POST">
                    <label for="email-connexion">Adresse mail :</label>
                    <input type="email" id="email-connexion" name="email-connexion" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>

                    <div id="options-connexion">
                        <label>
                            <input type="checkbox" name="remember">
                            Se souvenir de moi
                        </label>
                        <a href="#" id="link-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-connexion">Connexion</button>
                </form>
            </div>
        </div>
    </main>


    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>
