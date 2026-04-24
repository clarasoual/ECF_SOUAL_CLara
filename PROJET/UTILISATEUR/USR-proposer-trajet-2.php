<?php
session_start();
include('../PHP/auth.php'); 
requireLogin();
require('../PHP/connexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: USR-proposer-trajet.php');
    exit;
}

$_SESSION['trajet_temp'] = [
    'departure'    => $_POST['departure']    ?? '',
    'arrival'      => $_POST['arrival']      ?? '',
    'date'         => $_POST['date']         ?? '',
    'time'         => $_POST['time']         ?? '',
    'time_arrivee' => $_POST['time_arrivee'] ?? '',
    'vehicle_used' => $_POST['vehicle_used'] ?? '',
    'places'       => $_POST['places']       ?? '',
    'prix'         => $_POST['prix']         ?? 2,
    'commentaire'  => $_POST['commentaire']  ?? '',
    'etapes'       => array_filter($_POST, function($key) {
        return strpos($key, 'step') === 0;
    }, ARRAY_FILTER_USE_KEY)
];

$trajet = $_SESSION['trajet_temp'];
$gains  = max(0, (int)$trajet['prix'] - 2);

// Récupérer le nom du véhicule
$vehicule_label = '—';
if (!empty($trajet['vehicle_used'])) {
    $stmt = $bdd->prepare("SELECT marque, modele, couleur FROM vehicules WHERE vehicule_id = ?");
    $stmt->execute([$trajet['vehicle_used']]);
    $vehicule = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($vehicule) {
        $vehicule_label = htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele'] . ' ' . $vehicule['couleur']);
    }
}

// Formatage date et heures
$date_fmt          = !empty($trajet['date'])         ? date('d/m/Y', strtotime($trajet['date'])) : '—';
$heure_depart_fmt  = !empty($trajet['time'])         ? substr($trajet['time'], 0, 5) : '—';
$heure_arrivee_fmt = !empty($trajet['time_arrivee']) ? substr($trajet['time_arrivee'], 0, 5) : '—';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résumé du trajet</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-proposer-trajet-2.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main class="trip-step2">
    <h2 class="step-title">Résumé de votre trajet - Étape 2 sur 2</h2>
    <p class="step-subtitle">Vérifiez les informations de votre trajet avant de le confirmer.</p>

    <section class="trip-summary">
        <div class="summary-columns">

            <!-- COLONNE GAUCHE : départ -->
            <div class="summary-card">
                <h3 class="summary-card-title">🗺️ Départ</h3>
                <div class="summary-row">
                    <span class="summary-label">Adresse</span>
                    <span class="summary-value"><?= htmlspecialchars($trajet['departure']) ?></span>
                </div>
                <?php if (!empty(array_filter($trajet['etapes']))): ?>
                <div class="summary-row">
                    <span class="summary-label">Arrêts</span>
                    <span class="summary-value"><?= htmlspecialchars(implode(', ', array_filter($trajet['etapes']))) ?></span>
                </div>
                <?php endif; ?>
                <div class="summary-row">
                    <span class="summary-label">Date</span>
                    <span class="summary-value"><?= $date_fmt ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Heure de départ</span>
                    <span class="summary-value"><?= $heure_depart_fmt ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Véhicule</span>
                    <span class="summary-value"><?= $vehicule_label ?></span>
                </div>
            </div>

            <!-- COLONNE DROITE : arrivée -->
            <div class="summary-card">
                <h3 class="summary-card-title">🏁 Arrivée</h3>
                <div class="summary-row">
                    <span class="summary-label">Adresse</span>
                    <span class="summary-value"><?= htmlspecialchars($trajet['arrival']) ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Heure d'arrivée</span>
                    <span class="summary-value"><?= $heure_arrivee_fmt ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Places disponibles</span>
                    <span class="summary-value"><?= htmlspecialchars($trajet['places']) ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Prix par passager</span>
                    <span class="summary-value highlight"><?= htmlspecialchars($trajet['prix']) ?> crédits</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Vos gains</span>
                    <span class="summary-value highlight"><?= $gains ?> crédits</span>
                </div>
                <?php if (!empty($trajet['commentaire'])): ?>
                <div class="summary-row">
                    <span class="summary-label">Point de rendez-vous</span>
                    <span class="summary-value"><?= htmlspecialchars($trajet['commentaire']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <section class="trip-actions">
            <form action="USR-proposer-trajet-3.php" method="POST">
                <button type="submit" class="btn-submit">Confirmer le trajet</button>
            </form>
            <p class="edit-link">
                <a href="USR-proposer-trajet.php" id="edit-link">← Modifier les informations</a>
            </p>
        </section>
    </section>
</main>

<script src="../JS/USR-proposer-trajet2.js"></script>
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>