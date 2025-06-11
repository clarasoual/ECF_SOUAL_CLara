<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Connexion / Inscription ECO RIDE </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>

<?php include('COMPONENTS/header.html') ; ?>

    <main>
        <div class="container-connexion">
            <div id="inscription">
                <h2>S'inscrire</h2>
                <button class="btn-inscription-mail">Continuer avec une adresse mail</button>
                <p id="mentions-legales">
                    En continuant, vous acceptez nos <a href="#">Conditions d'utilisation</a>, notre <a href="#">Politique de confidentialité</a>, notre <a href="#">Politique sur les Cookies</a>.
                </p>
            </div>

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

    <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>