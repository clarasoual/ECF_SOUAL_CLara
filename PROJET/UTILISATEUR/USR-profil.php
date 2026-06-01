<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: USR-index.php');
    exit;
}

$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: USR-index.php');
    exit;
}

$isOwnProfile = ($_SESSION['user_id'] == $id);

$stmt = $bdd->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE id_destinataire = ? AND statut = 'valide'");
$stmt->execute([$id]);
$note_info = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("
    SELECT t.*, COUNT(tp.id_passager) as nb_passagers
    FROM trajets t
    LEFT JOIN trajets_passagers tp ON tp.id_trajet = t.id
    WHERE t.id_conducteur = ? AND t.statut = 'termine'
    GROUP BY t.id
    ORDER BY t.date_depart DESC
    LIMIT 5
");
$stmt->execute([$id]);
$trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("
    SELECT t.*
    FROM trajets t
    WHERE t.id_conducteur = ? AND t.statut IN ('publie', 'complet')
    AND t.date_depart >= CURDATE()
    ORDER BY t.date_depart ASC
    LIMIT 5
");
$stmt->execute([$id]);
$trajets_avenir = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("
    SELECT a.note, a.commentaire, a.date_creation, u.prenom, u.nom
    FROM avis a
    JOIN utilisateurs u ON u.id = a.id_auteur
    WHERE a.id_destinataire = ? AND a.statut = 'valide'
    ORDER BY a.date_creation DESC
");
$stmt->execute([$id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

$roles_labels = [
    'passager' => '🧳 Passager',
    'conducteur' => '🚗 Conducteur',
    'passager-conducteur' => '🧳🚗 Passager & Conducteur',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($user['prenom']) ?></title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-profil.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main class="profil-main">

    <!-- COLONNE GAUCHE -->
    <aside class="profil-aside">

        <div class="profil-card">
            <img src="../../IMAGES/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>"
                 class="profil-avatar" alt="Photo de profil">
            <h2 class="profil-nom"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
            <span class="profil-role-badge"><?= $roles_labels[$user['role']] ?? ucfirst($user['role']) ?></span>

            <?php if ($note_info['total'] > 0): ?>
            <div class="profil-note">
                <span class="profil-note-value">⭐ <?= number_format($note_info['moyenne'], 1) ?></span>
                <span class="profil-note-label">/ 5 · <?= $note_info['total'] ?> avis</span>
            </div>
            <?php else: ?>
            <p class="profil-note-empty">Aucun avis pour le moment</p>
            <?php endif; ?>

            <?php if (!empty($user['bio'])): ?>
            <p class="profil-bio"><?= htmlspecialchars($user['bio']) ?></p>
            <?php endif; ?>

            <?php if ($isOwnProfile): ?>
            <a href="USR-infos-perso.php" class="profil-btn-edit">✏️ Modifier mon profil</a>
            <?php endif; ?>
        </div>

        <!-- PRÉFÉRENCES & VÉHICULES -->
        <?php if (!empty($vehicules)): $v = $vehicules[0]; ?>
        <div class="profil-card">
            <h3 class="profil-card-title">Préférences à bord</h3>
            <div class="profil-prefs">
                <span class="profil-pref-item"><?= $v['fumeur'] === 'oui' ? '🚬 Fumeur' : '🚭 Non-fumeur' ?></span>
                <span class="profil-pref-item"><?= $v['animaux_acceptes'] === 'oui' ? '🐾 Animaux OK' : '🚫 Sans animaux' ?></span>
                <?php if (!empty($v['musique']) && $v['musique'] !== 'none'): ?>
                <span class="profil-pref-item">🎵 <?= htmlspecialchars(ucfirst($v['musique'])) ?></span>
                <?php else: ?>
                <span class="profil-pref-item">🔇 Pas de musique</span>
                <?php endif; ?>
            </div>

            <h3 class="profil-card-title" style="margin-top:1.25rem;">Véhicule(s)</h3>
            <?php foreach ($vehicules as $v): ?>
            <div class="profil-vehicule">
                <span class="profil-vehicule-icon">🚗</span>
                <div>
                    <p class="profil-vehicule-nom"><?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?> — <?= htmlspecialchars($v['couleur']) ?></p>
                    <p class="profil-vehicule-detail"><?= htmlspecialchars($v['carburant']) ?> · <?= $v['places'] ?> places</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </aside>

    <!-- COLONNE DROITE -->
    <div class="profil-content">

        <!-- Trajets à venir -->
        <?php if (!empty($trajets_avenir)): ?>
        <div class="profil-bloc">
            <h3 class="profil-bloc-title">🗓️ Prochains trajets</h3>
            <?php foreach ($trajets_avenir as $t): ?>
            <div class="profil-trajet-item">
                <div class="profil-trajet-route">
                    <span><?= htmlspecialchars($t['depart']) ?></span>
                    <span class="profil-trajet-arrow">→</span>
                    <span><?= htmlspecialchars($t['arrivee']) ?></span>
                </div>
                <div class="profil-trajet-meta">
                    📅 <?= date('d/m/Y', strtotime($t['date_depart'])) ?>
                    · 💺 <?= htmlspecialchars($t['places_disponibles']) ?> place(s)
                    · 💳 <?= (int)$t['prix'] ?> crédit(s)
                </div>
                <a href="USR-details-trajet.php?id=<?= $t['id'] ?>" class="profil-trajet-link">Voir le trajet →</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Historique -->
        <?php if (!empty($trajets)): ?>
        <div class="profil-bloc">
            <h3 class="profil-bloc-title">🕓 Trajets effectués</h3>
            <?php foreach ($trajets as $t): ?>
            <div class="profil-trajet-item profil-trajet-item--past">
                <div class="profil-trajet-route">
                    <span><?= htmlspecialchars($t['depart']) ?></span>
                    <span class="profil-trajet-arrow">→</span>
                    <span><?= htmlspecialchars($t['arrivee']) ?></span>
                </div>
                <div class="profil-trajet-meta">
                    📅 <?= date('d/m/Y', strtotime($t['date_depart'])) ?>
                    · 👥 <?= $t['nb_passagers'] ?> passager(s)
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Avis -->
        <?php if (!empty($avis)): ?>
        <div class="profil-bloc">
            <h3 class="profil-bloc-title">⭐ Avis reçus</h3>
            <?php foreach ($avis as $a): ?>
            <div class="profil-avis-item">
                <div class="profil-avis-header">
                    <span class="profil-avis-auteur"><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></span>
                    <span class="profil-avis-note">⭐ <?= $a['note'] ?>/5</span>
                    <span class="profil-avis-date"><?= date('d/m/Y', strtotime($a['date_creation'])) ?></span>
                </div>
                <?php if ($a['commentaire']): ?>
                <p class="profil-avis-texte"><?= htmlspecialchars($a['commentaire']) ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($trajets_avenir) && empty($trajets) && empty($avis)): ?>
        <div class="profil-bloc profil-bloc--empty">
            <p>Aucune activité pour le moment.</p>
        </div>
        <?php endif; ?>

    </div>

</main>

<script src="../JS/USR-profil.js"></script>
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>