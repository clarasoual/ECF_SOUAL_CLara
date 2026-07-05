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
    <title>Espace employé - Connexion</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-login-employe.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="connexion-page">
<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<main>
    <div class="container-connexion">
        <div id="connexion">
            <h2>Se connecter</h2>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'identifiants'): ?>
                    <p class="error-message">❌ Email ou mot de passe incorrect.</p>
                <?php elseif ($_GET['error'] === 'suspendu'): ?>
                    <p class="error-message">⚠️ Votre compte a été suspendu. Contactez l'administrateur.</p>
                <?php elseif ($_GET['error'] === 'champs'): ?>
                    <p class="error-message">❌ Veuillez remplir tous les champs.</p>
                <?php endif; ?>
            <?php endif; ?>

            <form id="formulaire-connexion" action="../PHP/login-employe.php" method="POST" novalidate>

                <div class="form-group">
                    <label for="email-connexion">Adresse mail :</label>
                    <input type="text" id="email-connexion" name="email-connexion" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password">
                </div>

                <button type="submit" class="btn-connexion">Connexion</button>
            </form>
        </div>
    </div>
</main>

<script src="../JS/EMP-login-employe.js"></script>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>
</body>
</html>