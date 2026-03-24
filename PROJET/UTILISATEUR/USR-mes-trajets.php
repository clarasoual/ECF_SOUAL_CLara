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

// Inclure la récupération et le tri des trajets
require_once __DIR__ . '/../PHP/mes_trajets.php';
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

<!-- Section Trajets à venir -->
<div id="upcoming">
    <h3>Trajets à venir</h3>
    <?php if (!empty($trajetsFutur)): ?>
        <?php foreach ($trajetsFutur as $trajet): ?>
            <?php afficherTrajet($trajet, $bdd, $id_utilisateur); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet à venir.</p>
    <?php endif; ?>
</div>

<!-- Section Trajets en cours -->
<div id="ongoing">
    <h3>Trajets en cours</h3>
    <?php if (!empty($trajetsEnCours)): ?>
        <?php foreach ($trajetsEnCours as $trajet): ?>
            <?php afficherTrajet($trajet, $bdd, $id_utilisateur); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet en cours.</p>
    <?php endif; ?>
</div>

<!-- Section Trajets passés -->
<div id="past">
    <h3>Historique des trajets</h3>
    <?php if (!empty($trajetsTermine)): ?>
        <?php foreach ($trajetsTermine as $trajet): ?>
            <?php afficherTrajet($trajet, $bdd, $id_utilisateur); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun trajet passé.</p>
    <?php endif; ?>
</div>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div id="toast-success" class="toast-success">
        ✅ Trajet supprimé avec succès !
    </div>
<?php endif; ?>

</section>

</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/USR-mes-trajets.js"></script>
<script src="../JS/USR-toast.js"></script>
</body>
</html>