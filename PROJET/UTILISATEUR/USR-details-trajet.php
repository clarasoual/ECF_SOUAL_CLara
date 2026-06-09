<?php
require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); 
require_once __DIR__ . '/../PHP/details_trajet.php';

error_reporting(0);
ini_set('display_errors', 0);

$success  = isset($_GET['success']) && $_GET['success'] == 1;
$isOwner  = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $trajet['id_conducteur'];

$eco = strtolower($trajet['carburant'] ?? '') === 'electrique';

$date_fmt          = date('d/m/Y', strtotime($trajet['date_depart']));
$heure_depart_fmt  = substr($trajet['heure_depart'], 0, 5);
$heure_arrivee_fmt = !empty($trajet['heure_arrivee']) ? substr($trajet['heure_arrivee'], 0, 5) : '';

$vehicules = [];
if ($isOwner) {
    $stmt = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = :id_user");
    $stmt->execute([':id_user' => $_SESSION['user_id']]);
    $vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$etapes = [];
if (!empty($trajet['etapes'])) {
    $etapes = json_decode($trajet['etapes'], true);
    if (!is_array($etapes)) $etapes = [];
    $etapes = array_filter($etapes);
}

$trajetDateTime = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
$now            = new DateTime();
$isPast         = $trajetDateTime < $now || $trajet['statut'] === 'termine';
$trajetTermine  = ($trajet['statut'] === 'termine');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du trajet</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-details-trajets.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<?php if ($success): ?>
    <div id="toast-success" class="toast-success">✅ Trajet modifié avec succès !</div>
<?php endif; ?>

<main>
    <a href="USR-recherche_trajet.php" class="back-arrow">← Retour</a>
    <h1 class="page-title">Détails du trajet</h1>

    <div class="cards-wrapper">

        <!-- CARTE GAUCHE : Conducteur -->
        <section class="card driver-card">
            <h3 class="card-title">🧑 Conducteur</h3>
            <div class="driver-main">
                <a href="USR-profil.php?id=<?= (int)$trajet['id_conducteur'] ?>">
                    <img src="../../IMAGES/profiles/<?= htmlspecialchars($trajet['photo_conducteur'] ?? 'default.jpg') ?>" 
                         alt="Photo du conducteur" class="driver-photo-lg">
                </a>
                <div class="driver-infos">
                    <a href="USR-profil.php?id=<?= (int)$trajet['id_conducteur'] ?>" class="driver-name-link">
                        <h2><?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?></h2>
                    </a>
                    <p class="driver-rating">
                        <?php if ($nb_avis > 0): ?>
                            ⭐ <?= $note_moyenne ?> / 5 · <?= $nb_avis ?> avis
                        <?php else: ?>
                            Aucun avis pour le moment
                        <?php endif; ?>
                    </p>
                    <p class="driver-car">
                        🚗 <?= htmlspecialchars($trajet['marque'] . ' ' . $trajet['modele']) ?> ·
                        <?= htmlspecialchars($trajet['couleur']) ?> ·
                        <?= htmlspecialchars($trajet['carburant']) ?>
                        <?php if ($eco): ?>
                            <span class="eco-badge">🌿 Écologique</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div id="driver-reviews" class="driver-reviews">
                <h4>
                    <?= $trajetTermine ? 'Avis sur le conducteur pour ce trajet' : 'Avis sur le conducteur' ?>
                </h4>
                <?php if (empty($avis)): ?>
                    <p class="empty">
                        <?= $trajetTermine ? 'Aucun avis laissé pour ce trajet.' : 'Aucun avis pour le moment.' ?>
                    </p>
                <?php else: ?>
                    <?php foreach ($avis as $a): ?>
                        <div class="review">
                            <p class="review-author">⭐ <?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?> – <?= $a['note'] ?>/5</p>
                            <p class="review-text"><?= htmlspecialchars($a['commentaire']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- CARTE DROITE : Trajet -->
        <section class="card trajet-card">
            <h3 class="card-title">🗺️ Itinéraire</h3>

            <div class="trajet-path">
                <div class="ville">
                    <a href="https://www.google.com/maps/search/<?= urlencode($trajet['depart']) ?>" target="_blank">
                        📍 <?= htmlspecialchars($trajet['depart']) ?>
                    </a>
                </div>
                <span class="arrow">→</span>
                <div class="ville">
                    <a href="https://www.google.com/maps/search/<?= urlencode($trajet['arrivee']) ?>" target="_blank">
                        📍 <?= htmlspecialchars($trajet['arrivee']) ?>
                    </a>
                </div>
                <?php if (!empty($etapes)): ?>
                    <div class="trajet-etapes">
                        Passant par : <?= htmlspecialchars(implode(', ', $etapes), ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="trajet-details">
                <div class="detail-item">
                    <span class="detail-label">📅 Date</span>
                    <span class="detail-value"><?= $date_fmt ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">🕒 Départ</span>
                    <span class="detail-value"><?= $heure_depart_fmt ?></span>
                </div>
                <?php if (!empty($heure_arrivee_fmt)): ?>
                <div class="detail-item">
                    <span class="detail-label">🏁 Arrivée</span>
                    <span class="detail-value"><?= $heure_arrivee_fmt ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-item">
                    <span class="detail-label">💺 Places</span>
                    <span class="detail-value"><?= htmlspecialchars($trajet['places_disponibles']) ?> disponible(s)</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">💳 Prix</span>
                    <span class="detail-value"><?= (int)$trajet['prix'] ?> crédit(s) / passager</span>
                </div>
                <?php if (!empty($trajet['commentaire'])): ?>
                <div class="detail-item detail-item--full">
                    <span class="detail-label">💬 Rendez-vous</span>
                    <span class="detail-value"><?= htmlspecialchars($trajet['commentaire']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="passengers-section">
                <h4>Passagers inscrits</h4>
                <?php if (empty($passagers)): ?>
                    <p>Aucun passager inscrit pour le moment.</p>
                <?php else: ?>
                    <div class="passengers-list">
                        <?php foreach ($passagers as $p): ?>
                            <a href="USR-profil.php?id=<?= (int)$p['id'] ?>" class="passenger-card">
                                <img src="../../IMAGES/profiles/<?= htmlspecialchars($p['photo'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($p['prenom']) ?>" class="passenger-photo">
                                <p class="passenger-name"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div class="cta-container">
        <?php if ($isPast): ?>
            <p>Ce trajet est terminé ✅</p>
        <?php else: ?>
            <?php if ($isOwner): ?>
                <button id="btn-modifier" class="cta-modifier">Modifier</button>
                <form action="../PHP/supprimer-trajet.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');" style="display:inline;">
                    <input type="hidden" name="id_trajet" value="<?= $trajet['id'] ?>">
                    <button type="submit" class="cta-supprimer">Supprimer</button>
                </form>
            <?php endif; ?>
            <?php if ($isPassenger): ?>
                <form action="../PHP/desinscrire-trajet.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_trajet" value="<?= $trajet['id'] ?>">
                    <button type="submit" class="cta-desinscrire">Se désinscrire</button>
                </form>
            <?php elseif (!$isOwner && !$isPassenger): ?>
                <a href="../PHP/reserver-trajet.php?id=<?= $trajet['id'] ?>" class="cta-reserver">Réserver ce trajet</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<!-- MODAL MODIFIER -->
<?php if ($isOwner && !$isPast): ?>
<div id="modal-modifier" class="modal">
    <div class="modal-content modal-content--wide">
        <div class="modal-header">
            <h2>Modifier votre trajet</h2>
            <span class="close-modal">&times;</span>
        </div>

        <form action="../PHP/modifier-trajet-traitement.php" method="POST">
            <input type="hidden" name="id_trajet" value="<?= $trajet['id'] ?>">

            <div class="modal-columns">

                <div class="modal-card">
                    <h3 class="modal-card-title">🗺️ Départ</h3>
                    <div class="form-group">
                        <label for="departure">Adresse de départ *</label>
                        <input type="text" id="departure" name="departure" value="<?= htmlspecialchars($trajet['depart']) ?>">
                    </div>
                    <label>Arrêts (optionnel)</label>
                    <div id="etapes-container">
                        <?php foreach ($etapes as $i => $etape): ?>
                            <div class="stop-container">
                                <input type="text" name="step<?= $i+1 ?>" placeholder="Arrêt n°<?= $i+1 ?>" value="<?= htmlspecialchars($etape) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="add-stop-btn">+ Ajouter un arrêt</button>
                    <div class="form-group" style="margin-top:1rem;">
                        <label for="date">Date de départ *</label>
                        <input type="date" id="date" name="date" value="<?= htmlspecialchars($trajet['date_depart']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="time">Heure de départ *</label>
                        <input type="time" id="time" name="time" value="<?= htmlspecialchars($trajet['heure_depart']) ?>">
                    </div>
                </div>

                <div class="modal-card">
                    <h3 class="modal-card-title">🏁 Arrivée</h3>
                    <div class="form-group">
                        <label for="arrival">Adresse d'arrivée *</label>
                        <input type="text" id="arrival" name="arrival" value="<?= htmlspecialchars($trajet['arrivee']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="time_arrivee">Heure d'arrivée *</label>
                        <input type="time" id="time_arrivee" name="time_arrivee" value="<?= htmlspecialchars($trajet['heure_arrivee'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="places">Nombre de places disponibles *</label>
                        <input type="number" id="places" name="places" min="1" max="8" value="<?= htmlspecialchars($trajet['places_disponibles']) ?>">
                    </div>
                </div>

                <div class="modal-card">
                    <h3 class="modal-card-title">🚗 Informations pratiques</h3>
                    <div class="form-group">
                        <label for="vehicle-used">Véhicule utilisé *</label>
                        <select id="vehicle-used" name="vehicle_used">
                            <option value="">-- Sélectionnez un véhicule --</option>
                            <?php foreach ($vehicules as $vehicule): 
                                $vehicule_label = htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele'] . ' ' . $vehicule['couleur']); 
                                $selected = ($trajet['vehicule_id'] == $vehicule['vehicule_id']) ? 'selected' : '';
                            ?>
                                <option value="<?= $vehicule['vehicule_id'] ?>" <?= $selected ?>><?= $vehicule_label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="commentaire">Point de rendez-vous et précisions *</label>
                        <textarea id="commentaire" name="commentaire" rows="5"><?= htmlspecialchars($trajet['commentaire']) ?></textarea>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn-submit">Enregistrer les modifications</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
<?php if ($isOwner && !$isPast): ?>
const modal       = document.getElementById('modal-modifier');
const btnModifier = document.getElementById('btn-modifier');
const closeModal  = document.querySelector('.close-modal');

btnModifier.addEventListener('click', () => modal.style.display = 'flex');
closeModal.addEventListener('click',  () => modal.style.display = 'none');
window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

const addStopBtn      = document.getElementById('add-stop-btn');
const etapesContainer = document.getElementById('etapes-container');

addStopBtn.addEventListener('click', () => {
    const existingStops = etapesContainer.querySelectorAll('.stop-container');
    if (existingStops.length >= 5) { alert("Maximum 5 arrêts."); return; }
    const index = existingStops.length + 1;
    const div   = document.createElement('div');
    div.className = 'stop-container';
    div.style.display = 'flex';
    div.style.gap = '0.5rem';
    div.style.marginTop = '0.5rem';
    div.innerHTML = `
        <input type="text" name="step${index}" placeholder="Arrêt n°${index}" style="margin-bottom:0;flex:1;">
        <button type="button" class="remove-stop" style="background:#e74c3c;color:white;width:auto;padding:0.3rem 0.6rem;box-shadow:none;">✕</button>
    `;
    etapesContainer.appendChild(div);
    div.querySelector('.remove-stop').addEventListener('click', () => {
        div.remove();
        renumerotterArrets();
    });
});

function renumerotterArrets() {
    etapesContainer.querySelectorAll('.stop-container').forEach((c, i) => {
        const input = c.querySelector('input');
        if (input) { input.name = `step${i+1}`; input.placeholder = `Arrêt n°${i+1}`; }
    });
}

etapesContainer.querySelectorAll('.stop-container').forEach((c) => {
    if (!c.querySelector('.remove-stop')) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'remove-stop';
        btn.textContent = '✕';
        btn.style.cssText = 'background:#e74c3c;color:white;width:auto;padding:0.3rem 0.6rem;box-shadow:none;';
        c.style.display = 'flex';
        c.style.gap = '0.5rem';
        c.appendChild(btn);
        btn.addEventListener('click', () => { c.remove(); renumerotterArrets(); });
    }
});
<?php endif; ?>
</script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/toast.js"></script>
</body>
</html>