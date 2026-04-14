<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: USR-index.php');
    exit;
}

// Récupérer le profil
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: USR-index.php');
    exit;
}

$isOwnProfile = ($_SESSION['user_id'] == $id);

// Note moyenne et nombre d'avis
$stmt = $bdd->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE id_destinataire = ? AND statut = 'valide'");
$stmt->execute([$id]);
$note_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Véhicules
$stmt = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Historique trajets terminés
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

// Trajets à venir
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

// Avis reçus validés
$stmt = $bdd->prepare("
    SELECT a.note, a.commentaire, a.date_creation, u.prenom, u.nom
    FROM avis a
    JOIN utilisateurs u ON u.id = a.id_auteur
    WHERE a.id_destinataire = ? AND a.statut = 'valide'
    ORDER BY a.date_creation DESC
");
$stmt->execute([$id]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<section class="driver-profile-section">
    <h2>Profil de <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong></h2>
    <img src="../../IMAGES/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>" class="profile-picture" alt="Photo de profil" width="150" height="150">
    <p>Rôle : <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
    <p>Note globale : <?= $note_info['total'] > 0 ? number_format($note_info['moyenne'], 1) . ' / 5 (' . $note_info['total'] . ' avis)' : 'Aucun avis' ?></p>
</section>

<hr>

<section class="profile-driver-details">
    <h3 class="section-title">En savoir plus</h3>

    <h4 class="subsection-title">Biographie</h4>
    <p class="bio"><?= !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'Aucune biographie renseignée.' ?></p>

    <?php if (!empty($vehicules)): $v = $vehicules[0]; ?>
    <h4 class="subsection-title">Préférences</h4>
    <ul class="preferences-list">
        <li>Fumeur : <?= htmlspecialchars($v['fumeur'] ?? 'Non renseigné') ?></li>
        <li>Animaux acceptés : <?= htmlspecialchars($v['animaux_acceptes'] ?? 'Non renseigné') ?></li>
        <li>Musique : <?= htmlspecialchars($v['musique'] ?? 'Non renseigné') ?></li>
    </ul>

    <h4 class="subsection-title">Véhicule(s)</h4>
    <ul>
        <?php foreach ($vehicules as $v): ?>
            <li><?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?> - <?= htmlspecialchars($v['couleur']) ?> (<?= htmlspecialchars($v['carburant']) ?>)</li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</section>

<hr>

<?php if (!empty($trajets_avenir)): ?>
<section class="trip-history">
    <h3 class="section-title">Prochains trajets</h3>
    <?php foreach ($trajets_avenir as $t): ?>
    <div class="trip">
        <h4 class="trip-title">Trajet du <?= date('d/m/Y', strtotime($t['date_depart'])) ?></h4>
        <p class="trip-route"><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></p>
        <a href="USR-details-trajet.php?id=<?= $t['id'] ?>">Voir le trajet</a>
    </div>
    <?php endforeach; ?>
</section>
<hr>
<?php endif; ?>

<?php if (!empty($trajets)): ?>
<section class="trip-history">
    <h3 class="section-title">Historique de trajets</h3>
    <?php foreach ($trajets as $t): ?>
    <div class="trip">
        <h4 class="trip-title">Trajet du <?= date('d/m/Y', strtotime($t['date_depart'])) ?></h4>
        <p class="trip-route"><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></p>
        <p><?= $t['nb_passagers'] ?> passager(s)</p>
    </div>
    <?php endforeach; ?>
</section>
<hr>
<?php endif; ?>

<?php if (!empty($avis)): ?>
<section class="avis-section">
    <h3 class="section-title">Avis reçus</h3>
    <?php foreach ($avis as $a): ?>
    <div class="review">
        <p><strong><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></strong> — <?= $a['note'] ?>/5 — <em><?= date('d/m/Y', strtotime($a['date_creation'])) ?></em></p>
        <?php if ($a['commentaire']): ?>
            <p><?= htmlspecialchars($a['commentaire']) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</section>
<hr>
<?php endif; ?>

<?php if (!$isOwnProfile): ?>
<section class="contact-driver">
    <h3 class="section-title">Contacter <?= htmlspecialchars($user['prenom']) ?></h3>
    <a href="USR-messagerie.php?contact=<?= $id ?>" class="btn-message">Envoyer un message</a>
</section>
<?php endif; ?>

<script src="../JS/USR-profil.js"></script>
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>