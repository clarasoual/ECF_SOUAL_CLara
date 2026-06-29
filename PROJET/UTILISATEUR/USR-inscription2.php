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
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
    <div class="account-creation-container">
        <h1>Complétez votre profil</h1>
        <p class="required-notes">* Champs obligatoires</p>

        <form action="../PHP/traitement-profil.php" method="POST" class="form-inscription" novalidate>

            <div class="form-group">
                <label>Votre rôle *</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="role" value="passager">
                        🧳 Passager
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="role" value="conducteur">
                        🚗 Conducteur
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="role" value="passager-conducteur">
                        🧳🚗 Passager & Conducteur
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance *</label>
                <input type="date" name="date_naissance" id="date_naissance"
                       value="<?= htmlspecialchars($user['date_naissance'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="bio">Bio <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--gris-doux);">(optionnel)</span></label>
                <textarea name="bio" id="bio" rows="4"
                          placeholder="Parlez un peu de vous..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Enregistrer mon profil</button>

        </form>
    </div>
</main>

<div id="toast" class="toast"></div>

<script src="../JS/USR-inscription2.js"></script>

</body>
</html>
