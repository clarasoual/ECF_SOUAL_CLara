<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../PHP/connexion.php');
require_once(__DIR__ . '/../PHP/recherche_trajets.php'); // charge aussi logs.php

// Initialisation des variables
$trajets            = [];
$trajetsAlternatifs = [];
$depart             = '';
$arrivee            = '';
$date               = '';

// Traitement du formulaire
if (isset($_GET['departure'], $_GET['destination'], $_GET['date'])) {
    $depart  = trim($_GET['departure']);
    $arrivee = trim($_GET['destination']);
    $date    = trim($_GET['date']);

    // Recherche des trajets exacts
    $trajets = chercherTrajets($bdd, $depart, $arrivee, $date);

    // Log de la recherche (un seul endroit)
    logAction(
        'recherche_trajet',
        "Recherche : $depart → $arrivee le $date (" . count($trajets) . " résultat(s))",
        'INFO',
        $_SESSION['user_id'] ?? null
    );

    // Trajets alternatifs depuis villes proches
    $trajetsAlternatifs = chercherTrajetsAlternatifs($bdd, $depart, $arrivee, $date, $trajets);

    // Calcul de la période (futur / en_cours / passé)
    $now = new DateTime();
    foreach ($trajets as $key => $trajet) {
        $trajetDateTime = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
        if ($trajetDateTime > $now) {
            $trajets[$key]['periode'] = 'futur';
        } elseif ($trajetDateTime >= (clone $now)->modify('-2 hours')) {
            $trajets[$key]['periode'] = 'en_cours';
        } else {
            $trajets[$key]['periode'] = 'passe';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un covoiturage</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-recherche-trajet.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include(__DIR__ . '/../COMPONENTS/COMP-header.php'); ?>

<!-- ===== FORMULAIRE DE RECHERCHE ===== -->
<section class="search-section">
    <form method="get">
        <div class="search-container">
            <div class="form-group">
                <label>Je pars de</label>
                <input type="text" name="departure" required value="<?= htmlspecialchars($depart) ?>">
            </div>
            <div class="form-group">
                <label>Je vais à</label>
                <input type="text" name="destination" required value="<?= htmlspecialchars($arrivee) ?>">
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" required value="<?= htmlspecialchars($date) ?>">
            </div>
            <div class="form-group">
                <label>Passagers</label>
                <input type="number" name="passenger" min="1" value="1">
            </div>
            <button class="search-btn" type="submit">
                <img src="../../IMAGES/logo recherche.png" alt="Rechercher">
            </button>
        </div>
    </form>
</section>

<!-- ===== RÉSULTATS ===== -->
<section class="results-section">
    <div class="filters">
        <h2>Filtrer</h2>
        <button type="button" class="fillers-clear-btn">Tout effacer</button>
        <label>
            <input type="checkbox" class="filter-input">
            Trajet écologique
        </label>
        <label>
            Note chauffeur minimale
            <input type="number" step="0.1" class="filter-input">
        </label>
    </div>

    <div class="search-results">

        <?php if (empty($trajets) && empty($trajetsAlternatifs)): ?>
            <p>Aucun trajet trouvé.</p>

        <?php elseif (empty($trajets)): ?>
            <p>Aucun trajet exact trouvé pour cette recherche.</p>

        <?php else: ?>
            <?php foreach ($trajets as $trajet): ?>
                <article class="ride">
                    <div class="ride-driver">
                        <img src="../../IMAGES/antoine.jpg" alt="Chauffeur">
                        <span class="driver-name">
                            <?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?>
                        </span>
                    </div>
                    <div class="ride-content">
                        <h3 class="ride-title">
                            <?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?>
                        </h3>
                        <div class="ride-infos">
                            <p>🕒 <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                            <p>📅 <?= htmlspecialchars($trajet['date_depart']) ?></p>
                            <p>💺 <?= htmlspecialchars($trajet['places_disponibles']) ?> place(s)</p>
                        </div>
                    </div>
                    <div class="ride-action">
                        <a class="ride-btn" href="../UTILISATEUR/USR-details-trajet.php?id=<?= (int)$trajet['id'] ?>">
                            Voir détails
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Trajets alternatifs -->
        <?php if (!empty($trajetsAlternatifs)): ?>
            <h2 class="alternatives-title">🗺️ Trajets depuis villes proches</h2>
            <?php foreach ($trajetsAlternatifs as $trajet): ?>
                <article class="ride ride-alternative">
                    <div class="ride-driver">
                        <img src="../../IMAGES/antoine.jpg" alt="Chauffeur">
                        <span class="driver-name">
                            <?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?>
                        </span>
                    </div>
                    <div class="ride-content">
                        <h3 class="ride-title">
                            <?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?>
                        </h3>
                        <div class="ride-infos">
                            <p>🕒 <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                            <p>📅 <?= htmlspecialchars($trajet['date_depart']) ?></p>
                            <p>💺 <?= htmlspecialchars($trajet['places_disponibles']) ?> place(s)</p>
                        </div>
                    </div>
                    <div class="ride-action">
                        <a class="ride-btn" href="../UTILISATEUR/USR-details-trajet.php?id=<?= (int)$trajet['id'] ?>">
                            Voir détails
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>

<script src="../JS/ecoride_js.js"></script>
<?php include(__DIR__ . '/../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>