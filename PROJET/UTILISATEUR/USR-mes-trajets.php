<?php
// Toujours commencer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure auth et vérification connexion
require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); // Redirige si non connecté

// ID utilisateur connecté
$id_utilisateur = $_SESSION['user_id'] ?? null;
if (!$id_utilisateur) {
    die("Erreur : utilisateur non connecté.");
}

// Inclure la récupération des trajets
require_once __DIR__ . '/../PHP/mes_trajets.php';

// ⚠️ Debug temporaire pour vérifier
// var_dump($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Mes trajets</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-mes-trajets.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<section>
    <h2>Mes trajets</h2>

    <nav class="trips-tab">
        <ul>
            <li><a href="#upcoming">À venir</a></li>
            <li><a href="#ongoing">En cours</a></li>
            <li><a href="#past">Passés</a></li>
        </ul>
    </nav>

    <div id="upcoming"><h3>Trajets à venir</h3></div>
    <div id="ongoing"><h3>Trajets en cours</h3></div>
    <div id="past"><h3>Historique des trajets</h3><div class="past-trips"></div></div>

    <?php
    // Afficher tous les trajets, le JS les trie dans les bons onglets
    foreach ($trajets as $trajet) {
        afficherTrajet($trajet, $bdd, $id_utilisateur);
    }
    ?>

</section>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<div id="toast-success" class="toast-success">
    ✅ Trajet supprimé avec succès !
</div>
<?php endif; ?>

</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/USR-mes-trajets.js"></script>
<script src="../JS/USR-toast.js"></script>
</body>
</html>