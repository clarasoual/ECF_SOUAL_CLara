<?php
session_start();
include('../PHP/connexion.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos existantes depuis la table 'utilisateurs'
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Si aucune donnée trouvée, rediriger vers l'inscription
if (!$user) {
    header('Location: USR-inscription.php');
    exit;
}

// Si le profil est déjà complété, rediriger vers la page infos perso
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
        /* Toast */
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

<h2>Complétez votre profil</h2>

<form action="../PHP/traitement-profil.php" method="POST">
    
    <label>Votre rôle :</label><br>
    <label>
        <input type="radio" name="role" value="passager" required <?= $user['role'] === 'passager' ? 'checked' : '' ?>> Passager
    </label>
    <label>
        <input type="radio" name="role" value="conducteur" <?= $user['role'] === 'conducteur' ? 'checked' : '' ?>> Conducteur
    </label>
    <label>
        <input type="radio" name="role" value="passager-conducteur" <?= $user['role'] === 'passager-conducteur' ? 'checked' : '' ?>> Passager & Conducteur
    </label>
    <br><br>

    <label>Date de naissance :</label><br>
    <input type="date" name="date_naissance" value="<?= htmlspecialchars($user['date_naissance'] ?? '') ?>"><br><br>

    <label>Bio :</label><br>
    <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea><br><br>

    <button type="submit">Enregistrer mon profil</button>
</form>

<!-- Toast -->
<div id="toast" class="toast"></div>

<script>
// Toast
function showToast(message, duration = 6000) {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.classList.add('show');
  setTimeout(() => { toast.classList.remove('show'); }, duration);
}

// Montrer toast si rôle conducteur
document.querySelectorAll('input[name="role"]').forEach(input => {
    input.addEventListener('change', () => {
        if(input.value === 'conducteur' || input.value === 'passager-conducteur') {
            showToast("⚠️ Votre rôle nécessite des infos véhicule. Elles seront à compléter sur la page suivante.");
        }
    });
});
</script>

</body>
</html>
