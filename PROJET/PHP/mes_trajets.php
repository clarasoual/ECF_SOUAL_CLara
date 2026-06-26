<?php
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

$trajetsFutur   = [];
$trajetsEnCours = [];
$trajetsTermine = [];

foreach ($trajets as $trajet) {
    $statut = $trajet['statut'];

    if ($statut === 'termine') {
        $trajetsTermine[] = $trajet;
    } elseif ($statut === 'en_cours') {
        $trajetsEnCours[] = $trajet;
    } else {
        $trajetsFutur[] = $trajet;
    }
}

$sortDesc = fn($a, $b) => strcmp($b['date_depart'] . $b['heure_depart'], $a['date_depart'] . $a['heure_depart']);
$sortAsc  = fn($a, $b) => strcmp($a['date_depart'] . $a['heure_depart'], $b['date_depart'] . $b['heure_depart']);

usort($trajetsFutur,   $sortAsc);
usort($trajetsEnCours, $sortAsc);
usort($trajetsTermine, $sortDesc);

function getPassagers($bdd, $id_trajet) {
    $stmt = $bdd->prepare("
        SELECT u.id, u.nom, u.prenom, tp.statut
        FROM trajets_passagers tp
        JOIN utilisateurs u ON tp.id_passager = u.id
        WHERE tp.id_trajet = ?
    ");
    $stmt->execute([$id_trajet]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function statutAvisPassagers($bdd, $id_trajet, $id_conducteur) {
    $stmtTotal = $bdd->prepare("
        SELECT COUNT(*) FROM trajets_passagers
        WHERE id_trajet = ? AND statut NOT IN ('annule')
    ");
    $stmtTotal->execute([$id_trajet]);
    $total = (int)$stmtTotal->fetchColumn();

    if ($total === 0) return 'aucun_passager';

    $stmtAvis = $bdd->prepare("
        SELECT COUNT(*) FROM avis
        WHERE id_trajet = ? AND id_auteur = ?
    ");
    $stmtAvis->execute([$id_trajet, $id_conducteur]);
    $nbAvis = (int)$stmtAvis->fetchColumn();

    if ($nbAvis === 0)     return 'aucun';
    if ($nbAvis >= $total) return 'tous';
    return 'partiel';
}

function afficherTrajet($trajet, $bdd, $id_utilisateur) {
    $passagers = getPassagers($bdd, $trajet['id']);

    $date_fmt  = date('d/m/Y', strtotime($trajet['date_depart']));
    $heure_fmt = substr($trajet['heure_depart'], 0, 5);

    $etapes_fmt = '';
    if (!empty($trajet['etapes'])) {
        $etapes = json_decode($trajet['etapes'], true);
        if (is_array($etapes)) {
            $etapes = array_filter($etapes);
            if (!empty($etapes)) {
                $etapes_fmt = implode(', ', array_map('htmlspecialchars', $etapes));
            }
        }
    }

    echo '<div class="trip-card">';
    echo '<p><strong>Date :</strong> ' . $date_fmt . ' à ' . $heure_fmt . '</p>';
    echo '<p><strong>Départ :</strong> ' . htmlspecialchars($trajet['depart']) . '</p>';
    echo '<p><strong>Arrivée :</strong> ' . htmlspecialchars($trajet['arrivee']) . '</p>';

    if (!empty($etapes_fmt)) {
        echo '<p><strong>Arrêts :</strong> ' . $etapes_fmt . '</p>';
    }

    if (!empty($passagers)) {
        $listePassagers = array_map(fn($p) => htmlspecialchars($p['prenom'] . ' ' . $p['nom']), $passagers);
        echo '<p><strong>Passagers :</strong> ' . implode(', ', $listePassagers) . '</p>';
    }

    echo '<p><strong>Rôle :</strong> ' . ($trajet['role'] === 'conducteur' ? 'Conducteur' : 'Passager') . '</p>';

    echo '<div class="trip-card-actions">';
    echo '<a href="USR-details-trajet.php?id=' . $trajet['id'] . '" class="btn-details">Voir détails</a>';

    // --- Boutons conducteur ---
    if ($trajet['role'] === 'conducteur') {
        $heureTrajet   = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
        $uneHeureAvant = (clone $heureTrajet)->modify('-1 hour');
        $now           = new DateTime();

        if (($trajet['statut'] === 'publie' || $trajet['statut'] === 'complet') && $now >= $uneHeureAvant) {
            echo '
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

        if ($trajet['statut'] === 'termine' && !empty($passagers)) {
            $statutAvis = statutAvisPassagers($bdd, $trajet['id'], $id_utilisateur);

            if ($statutAvis === 'tous') {
                echo '<span class="badge-avis badge-vert">✅ Passagers notés</span>';
            } elseif ($statutAvis === 'partiel') {
                echo '<a href="USR-avis-passager.php?id_trajet=' . $trajet['id'] . '" class="btn-avis">⭐ Continuer les avis</a>';
            } else {
                echo '<a href="USR-avis-passager.php?id_trajet=' . $trajet['id'] . '" class="btn-avis">⭐ Noter mes passagers</a>';
            }
        }
    }

    // --- Badge avis passager après trajet terminé ---
    if ($trajet['role'] === 'passager' && $trajet['statut'] === 'termine') {
        $statutPassager = '';
        foreach ($passagers as $p) {
            if ($p['id'] == $id_utilisateur) {
                $statutPassager = $p['statut'];
                break;
            }
        }

        if ($statutPassager === 'avis_laisse') {
            echo '<span class="badge-avis badge-vert">✅ Avis laissé</span>';
        } elseif ($statutPassager === 'litige') {
            echo '<span class="badge-avis badge-rouge">🚨 Litige signalé</span>';
        } else {
            echo '<a href="USR-avis-trajet.php?id_trajet=' . $trajet['id'] . '" class="btn-avis">⭐ Laisser un avis</a>';
        }
    }

    echo '</div>';
    echo '</div>';
}
