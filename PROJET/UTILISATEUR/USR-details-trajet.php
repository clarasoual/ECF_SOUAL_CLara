<?php
require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); 
require_once __DIR__ . '/../PHP/details_trajet.php';
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
                    <p class="driver-rating">⭐ 4.8 / 5 · Conducteur vérifié</p>
                    <p class="driver-car">
                        🚗 <?= htmlspecialchars($trajet['marque'] . ' ' . $trajet['modele']) ?> ·
                        <?= htmlspecialchars($trajet['couleur']) ?> ·
                        <?= htmlspecialchars($trajet['carburant']) ?>
                    </p>
                    <div class="driver-schedule">
                        <p>📅 Date : <?= htmlspecialchars($trajet['date_depart']) ?></p>
                        <p>🕒 Heure : <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                        <p>💺 Places disponibles : <?= htmlspecialchars($trajet['places_disponibles']) ?></p>
                    </div>
                    <div class="driver-reviews">
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
                    <p class="address">1 Rue Jean Jaurès, <?= htmlspecialchars($trajet['depart']) ?></p>
                </div>
                <span class="arrow">→</span>
                <div class="ville">
                    <a href="https://www.google.com/maps/search/<?= urlencode($trajet['arrivee']) ?>" target="_blank">
                        🗺️ <?= htmlspecialchars($trajet['arrivee']) ?>
                    </a>
                    <p class="address">12 Boulevard des Pyrénées, <?= htmlspecialchars($trajet['arrivee']) ?></p>
                </div>
            </div>

            <!-- PASSAGERS -->
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
        <?php if (!$isOwner): ?>
            <a href="#" class="cta-reserver">Réserver ce trajet</a>
        <?php else: ?>
            <a href="USR-modifier-trajet.php?id=<?= $trajet['id'] ?>" class="cta-modifier">Modifier</a>
            <a href="USR-supprimer-trajet.php?id=<?= $trajet['id'] ?>" class="cta-supprimer">Supprimer</a>
        <?php endif; ?>
    </div>

</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>

</body>
</html>