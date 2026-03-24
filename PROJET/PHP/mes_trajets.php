<?php
// 🔒 Sécurité et session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure connexion BDD
require_once __DIR__ . '/connexion.php'; // $bdd

// Récupérer l'ID de l'utilisateur connecté
$id_utilisateur = $_SESSION['user_id'] ?? null;
if (!$id_utilisateur) {
    die("Erreur : utilisateur non connecté.");
}

// --- Récupérer les trajets où l'utilisateur est conducteur ---
try {
    $stmt = $bdd->prepare("
        SELECT *, 'conducteur' AS role
        FROM trajets
        WHERE id_conducteur = ?
    ");
    $stmt->execute([$id_utilisateur]);
    $trajetsConducteur = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur PDO (conducteur) : " . htmlspecialchars($e->getMessage()));
}

// --- Récupérer les trajets où l'utilisateur est passager ---
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

// --- Fusionner les trajets ---
$trajets = array_merge($trajetsConducteur, $trajetsPassager);

// --- Fonction pour récupérer les passagers d'un trajet ---
function getPassagers($bdd, $id_trajet) {
    $stmt = $bdd->prepare("
        SELECT u.nom, u.prenom, tp.note_passager, tp.statut
        FROM trajets_passagers tp
        JOIN utilisateurs u ON tp.id_passager = u.id
        WHERE tp.id_trajet = ?
    ");
    $stmt->execute([$id_trajet]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- Fonction pour afficher un trajet ---
function afficherTrajet($trajet, $bdd, $id_utilisateur) {
    $passagers = getPassagers($bdd, $trajet['id']);
    $dateTrajet = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);

    echo '<div class="trip-card">';
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

    // Modifier / supprimer seulement si utilisateur conducteur et trajet futur
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