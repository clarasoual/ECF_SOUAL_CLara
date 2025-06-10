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
        <div class="connexion-container">
            <div class="inscription-section">
                <h2>S'inscrire</h2>
                <button class="btn-inscription">Continuer avec une adresse mail</button>
                <p class="mentions-legales">
                    En continuant, vous acceptez nos <a href="#">Conditions d'utilisation</a>, notre <a href="#">Politique de confidentialité</a>, notre <a href="#">Politique sur les Cookies</a>.
                </p>
            </div>

            <div class="connexion-section">
                <h2>Se connecter</h2>
                <form action="#" method="POST">
                    <label for="email">Adresse mail :</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>

                    <div class="options-connexion">
                        <label>
                            <input type="checkbox" name="remember">
                            Se souvenir de moi
                        </label>
                        <a href="#">Mot de passe oublié ?</a>
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