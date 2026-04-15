<?php
session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: USR-inscription.php');
    exit;
}

if (!empty($user['profile_completed']) && $user['profile_completed'] == 1) {
    header('Location: USR-infos-perso.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compléter mon profil - ECO RIDE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-inscription.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-family: 'Quicksand', sans-serif;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s;
            z-index: 1000;
        }
        .toast.show {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
    <div class="account-creation-container">
        <h2>Complétez votre profil</h2>

        <form action="../PHP/traitement-profil.php" method="POST" novalidate>

            <div class="form-group-radio">
                <label>Votre rôle * :</label>
                <label>
                    <input type="radio" name="role" value="passager" <?= $user['role'] === 'passager' ? 'checked' : '' ?>> Passager
                </label>
                <label>
                    <input type="radio" name="role" value="conducteur" <?= $user['role'] === 'conducteur' ? 'checked' : '' ?>> Conducteur
                </label>
                <label>
                    <input type="radio" name="role" value="passager-conducteur" <?= $user['role'] === 'passager-conducteur' ? 'checked' : '' ?>> Passager & Conducteur
                </label>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance * :</label>
                <input type="date" name="date_naissance" id="date_naissance"
                       value="<?= htmlspecialchars($user['date_naissance'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="bio">Bio :</label>
                <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>

            <button type="submit">Enregistrer mon profil</button>

        </form>
    </div>
</main>

<div id="toast" class="toast"></div>

<script src="../JS/USR-inscription2.js"></script>

</body>
</html>