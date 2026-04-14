<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avis_id'], $_POST['action'])) {
    $avis_id = (int)$_POST['avis_id'];
    $action = $_POST['action'];

    if ($action === 'valider') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'valide' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'refuser') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'refuse' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'remettre_en_attente') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'en_attente' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'supprimer') {
        $stmt = $bdd->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->execute([$avis_id]);
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?onglet=" . ($_POST['onglet'] ?? 'en_attente') . "&toast=ok");
    exit;
}

$onglet = $_GET['onglet'] ?? 'en_attente';

// Récupérer les avis selon l'onglet
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

<div class="layout">
<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<hr>

<main>
<?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section class="reviews-moderation">
        <h2 id="title-reviews">Avis utilisateurs - Modération</h2>

        <!-- Menu déroulant -->
        <form method="GET" style="margin-bottom: 1rem;">
            <label for="onglet">Afficher :</label>
            <select name="onglet" id="onglet" onchange="this.form.submit()">
                <option value="en_attente" <?= $onglet === 'en_attente' ? 'selected' : '' ?>>⏳ En attente</option>
                <option value="valide" <?= $onglet === 'valide' ? 'selected' : '' ?>>✅ Validés</option>
                <option value="refuse" <?= $onglet === 'refuse' ? 'selected' : '' ?>>❌ Refusés</option>
            </select>
        </form>

        <?php if (empty($avis)): ?>
            <p>Aucun avis dans cette catégorie.</p>
        <?php else: ?>
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
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="avis_id" value="<?= $a['id'] ?>">
                            <input type="hidden" name="onglet" value="<?= $onglet ?>">

                            <?php if ($onglet === 'en_attente'): ?>
                                <button type="submit" name="action" value="valider" class="btn-valider">✅ Valider</button>
                                <button type="submit" name="action" value="refuser" class="btn-refuser">❌ Refuser</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="remettre_en_attente" class="btn-attente">🔄 Remettre en attente</button>
                                <button type="submit" name="action" value="supprimer" class="btn-supprimer" onclick="return confirm('Supprimer définitivement cet avis ?')">🗑️ Supprimer</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

    </section>
</main>
</div>

<?php if (isset($_GET['toast'])): ?>
    <div id="toast-success" style="position:fixed;bottom:20px;right:20px;background:#4BB543;color:white;padding:12px 20px;border-radius:8px;">
        ✅ Avis mis à jour !
    </div>
<?php endif; ?>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>
</body>
</html>