<?php
// --- Inclure auth et les trajets ---
require_once __DIR__ . '/../PHP/auth.php';
require_once __DIR__ . '/../PHP/mes_trajets.php';
requireLogin();

// Récupérer l'ID de l'utilisateur connecté
$id_utilisateur = $_SESSION['id'] ?? 0;

// --- TRI PAR DATE ---
$trajetsFutur = [];
$trajetsEnCours = [];
$trajetsTermine = [];

$now = new DateTime();

foreach ($trajets as $trajet) {
    $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);

    if ($dateTrajet > $now) {
        $trajetsFutur[] = $trajet;
    } elseif ($dateTrajet <= $now && $dateTrajet >= (clone $now)->modify('-2 hours')) {
        $trajetsEnCours[] = $trajet;
    } else {
        $trajetsTermine[] = $trajet;
    }
}

// --- Fonction pour afficher une carte trajet ---
function afficherTrajet($trajet, $bdd, $dateTrajet, $id_utilisateur) {
    $passagers = getPassagers($bdd, $trajet['id']);

    echo '<div class="trip-card">';
    
    echo '<p><strong>Date :</strong> ' . htmlspecialchars($trajet['date_depart']) . ' à ' . htmlspecialchars($trajet['heure_depart']) . '</p>';
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

    // ✅ BOUTON VOIR DÉTAILS
    echo '<a href="USR-details-trajet.php?id=' . $trajet['id'] . '" class="btn-details">Voir détails</a>';

    // ⚠️ Modifier et supprimer uniquement si le trajet appartient à l'utilisateur et n'est pas passé
    if ($trajet['id_conducteur'] == $id_utilisateur && $dateTrajet > new DateTime()) {
        echo '<a href="USR-details-trajet.php?id=' . $trajet['id'] . '&edit=1" class="btn-modifier" style="margin-left:10px;">Modifier</a>';
        echo '<form action="../PHP/supprimer-trajet.php" method="POST" onsubmit="return confirm(\'Voulez-vous vraiment supprimer ce trajet ?\');" style="display:inline; margin-left:10px;">
                <input type="hidden" name="id_trajet" value="' . $trajet['id'] . '">
                <button type="submit" class="cta-supprimer">Supprimer</button>
              </form>';
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

<!-- Header -->
<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>

<!-- Menu -->
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

    <!-- À venir -->
    <div id="upcoming">
        <h3>Trajets à venir</h3>
        <?php
        if (!empty($trajetsFutur)) {
            foreach ($trajetsFutur as $trajet) {
                $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
                afficherTrajet($trajet, $bdd, $dateTrajet, $id_utilisateur);
            }
        } else {
            echo '<p>Aucun trajet à venir.</p>';
        }
        ?>
    </div>

    <!-- En cours -->
    <div id="ongoing">
        <h3>Trajets en cours</h3>
        <?php
        if (!empty($trajetsEnCours)) {
            foreach ($trajetsEnCours as $trajet) {
                $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
                afficherTrajet($trajet, $bdd, $dateTrajet, $id_utilisateur);
            }
        } else {
            echo '<p>Aucun trajet en cours.</p>';
        }
        ?>
    </div>

    <!-- Passés -->
    <div id="past">
        <h3>Historique des trajets</h3>
        <div class="past-trips">
        <?php
        if (!empty($trajetsTermine)) {
            foreach ($trajetsTermine as $trajet) {
                $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
                afficherTrajet($trajet, $bdd, $dateTrajet, $id_utilisateur);
            }
        } else {
            echo '<p>Aucun trajet passé.</p>';
        }
        ?>
        </div>
    </div>
</section>

<!-- Toast succès suppression -->
<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<div id="toast-success" class="toast-success">
    ✅ Trajet supprimé avec succès !
</div>
<?php endif; ?>

</main>

<!-- Footer -->
<?php include('../COMPONENTS/COMP-footer.php'); ?>

<!-- Scripts -->
<script src="../JS/USR-mes-trajets.js"></script>
<script src="../JS/USR-toast.js"></script>

</body>
</html>