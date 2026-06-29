<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
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

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
    <div class="container-connexion">

        <!-- INSCRIPTION -->
        <div id="inscription">
            <h2>Pas encore inscrit ?</h2>
            <p style="font-family:'Quicksand',sans-serif; color:#2e2b28; font-size:0.95rem; margin:0; opacity:0.8;">
                Rejoignez la communauté Eco Ride et partagez vos trajets.
            </p>
            <a class="btn-inscription-mail" href="USR-inscription.php">
                Créer un compte
            </a>
        </div>

        <!-- CONNEXION -->
        <div id="connexion">
            <h2>Se connecter</h2>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'suspendu'): ?>
                    <p class="error-message">⚠️ Votre compte a été suspendu. <a href="mailto:support@ecoride.fr">Contacter le support EcoRide</a></p>
                <?php elseif ($_GET['error'] === 'identifiants_incorrects'): ?>
                    <p class="error-message">❌ Email ou mot de passe incorrect.</p>
                <?php elseif ($_GET['error'] === 'champs_manquants'): ?>
                    <p class="error-message">❌ Veuillez remplir tous les champs.</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php $redirect = $_GET['redirect'] ?? '../UTILISATEUR/USR-index.php'; ?>

            <form id="formulaire-connexion"
                  action="../PHP/login.php"
                  method="POST"
                  novalidate>

                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

                <div class="form-group">
                    <label for="email">Adresse mail</label>
                    <input type="text" id="email" name="email" autocomplete="email" placeholder="votre@email.com">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                </div>

                <button type="submit" class="btn-connexion">
                    Connexion
                </button>
            </form>
        </div>

    </div>
</main>

<script src="../JS/USR-connexion-inscription.js"></script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>

</body>
</html>