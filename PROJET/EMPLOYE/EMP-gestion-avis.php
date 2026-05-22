<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

$onglet = $_GET['onglet'] ?? 'en_attente';

$stmt = $bdd->prepare("
    SELECT 
        a.id,
        a.note,
        a.commentaire,
        a.date_creation,
        a.statut,
        u_auteur.prenom AS prenom_auteur,
        u_auteur.nom AS nom_auteur,
        u_conducteur.prenom AS prenom_conducteur,
        u_conducteur.nom AS nom_conducteur,
        t.depart,
        t.arrivee,
        t.id AS id_trajet
    FROM avis a
    JOIN utilisateurs u_auteur ON u_auteur.id = a.id_auteur
    JOIN utilisateurs u_conducteur ON u_conducteur.id = a.id_destinataire
    JOIN trajets t ON t.id = a.id_trajet
    WHERE a.statut = ?
    ORDER BY a.date_creation DESC
");
$stmt->execute([$onglet]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace employé - Gestion des avis</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-gestion-avis.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<!-- SELECT MOBILE -->
<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Navigation —</option>
    <option value="EMP-gestion-avis.php">Avis à valider</option>
    <option value="EMP-litiges.php">Covoiturages signalés</option>
    <option value="EMP-demandes-credits.php">Demandes de crédits</option>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section class="reviews-moderation">
        <h2 id="title-reviews">Avis utilisateurs - Modération</h2>

        <form method="GET" style="margin-bottom: 1rem;">
            <label for="onglet">Afficher :</label>
            <select name="onglet" id="onglet" onchange="this.form.submit()">
                <option value="en_attente" <?= $onglet === 'en_attente' ? 'selected' : '' ?>>⏳ En attente</option>
                <option value="valide"     <?= $onglet === 'valide'     ? 'selected' : '' ?>>✅ Validés</option>
                <option value="refuse"     <?= $onglet === 'refuse'     ? 'selected' : '' ?>>❌ Refusés</option>
            </select>
        </form>

        <?php if (empty($avis)): ?>
            <p>Aucun avis dans cette catégorie.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Auteur</th>
                        <th>Conducteur</th>
                        <th>Trajet</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($avis as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($a['date_creation'])) ?></td>
                        <td><?= htmlspecialchars($a['prenom_auteur'] . ' ' . $a['nom_auteur']) ?></td>
                        <td><?= htmlspecialchars($a['prenom_conducteur'] . ' ' . $a['nom_conducteur']) ?></td>
                        <td><?= htmlspecialchars($a['depart']) ?> → <?= htmlspecialchars($a['arrivee']) ?></td>
                        <td><?= $a['note'] ?>/5</td>
                        <td><?= htmlspecialchars($a['commentaire'] ?? '—') ?></td>
                        <td>
                            <div class="actions-cell">
                            <?php if ($onglet === 'en_attente'): ?>
                                <button type="button" class="btn-valider"
                                        data-action="valider"
                                        data-avis-id="<?= $a['id'] ?>">
                                    ✅ Valider
                                </button>
                                <button type="button" class="btn-refuser"
                                        data-action="refuser"
                                        data-avis-id="<?= $a['id'] ?>">
                                    ❌ Refuser
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-attente"
                                        data-action="remettre_en_attente"
                                        data-avis-id="<?= $a['id'] ?>">
                                    🔄 Remettre en attente
                                </button>
                                <button type="button" class="btn-supprimer"
                                        data-action="supprimer"
                                        data-avis-id="<?= $a['id'] ?>">
                                    🗑️ Supprimer
                                </button>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </section>
</main>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>

<script src="../JS/EMP-gestion-avis.js"></script>
</body>
</html>