<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');

$id_utilisateur = $_SESSION['user_id'];

$stmt = $bdd->prepare("
    SELECT 
        a.note,
        a.commentaire,
        a.date_creation,
        t.depart,
        t.arrivee,
        u.prenom AS prenom_auteur,
        u.nom AS nom_auteur
    FROM avis a
    JOIN trajets t ON t.id = a.id_trajet
    JOIN utilisateurs u ON u.id = a.id_auteur
    WHERE a.id_destinataire = ?
    AND a.statut = 'valide'
    ORDER BY a.date_creation DESC
");
$stmt->execute([$id_utilisateur]);
$avis_recus = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("
    SELECT 
        a.note,
        a.commentaire,
        a.date_creation,
        a.statut,
        t.depart,
        t.arrivee,
        u.prenom AS prenom_destinataire,
        u.nom AS nom_destinataire
    FROM avis a
    JOIN trajets t ON t.id = a.id_trajet
    JOIN utilisateurs u ON u.id = a.id_destinataire
    WHERE a.id_auteur = ?
    ORDER BY a.date_creation DESC
");
$stmt->execute([$id_utilisateur]);
$avis_donnes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Avis</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-avis.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<!-- SELECT MOBILE -->
<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Mon compte —</option>
    <option value="../UTILISATEUR/USR-infos-perso.php">Informations personnelles</option>
    <option value="../UTILISATEUR/USR-mes-trajets.php">Mes trajets</option>
    <option value="../UTILISATEUR/USR-avis.php">Avis</option>
    <option value="../UTILISATEUR/USR-gestion-credits.php">Crédits</option>
    <option value="../UTILISATEUR/USR-infos-conducteur.php">Informations conducteur</option>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

    <section class="reviews-section">
        <h2>Mes avis</h2>

        <div id="reviews-received">
            <h3>Avis reçus</h3>
            <?php if (empty($avis_recus)): ?>
                <p>Aucun avis reçu pour le moment.</p>
            <?php else: ?>
                <?php foreach ($avis_recus as $a): ?>
                <div class="review">
                    <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($a['date_creation'])) ?></p>
                    <p><strong>Trajet :</strong> <?= htmlspecialchars($a['depart']) ?> → <?= htmlspecialchars($a['arrivee']) ?></p>
                    <p><strong>De :</strong> <?= htmlspecialchars($a['prenom_auteur'] . ' ' . $a['nom_auteur']) ?></p>
                    <p><strong>Note :</strong> <?= $a['note'] ?>/5</p>
                    <?php if ($a['commentaire']): ?>
                        <p><strong>Commentaire :</strong> <?= htmlspecialchars($a['commentaire']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="reviews-given">
            <h3>Avis donnés</h3>
            <?php if (empty($avis_donnes)): ?>
                <p>Vous n'avez pas encore donné d'avis.</p>
            <?php else: ?>
                <?php foreach ($avis_donnes as $a): ?>
                <div class="review">
                    <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($a['date_creation'])) ?></p>
                    <p><strong>Trajet :</strong> <?= htmlspecialchars($a['depart']) ?> → <?= htmlspecialchars($a['arrivee']) ?></p>
                    <p><strong>Pour :</strong> <?= htmlspecialchars($a['prenom_destinataire'] . ' ' . $a['nom_destinataire']) ?></p>
                    <p><strong>Note :</strong> <?= $a['note'] ?>/5</p>
                    <?php if ($a['commentaire']): ?>
                        <p><strong>Commentaire :</strong> <?= htmlspecialchars($a['commentaire']) ?></p>
                    <?php endif; ?>
                    <p><em>Statut : <?= $a['statut'] === 'valide' ? '✅ Publié' : ($a['statut'] === 'refuse' ? '❌ Refusé' : '⏳ En attente') ?></em></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </section>
</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/USR-avis.js"></script>
</body>
</html>
