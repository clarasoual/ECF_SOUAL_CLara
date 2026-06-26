<?php
session_start();
include('../PHP/connexion.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email-connexion'];
    $password = $_POST['password'];

    $stmt = $bdd->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: ADM-employes.php');
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-login.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="connexion-page">

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<main>
    <div class="container-connexion">
        <div id="connexion">
            <h2>Se connecter</h2>

            <?php if ($error): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form id="formulaire-connexion" action="" method="POST" novalidate>

                <div class="form-group">
                    <label for="email-connexion">Adresse mail :</label>
                    <input type="text" id="email-connexion" name="email-connexion"
                           autocomplete="email">
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

<script src="../JS/ADM-login.js"></script>

<?php include('../COMPONENTS/COMP-footer.html'); ?>
</body>
</html>
