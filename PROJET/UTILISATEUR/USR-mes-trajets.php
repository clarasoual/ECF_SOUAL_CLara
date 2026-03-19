<?php
// --- Afficher toutes les erreurs ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Inclure auth et les trajets ---
require_once __DIR__ . '/../PHP/auth.php';
require_once __DIR__ . '/../PHP/mes_trajets.php';

// --- Trier les trajets par statut ---
$trajetsFutur = [];
$trajetsEnCours = [];
$trajetsTermine = [];

foreach ($trajets as $trajet) {
    switch ($trajet['statut']) {
        case 'futur':
            $trajetsFutur[] = $trajet;
            break;
        case 'en_cours':
            $trajetsEnCours[] = $trajet;
            break;
        case 'termine':
            $trajetsTermine[] = $trajet;
            break;
    }
}

// --- Fonction pour afficher une carte trajet ---
function afficherTrajet($trajet, $bdd) {
    $passagers = getPassagers($bdd, $trajet['id']);
    echo '<div class="trip-card">';
    echo '<p><strong>Date :</strong> ' . htmlspecialchars($trajet['date_depart']) . '</p>';
    echo '<p><strong>Départ :</strong> ' . htmlspecialchars($trajet['depart']) . '</p>';
    echo '<p><strong>Arrivée :</strong> ' . htmlspecialchars($trajet['arrivee']) . '</p>';
    if (!empty($trajet['etapes'])) {
        echo '<p><strong>Étapes :</strong> ' . htmlspecialchars($trajet['etapes']) . '</p>';
    }
    if (!empty($passagers)) {
        echo '<p><strong>Passagers :</strong> ';
        $listePassagers = array_map(function($p) { 
            return htmlspecialchars($p['nom'] . ' ' . $p['prenom']); 
        }, $passagers);
        echo implode(', ', $listePassagers);
        echo '</p>';
    }
    echo '</div>';
}
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

<!-- Header commun -->
<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>

<!-- Menu latéral -->
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

    <!-- Trajets à venir -->
    <div id="upcoming">
        <h3>Trajets à venir</h3>
        <?php
        if (!empty($trajetsFutur)) {
            foreach ($trajetsFutur as $trajet) {
                afficherTrajet($trajet, $bdd);
            }
        } else {
            echo '<p>Aucun trajet à venir.</p>';
        }
        ?>
    </div>

    <!-- Trajets en cours -->
    <div id="ongoing">
        <h3>Trajets en cours</h3>
        <?php
        if (!empty($trajetsEnCours)) {
            foreach ($trajetsEnCours as $trajet) {
                afficherTrajet($trajet, $bdd);
            }
        } else {
            echo '<p>Aucun trajet en cours.</p>';
        }
        ?>
    </div>

    <!-- Trajets passés -->
    <div id="past">
        <h3>Historique des trajets</h3>
        <div class="past-trips">
        <?php
        if (!empty($trajetsTermine)) {
            foreach ($trajetsTermine as $trajet) {
                afficherTrajet($trajet, $bdd);
            }
        } else {
            echo '<p>Aucun trajet passé.</p>';
        }
        ?>
        </div>
    </div>
</section>

<script src="../JS/USR-mes-trajets.js"></script>
</main>

<!-- Footer commun -->
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>