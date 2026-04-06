<?php
// 🔒 Sécurité et session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/connexion.php';

$id_utilisateur = $_SESSION['user_id'] ?? null;
if (!$id_utilisateur) {
    die("Erreur : utilisateur non connecté.");
}

// --- Trajets conducteur ---
try {
    $stmt = $bdd->prepare("SELECT *, 'conducteur' AS role FROM trajets WHERE id_conducteur = ?");
    $stmt->execute([$id_utilisateur]);
    $trajetsConducteur = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur PDO (conducteur) : " . htmlspecialchars($e->getMessage()));
}

// --- Trajets passager ---
try {
    $stmt = $bdd->prepare("
        SELECT t.*, 'passager' AS role
        FROM trajets t
        JOIN trajets_passagers tp ON t.id = tp.id_trajet
        WHERE tp.id_passager = ?
    ");
    $stmt->execute([$id_utilisateur]);
    $trajetsPassager = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur PDO (passager) : " . htmlspecialchars($e->getMessage()));
}

$trajets = array_merge($trajetsConducteur, $trajetsPassager);

$trajetsFutur = [];
$trajetsEnCours = [];
$trajetsTermine = [];
$now = new DateTime();

foreach ($trajets as $trajet) {
    $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);

    if ($trajet['statut'] === 'termine') {
        $trajetsTermine[] = $trajet;
    } elseif ($trajet['statut'] === 'en_cours') {
        $trajetsEnCours[] = $trajet;
    } elseif ($dateTrajet > $now) {
        $trajetsFutur[] = $trajet;
    } else {
        $trajetsTermine[] = $trajet;
    }
}

function getPassagers($bdd, $id_trajet) {
    $stmt = $bdd->prepare("
        SELECT u.id, u.nom, u.prenom, tp.note_passager, tp.statut
        FROM trajets_passagers tp
        JOIN utilisateurs u ON tp.id_passager = u.id
        WHERE tp.id_trajet = ?
    ");
    $stmt->execute([$id_trajet]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function afficherTrajet($trajet, $bdd, $id_utilisateur) {
    $passagers = getPassagers($bdd, $trajet['id']);

    echo '<div class="trip-card">';
    echo '<p>DEBUG statut: ' . $trajet['statut'] . ' | role: ' . $trajet['role'] . '</p>';
    echo '<p><strong>Date :</strong> ' . htmlspecialchars($trajet['date_depart']) . ' à ' . htmlspecialchars($trajet['heure_depart']) . '</p>';
    echo '<p><strong>Départ :</strong> ' . htmlspecialchars($trajet['depart']) . '</p>';
    echo '<p><strong>Arrivée :</strong> ' . htmlspecialchars($trajet['arrivee']) . '</p>';

    if (!empty($trajet['etapes'])) {
        echo '<p><strong>Étapes :</strong> ' . htmlspecialchars($trajet['etapes']) . '</p>';
    }

    if (!empty($passagers)) {
        $listePassagers = array_map(fn($p) => htmlspecialchars($p['nom'] . ' ' . $p['prenom']), $passagers);
        echo '<p><strong>Passagers :</strong> ' . implode(', ', $listePassagers) . '</p>';
    }

    echo '<p><strong>Rôle :</strong> ' . ($trajet['role'] === 'conducteur' ? 'Conducteur' : 'Passager') . '</p>';
    echo '<a href="USR-details-trajet.php?id=' . $trajet['id'] . '" class="btn-details">Voir détails</a>';

    // Boutons conducteur
    if ($trajet['role'] === 'conducteur') {
$heureTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
$uneHureAvant = (clone $heureTrajet)->modify('-1 hour');
$now = new DateTime();

if (($trajet['statut'] === 'publie' || $trajet['statut'] === 'complet') && $now >= $uneHureAvant) {            echo '
            <form action="../PHP/demarrer-trajet.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_trajet" value="' . $trajet['id'] . '">
                <button type="submit" class="btn-demarrer" onclick="return confirm(\'Démarrer ce trajet ?\')">🚗 Démarrer</button>
            </form>';
        } elseif ($trajet['statut'] === 'en_cours') {
            echo '
            <form action="../PHP/terminer-trajet.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_trajet" value="' . $trajet['id'] . '">
                <button type="submit" class="btn-terminer" onclick="return confirm(\'Confirmer l\'arrivée à destination ?\')">✅ Arrivée à destination</button>
            </form>';
        }
    }

    // Bouton validation passager après trajet terminé
    if ($trajet['role'] === 'passager' && $trajet['statut'] === 'termine') {
        // Vérifier si le passager a déjà validé
        $dejValide = false;
foreach ($passagers as $p) {
    if ($p['id'] == $id_utilisateur && ($p['statut'] === 'valide' || $p['statut'] === 'litige')) {
        $dejValide = true;
        break;
    }
}
        if (!$dejValide) {
            echo '
            <div class="validation-trajet">
                <p>Ce trajet est terminé. Comment s\'est-il passé ?</p>
                <form action="../PHP/valider-trajet.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_trajet" value="' . $trajet['id'] . '">
                    <button type="submit" name="validation" value="ok" class="btn-ok">👍 Tout s\'est bien passé</button>
                    <button type="submit" name="validation" value="probleme" class="btn-probleme">👎 Problème</button>
                </form>
            </div>';
        }
    }

    echo '</div>';
}