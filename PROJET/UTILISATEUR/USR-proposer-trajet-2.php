<?php
session_start();
include('../PHP/auth.php'); 
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: USR-proposer-trajet.php');
    exit;
}

$_SESSION['trajet_temp'] = [
    'departure'    => $_POST['departure'] ?? '',
    'arrival'      => $_POST['arrival'] ?? '',
    'date'         => $_POST['date'] ?? '',
    'time'         => $_POST['time'] ?? '',
    'vehicle_used' => $_POST['vehicle_used'] ?? '',
    'places'       => $_POST['places'] ?? '',
    'prix'         => $_POST['prix'] ?? 2,
    'commentaire'  => $_POST['commentaire'] ?? '',
    'etapes'       => array_filter($_POST, function($key) {
        return strpos($key, 'step') === 0;
    }, ARRAY_FILTER_USE_KEY)
];

$trajet = $_SESSION['trajet_temp'];
$gains = max(0, (int)$trajet['prix'] - 2);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé du trajet</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-proposer-trajet-2.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main class="trip-step2">
    <h2 class="step-title">Résumé de votre trajet - Étape 2 sur 2</h2>
    <p>Vérifiez les informations de votre trajet avant de le confirmer.</p>

    <section class="trip-summary">
        <table class="summary-table">
            <tr><td class="summary-label">Adresse de départ :</td><td class="summary-value"><?= htmlspecialchars($trajet['departure']) ?></td></tr>
            <tr><td class="summary-label">Arrêts :</td><td class="summary-value"><?= htmlspecialchars(implode(', ', $trajet['etapes'])) ?></td></tr>
            <tr><td class="summary-label">Adresse d'arrivée :</td><td class="summary-value"><?= htmlspecialchars($trajet['arrival']) ?></td></tr>
            <tr><td class="summary-label">Date :</td><td class="summary-value"><?= htmlspecialchars($trajet['date']) ?></td></tr>
            <tr><td class="summary-label">Heure :</td><td class="summary-value"><?= htmlspecialchars($trajet['time']) ?></td></tr>
            <tr><td class="summary-label">Véhicule utilisé :</td><td class="summary-value"><?= htmlspecialchars($trajet['vehicle_used']) ?></td></tr>
            <tr><td class="summary-label">Places disponibles :</td><td class="summary-value"><?= htmlspecialchars($trajet['places']) ?></td></tr>
            <tr><td class="summary-label">Prix par passager :</td><td class="summary-value"><?= htmlspecialchars($trajet['prix']) ?> crédits</td></tr>
            <tr><td class="summary-label">Vos gains par passager :</td><td class="summary-value"><?= $gains ?> crédits</td></tr>
            <tr><td class="summary-label">Commentaires :</td><td class="summary-value"><?= htmlspecialchars($trajet['commentaire']) ?></td></tr>
        </table>

        <section class="trip-actions">
            <form action="USR-proposer-trajet-3.php" method="POST">
                <button type="submit" class="btn-submit">Confirmer le trajet</button>
            </form>
            <p class="edit-link">
                <a href="USR-proposer-trajet.php" id="edit-link">Modifier les informations du trajet</a>
            </p>
        </section>
    </section>
</main>

<script src="../JS/USR-proposer-trajet2.js"></script>
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>