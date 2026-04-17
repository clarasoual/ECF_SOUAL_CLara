<?php

require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); 
require_once __DIR__ . '/../PHP/details_trajet.php';

error_reporting(0);
ini_set('display_errors', 0);

$success  = isset($_GET['success']) && $_GET['success'] == 1;
$isOwner  = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $trajet['id_conducteur'];

// Badge écologique — basé sur le carburant du véhicule
$eco = strtolower($trajet['carburant'] ?? '') === 'electrique';

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
}

$trajetDateTime = new DateTime($trajet['date_depart'] . ' ' . $trajet['heure_depart']);
$now            = new DateTime();
$isPast         = $trajetDateTime < $now || $trajet['statut'] === 'termine';
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
    <div id="toast-success" class="toast-success">
        ✅ Trajet modifié avec succès !
    </div>
<?php endif; ?>

<main>
    <a href="USR-recherche_trajet.php" class="back-arrow">← Retour</a>

    <h1 class="page-title">Détails du trajet</h1>

    <div class="cards-wrapper">

        <!-- Conducteur -->
        <section class="card driver-card">
            <div class="driver-main">
                <img src="../../IMAGES/profiles/<?= htmlspecialchars($trajet['photo_conducteur'] ?? 'default.jpg') ?>" 
                     alt="Photo du conducteur" class="driver-photo-lg">
                <div class="driver-infos">
                    <h2><?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?></h2>
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
                            <span class="eco-badge">🌿 Trajet écologique</span>
                        <?php endif; ?>
                    </p>
                    <div class="driver-schedule">
                        <p>📅 Date : <?= htmlspecialchars($trajet['date_depart']) ?></p>
                        <p>🕒 Départ : <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                        <?php if (!empty($trajet['heure_arrivee'])): ?>
                            <p>🏁 Arrivée : <?= htmlspecialchars($trajet['heure_arrivee']) ?></p>
                        <?php endif; ?>
                        <p>💺 Places disponibles : <?= htmlspecialchars($trajet['places_disponibles']) ?></p>
                    </div>
                    <div id="driver-reviews" class="driver-reviews">
                        <h4>Avis sur le conducteur</h4>
                        <?php if (empty($avis)): ?>
                            <p class="empty">Aucun avis pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($avis as $a): ?>
                                <div class="review">
                                    <p class="review-author">⭐ <?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?> – <?= $a['note'] ?>/5</p>
                                    <p class="review-text"><?= htmlspecialchars($a['commentaire']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Itinéraire -->
        <section class="card trajet-card">
            <h3>Itinéraire</h3>
            <div class="trajet-path">
                <div class="ville">
                    <a href="https://www.google.com/maps/search/<?= urlencode($trajet['depart']) ?>" target="_blank">
                        🗺️ <?= htmlspecialchars($trajet['depart']) ?>
                    </a>
                </div>
                <span class="arrow">→</span>
                <div class="ville">
                    <a href="https://www.google.com/maps/search/<?= urlencode($trajet['arrivee']) ?>" target="_blank">
                        🗺️ <?= htmlspecialchars($trajet['arrivee']) ?>
                    </a>
                </div>
            </div>

            <div class="passengers-section">
                <h4>Passagers</h4>
                <?php if (empty($passagers)): ?>
                    <p>Aucun passager inscrit pour le moment.</p>
                <?php else: ?>
                    <div class="passengers-list">
                        <?php foreach ($passagers as $p): ?>
                            <a href="USR-profil.php?id=<?= (int)$p['id'] ?>" class="passenger-card">
                                <img src="../../IMAGES/profiles/<?= htmlspecialchars($p['photo'] ?? 'default.jpg') ?>" alt="Photo de <?= htmlspecialchars($p['prenom']) ?>" class="passenger-photo">
                                <div class="passenger-info">
                                    <p class="passenger-name"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></p>
                                </div>
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
            <?php if (!empty($avis)): ?>
                <p><a href="#driver-reviews">Voir les avis</a></p>
            <?php endif; ?>

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
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Modifier votre trajet</h2>

        <form action="../PHP/modifier-trajet-traitement.php" method="POST">
            <input type="hidden" name="id_trajet" value="<?= $trajet['id'] ?>">

            <table class="trip-table">
                <tr>
                    <td class="trip-info">
                        <h3 class="trip-subtitle">D'où partons-nous ?</h3>

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
                        <button type="button" id="add-stop-btn">+ Ajouter un arrêt</button><br><br>

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
                            <label for="date">Date de départ *</label>
                            <input type="date" id="date" name="date" value="<?= htmlspecialchars($trajet['date_depart']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="time">Heure de départ *</label>
                            <input type="time" id="time" name="time" value="<?= htmlspecialchars($trajet['heure_depart']) ?>">
                        </div>
                    </td>

                    <td class="trip-info-destination">
                        <h3 class="trip-subtitle">Où allons-nous ?</h3>

                        <div class="form-group">
                            <label for="arrival">Adresse d'arrivée *</label>
                            <input type="text" id="arrival" name="arrival" value="<?= htmlspecialchars($trajet['arrivee']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="time_arrivee">Heure d'arrivée *</label>
                            <input type="time" id="time_arrivee" name="time_arrivee"
                                   value="<?= htmlspecialchars($trajet['heure_arrivee'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="places">Nombre de places disponibles *</label>
                            <input type="number" id="places" name="places" min="1" max="8" value="<?= htmlspecialchars($trajet['places_disponibles']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="commentaire">Autres précisions (optionnel)</label>
                            <textarea id="commentaire" name="commentaire" rows="4"><?= htmlspecialchars($trajet['commentaire']) ?></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
<?php if ($isOwner && !$isPast): ?>
const modal       = document.getElementById('modal-modifier');
const btnModifier = document.getElementById('btn-modifier');
const closeModal  = document.querySelector('.close-modal');

btnModifier.addEventListener('click', () => modal.style.display = 'block');
closeModal.addEventListener('click',  () => modal.style.display = 'none');
window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

const addStopBtn      = document.getElementById('add-stop-btn');
const etapesContainer = document.getElementById('etapes-container');
addStopBtn.addEventListener('click', () => {
    const index = etapesContainer.children.length + 1;
    const div   = document.createElement('div');
    div.className = 'stop-container';
    div.innerHTML = `<input type="text" name="step${index}" placeholder="Arrêt n°${index}">`;
    etapesContainer.appendChild(div);
});
<?php endif; ?>
</script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/toast.js"></script>
</body>
</html>