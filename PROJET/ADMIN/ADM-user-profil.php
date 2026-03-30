<?php
session_start();
include('../PHP/connexion.php');

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

// Récupérer l'ID passé dans l'URL
if (!isset($_GET['id'])) {
    header('Location: ADM-utilisateurs.php');
    exit();
}

$id = $_GET['id'];

// Récupérer l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas
if (!$user) {
    header('Location: ADM-utilisateurs.php');
    exit();
}

// ====== AVIS reçus ======
$stmt_avis = $bdd->prepare("
    SELECT a.note, a.commentaire, a.date_creation, 
           u.prenom, u.nom
    FROM avis a
    JOIN utilisateurs u ON u.id = a.id_auteur
    WHERE a.id_destinataire = ?
    ORDER BY a.date_creation DESC
");
$stmt_avis->execute([$id]);
$avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

// Note moyenne
$stmt_note = $bdd->prepare("SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis WHERE id_destinataire = ?");
$stmt_note->execute([$id]);
$note_info = $stmt_note->fetch(PDO::FETCH_ASSOC);

// ====== VÉHICULES ======
$stmt_vehicules = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = ?");
$stmt_vehicules->execute([$id]);
$vehicules = $stmt_vehicules->fetchAll(PDO::FETCH_ASSOC);

// ====== TRAJETS (conducteur) ======
$stmt_trajets = $bdd->prepare("
    SELECT t.*, COUNT(tp.id_passager) as nb_passagers
    FROM trajets t
    LEFT JOIN trajets_passagers tp ON tp.id_trajet = t.id
    WHERE t.id_conducteur = ?
    GROUP BY t.id
    ORDER BY t.date_depart DESC
");
$stmt_trajets->execute([$id]);
$trajets = $stmt_trajets->fetchAll(PDO::FETCH_ASSOC);

// ====== TRAJETS (passager) ======
$stmt_passager = $bdd->prepare("
    SELECT t.depart, t.arrivee, t.date_depart, tp.statut,
           u.prenom as prenom_conducteur, u.nom as nom_conducteur
    FROM trajets_passagers tp
    JOIN trajets t ON t.id = tp.id_trajet
    JOIN utilisateurs u ON u.id = t.id_conducteur
    WHERE tp.id_passager = ?
    ORDER BY t.date_depart DESC
");
$stmt_passager->execute([$id]);
$trajets_passager = $stmt_passager->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Profil de <?= htmlspecialchars($user['prenom']) ?></title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-profil.css">
</head>

<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<hr>

<main>

    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>
<div class="content-wrapper">
    <section class="driver-profile-section">

        <h2>Profil de <strong><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></strong></h2>

        <img src="../../IMAGES/<?= htmlspecialchars($user['photo']) ?>" class="profile-picture" alt="Photo de profil" width="150" height="150">

        <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>
        <p>Email : <?= htmlspecialchars($user['email']) ?></p>
        <p>Inscription : <?= date('d/m/Y', strtotime($user['date_inscription'])) ?></p>
        <p>Crédits : <?= $user['credits'] ?></p>

        <p>Note globale :
            <?= $note_info['total'] > 0 ? number_format($note_info['moyenne'], 1) . ' / 5' : 'Aucune note' ?>
        </p>
        <p>Nombre d'avis : <?= $note_info['total'] ?></p>

    </section>

    <hr>

    <!-- ===== EN SAVOIR PLUS ===== -->
    <section class="profile-driver-details">

        <h3 class="section-title">En savoir plus</h3>

        <h4 class="subsection-title">Biographie</h4>
        <p class="bio"><?= !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'Aucune biographie renseignée.' ?></p>

        <?php if (!empty($vehicules)): $v = $vehicules[0]; ?>

        <h4 class="subsection-title">Préférences</h4>
        <ul class="preferences-list">
            <li>Fumeur : <?= htmlspecialchars($v['fumeur']) ?></li>
            <li>Animaux acceptés : <?= htmlspecialchars($v['animaux_acceptes']) ?></li>
            <li>Musique : <?= htmlspecialchars($v['musique'] ?? 'Non renseigné') ?></li>
        </ul>

        <h4 class="subsection-title">Véhicule(s)</h4>
        <ul>
            <?php foreach ($vehicules as $v): ?>
                <li>
                    <?= htmlspecialchars($v['marque']) ?> <?= htmlspecialchars($v['modele']) ?> - <?= htmlspecialchars($v['couleur']) ?>
                    (<?= htmlspecialchars($v['carburant']) ?> — <?= $v['places'] ?> places)
                </li>
            <?php endforeach; ?>
        </ul>

        <?php else: ?>
            <p>Aucun véhicule enregistré.</p>
        <?php endif; ?>

    </section>

    <hr>

    <!-- ===== HISTORIQUE CONDUCTEUR ===== -->
    <?php if ($user['role'] === 'conducteur' || $user['role'] === 'passager-conducteur'): ?>
    <section class="trip-history">

        <h3 class="section-title">Historique de trajets (conducteur)</h3>

        <?php if (!empty($trajets)): ?>
            <?php foreach ($trajets as $t): ?>
            <div class="trip">
                <h4 class="trip-title">Trajet du <?= date('d/m/Y', strtotime($t['date_depart'])) ?></h4>
                <p class="trip-route"><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></p>
                <p>Statut : <?= $t['statut'] ?> — <?= $t['nb_passagers'] ?> passager(s)</p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun trajet en tant que conducteur.</p>
        <?php endif; ?>

    </section>
    <hr>
    <?php endif; ?>

    <!-- ===== HISTORIQUE PASSAGER ===== -->
    <?php if ($user['role'] === 'passager' || $user['role'] === 'passager-conducteur'): ?>
    <section class="trip-history">

        <h3 class="section-title">Historique de trajets (passager)</h3>

        <?php if (!empty($trajets_passager)): ?>
            <?php foreach ($trajets_passager as $t): ?>
            <div class="trip">
                <h4 class="trip-title">Trajet du <?= date('d/m/Y', strtotime($t['date_depart'])) ?></h4>
                <p class="trip-route"><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></p>
                <p>Conducteur : <?= htmlspecialchars($t['prenom_conducteur']) ?> <?= htmlspecialchars($t['nom_conducteur']) ?></p>
                <p>Statut : <?= $t['statut'] ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun trajet en tant que passager.</p>
        <?php endif; ?>

    </section>
    <hr>
    <?php endif; ?>

    <!-- ===== AVIS REÇUS ===== -->
    <section class="avis-section">

        <h3 class="section-title">Avis reçus</h3>

        <?php if (!empty($avis)): ?>
            <?php foreach ($avis as $a): ?>
            <div class="avis">
                <p><strong><?= htmlspecialchars($a['prenom']) ?> <?= htmlspecialchars($a['nom']) ?></strong>
                — <?= number_format($a['note'], 1) ?> / 5
                — <em><?= date('d/m/Y', strtotime($a['date_creation'])) ?></em></p>
                <p><?= htmlspecialchars($a['commentaire']) ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun avis reçu.</p>
        <?php endif; ?>

    </section>

    <hr>

    <!-- ===== ACTIONS ADMIN ===== -->
    <section class="admin-actions">
        <h3 class="section-title">Actions admin</h3>
        <a href="ADM-utilisateurs.php" class="btn-retour">← Retour à la liste</a>
    </section>
</div>
</main>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>

</body>
</html>