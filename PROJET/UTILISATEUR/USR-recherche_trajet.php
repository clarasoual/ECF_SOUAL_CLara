<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../PHP/connexion.php');
require_once(__DIR__ . '/../PHP/recherche_trajets.php');

$trajets  = [];
$depart   = '';
$arrivee  = '';
$date     = '';
$prochain = null;
$erreurs  = [];

if (isset($_GET['departure'], $_GET['destination'], $_GET['date'])) {

    $depart  = trim($_GET['departure']);
    $arrivee = trim($_GET['destination']);
    $date    = trim($_GET['date']);

    $regexVille = '/^[a-zA-ZÀ-ÿ0-9\s\-\',\.]{1,100}$/u';

    if (empty($depart)) {
        $erreurs[] = "La ville de départ est obligatoire.";
    } elseif (!preg_match($regexVille, $depart)) {
        $erreurs[] = "La ville de départ contient des caractères non autorisés.";
        $depart = '';
    }

    if (empty($arrivee)) {
        $erreurs[] = "La ville d'arrivée est obligatoire.";
    } elseif (!preg_match($regexVille, $arrivee)) {
        $erreurs[] = "La ville d'arrivée contient des caractères non autorisés.";
        $arrivee = '';
    } elseif (!empty($depart) && strtolower($depart) === strtolower($arrivee)) {
        $erreurs[] = "La ville d'arrivée doit être différente de la ville de départ.";
        $arrivee = '';
    }

    if (empty($date)) {
        $erreurs[] = "La date est obligatoire.";
    } else {
        $dateObj    = DateTime::createFromFormat('Y-m-d', $date);
        $dateValide = $dateObj && $dateObj->format('Y-m-d') === $date;

        if (!$dateValide) {
            $erreurs[] = "Le format de la date est invalide.";
            $date = '';
        } else {
            $aujourd_hui = new DateTime();
            $aujourd_hui->setTime(0, 0, 0);
            if ($dateObj < $aujourd_hui) {
                $erreurs[] = "La date ne peut pas être dans le passé.";
                $date = '';
            }
        }
    }

    $passenger = isset($_GET['passenger']) ? (int) $_GET['passenger'] : 1;
    if ($passenger < 1) $passenger = 1;
    if ($passenger > 8) $passenger = 8;

    if (empty($erreurs)) {
        $trajets = chercherTrajets($bdd, $depart, $arrivee, $date);

        logAction(
            'recherche_trajet',
            "Recherche : $depart → $arrivee le $date (" . count($trajets) . " résultat(s))",
            'INFO',
            $_SESSION['user_id'] ?? null
        );

        if (empty($trajets)) {
            $prochain = prochainTrajetDisponible($bdd, $depart, $arrivee, $date);
        }

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

            // Formatage heure HH:MM
            $trajets[$key]['heure_depart_fmt']  = substr($trajet['heure_depart'], 0, 5);
            $trajets[$key]['heure_arrivee_fmt'] = !empty($trajet['heure_arrivee']) ? substr($trajet['heure_arrivee'], 0, 5) : '';

            // Formatage date dd/mm/YYYY
            $trajets[$key]['date_depart_fmt'] = date('d/m/Y', strtotime($trajet['date_depart']));

            // Calcul durée en minutes pour le filtre JS
            if (!empty($trajet['heure_depart']) && !empty($trajet['heure_arrivee'])) {
                [$hD, $mD] = explode(':', $trajet['heure_depart']);
                [$hA, $mA] = explode(':', $trajet['heure_arrivee']);
                $duree = ((int)$hA * 60 + (int)$mA) - ((int)$hD * 60 + (int)$mD);
                if ($duree < 0) $duree += 24 * 60;
                $trajets[$key]['duree_minutes'] = $duree;
            } else {
                $trajets[$key]['duree_minutes'] = -1;
            }
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

<section class="search-section">
    <form method="get" novalidate>
        <div class="search-container">
            <div class="form-group">
                <label>Je pars de</label>
                <input type="text" name="departure"
                       value="<?= htmlspecialchars($depart, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Je vais à</label>
                <input type="text" name="destination"
                       value="<?= htmlspecialchars($arrivee, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date"
                       value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Passagers</label>
                <input type="number" name="passenger" min="1" max="8"
                       value="<?= isset($passenger) ? (int)$passenger : 1 ?>">
            </div>
            <button class="search-btn" type="submit">
                <img src="../../IMAGES/logo recherche.png" alt="Rechercher">
            </button>
        </div>
    </form>
</section>

<section class="results-section">
    <div class="filters">
        <h2>Filtrer</h2>
        <button type="button" class="fillers-clear-btn">Tout effacer</button>

        <label>
            <input type="checkbox" class="filter-input" id="filter-eco">
            Trajet écologique 🌿
        </label>

        <label>
            Prix maximum (crédits)
            <input type="number" min="0" class="filter-input" id="filter-prix">
        </label>

        <label>
            Durée maximum (heures)
            <input type="number" min="0" step="0.5" class="filter-input" id="filter-duree"
                   placeholder="ex: 2.5">
        </label>

        <label>
            Note chauffeur minimale
            <input type="number" step="0.1" min="0" max="5" class="filter-input" id="filter-note">
        </label>
    </div>

    <div class="search-results">

        <?php if (empty($trajets) && !isset($_GET['departure'])): ?>
            <p>Effectuez une recherche pour voir les trajets disponibles.</p>

        <?php elseif (!empty($erreurs)): ?>
            <p>Veuillez corriger les erreurs dans le formulaire pour lancer la recherche.</p>

        <?php elseif (empty($trajets)): ?>
            <p>Aucun trajet trouvé pour cette recherche.</p>
            <?php if ($prochain): ?>
                <p>Le prochain trajet disponible sur cet itinéraire est le
                    <strong><?= date('d/m/Y', strtotime($prochain['date_depart'])) ?></strong>.
                </p>
                <a href="?departure=<?= urlencode($depart) ?>&destination=<?= urlencode($arrivee) ?>&date=<?= urlencode($prochain['date_depart']) ?>&passenger=1" class="ride-btn">
                    Voir les trajets du <?= date('d/m/Y', strtotime($prochain['date_depart'])) ?>
                </a>
            <?php endif; ?>

        <?php else: ?>
            <?php foreach ($trajets as $trajet): ?>
                <?php $eco = strtolower($trajet['carburant'] ?? '') === 'electrique'; ?>
                <article class="ride"
                    data-eco="<?= $eco ? '1' : '0' ?>"
                    data-note="<?= (float)$trajet['note_moyenne'] ?>"
                    data-prix="<?= (float)$trajet['prix'] ?>"
                    data-duree="<?= (int)$trajet['duree_minutes'] ?>">

                    <div class="ride-driver">
                        <img src="../../IMAGES/profiles/<?= htmlspecialchars($trajet['photo_conducteur'] ?? 'default.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="Chauffeur">
                        <span class="driver-name">
                            <?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <?php if ($trajet['note_moyenne'] > 0): ?>
                            <span class="driver-note">⭐ <?= number_format($trajet['note_moyenne'], 1) ?>/5</span>
                        <?php else: ?>
                            <span class="driver-note">Aucun avis</span>
                        <?php endif; ?>
                    </div>

                    <div class="ride-content">
                        <h3 class="ride-title">
    <?= htmlspecialchars($trajet['depart'], ENT_QUOTES, 'UTF-8') ?>
    → <?= htmlspecialchars($trajet['arrivee'], ENT_QUOTES, 'UTF-8') ?>
</h3>
<?php if (!empty($trajet['etapes'])): ?>
    <?php $etapes = json_decode($trajet['etapes'], true); ?>
    <?php $etapes = array_filter($etapes ?? []); ?>
    <?php if (!empty($etapes)): ?>
        <p class="ride-arrets">Passant par : <?= htmlspecialchars(implode(', ', $etapes), ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
<?php endif; ?>
                        <div class="ride-infos">
                            <p>📅 <?= $trajet['date_depart_fmt'] ?></p>
                            <p>🕒 Départ : <?= $trajet['heure_depart_fmt'] ?></p>
                            <?php if (!empty($trajet['heure_arrivee_fmt'])): ?>
                                <p>🏁 Arrivée : <?= $trajet['heure_arrivee_fmt'] ?></p>
                                <?php if ($trajet['duree_minutes'] > 0): ?>
                                    <p>⏱️ Durée : <?= floor($trajet['duree_minutes'] / 60) ?>h<?= str_pad($trajet['duree_minutes'] % 60, 2, '0', STR_PAD_LEFT) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <p>💺 <?= (int)$trajet['places_disponibles'] ?> place(s)</p>
                            <p>💳 <?= (int)$trajet['prix'] ?> crédit(s)</p>
                            <?php if ($eco): ?>
                                <p class="eco-badge">🌿 Trajet écologique</p>
                            <?php endif; ?>
                        </div>
                        <?php if ($trajet['statut'] === 'complet'): ?>
                            <p class="trajet-complet">🚫 Complet</p>
                        <?php endif; ?>
                    </div>

                    <div class="ride-action">
                        <?php if ($trajet['statut'] === 'complet'): ?>
                            <span class="ride-btn disabled">Complet</span>
                        <?php else: ?>
                            <a class="ride-btn" href="../UTILISATEUR/USR-details-trajet.php?id=<?= (int)$trajet['id'] ?>">
                                Voir détails
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>

<script>
const btnClear    = document.querySelector('.fillers-clear-btn');
const filterEco   = document.getElementById('filter-eco');
const filterNote  = document.getElementById('filter-note');
const filterPrix  = document.getElementById('filter-prix');
const filterDuree = document.getElementById('filter-duree');

function appliquerFiltres() {
    const eco      = filterEco.checked;
    const noteMin  = parseFloat(filterNote.value) || 0;
    const prixMax  = parseFloat(filterPrix.value) || Infinity;
    const dureeMax = filterDuree.value ? parseFloat(filterDuree.value) * 60 : Infinity;

    document.querySelectorAll('.ride').forEach(ride => {
        const rideEco   = ride.dataset.eco === '1';
        const rideNote  = parseFloat(ride.dataset.note);
        const ridePrix  = parseFloat(ride.dataset.prix);
        const rideDuree = parseInt(ride.dataset.duree);

        const dureeOk = dureeMax === Infinity || rideDuree === -1 || rideDuree <= dureeMax;

        const ok = (!eco || rideEco) &&
                   (rideNote >= noteMin) &&
                   (ridePrix <= prixMax) &&
                   dureeOk;

        ride.style.display = ok ? '' : 'none';
    });
}

filterEco.addEventListener('change', appliquerFiltres);
filterNote.addEventListener('input', appliquerFiltres);
filterPrix.addEventListener('input', appliquerFiltres);
filterDuree.addEventListener('input', appliquerFiltres);

btnClear.addEventListener('click', () => {
    filterEco.checked = false;
    filterNote.value  = '';
    filterPrix.value  = '';
    filterDuree.value = '';
    appliquerFiltres();
});
</script>

<script src="../JS/USR-recherche_trajet.js"></script>
<script src="../JS/ecoride_js.js"></script>
<?php include(__DIR__ . '/../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>