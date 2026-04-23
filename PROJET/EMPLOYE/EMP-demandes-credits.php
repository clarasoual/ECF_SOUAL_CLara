<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');
require_once('../PHP/transactions.php');

define('CREDITS_ACCORDES', 20);
define('FICHIER_DEMANDES', __DIR__ . '/../../demandes_credits.json');

// ─────────────────────────────────────────────
// TRAITEMENT ACTION (accepter / refuser)
// ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demande_id'], $_POST['action'])) {
    $demande_id = $_POST['demande_id'];
    $action     = $_POST['action'];

    // Lire le fichier JSON
    $fp = fopen(FICHIER_DEMANDES, 'c+');
    if (flock($fp, LOCK_EX)) {
        $contenu  = stream_get_contents($fp);
        $demandes = json_decode($contenu, true) ?? [];

        foreach ($demandes as &$d) {
            if ($d['id'] === $demande_id && $d['statut'] === 'en_attente') {
                if ($action === 'accepter') {
                    $d['statut'] = 'accepte';

                    // Créditer l'utilisateur en BDD
                    $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
                    $stmt->execute([$d['id_utilisateur']]);
                    $credit        = $stmt->fetch(PDO::FETCH_ASSOC);
                    $solde_actuel  = $credit ? $credit['solde'] : 0;
                    $nouveau_solde = $solde_actuel + CREDITS_ACCORDES;

                    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
                    $stmt->execute([$nouveau_solde, $d['id_utilisateur']]);

                    // Enregistrer la transaction JSON
                    ajouterTransaction(
                        $d['id_utilisateur'],
                        'entree',
                        'Crédits accordés par EcoRide suite à votre demande',
                        CREDITS_ACCORDES,
                        $nouveau_solde,
                        null
                    );

                } elseif ($action === 'refuser') {
                    $d['statut'] = 'refuse';
                }
                break;
            }
        }
        unset($d);

        // Réécrire le fichier
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($demandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
    }
    fclose($fp);

    header('Location: EMP-demandes-credits.php?toast=' . $action);
    exit;
}

// ─────────────────────────────────────────────
// LECTURE DES DEMANDES
// ─────────────────────────────────────────────
$demandes    = [];
$onglet      = $_GET['onglet'] ?? 'en_attente';

if (file_exists(FICHIER_DEMANDES)) {
    $contenu  = file_get_contents(FICHIER_DEMANDES);
    $toutes   = json_decode($contenu, true) ?? [];
    $demandes = array_filter($toutes, fn($d) => $d['statut'] === $onglet);
    $demandes = array_values($demandes);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace employé - Demandes de crédits</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-gestion-avis.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-employe.php'); ?>
<main>
    <?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section class="reviews-moderation">
        <h2>Demandes de crédits</h2>

        <form method="GET" style="margin-bottom: 1rem;">
            <label for="onglet">Afficher :</label>
            <select name="onglet" id="onglet" onchange="this.form.submit()">
                <option value="en_attente" <?= $onglet === 'en_attente' ? 'selected' : '' ?>>⏳ En attente</option>
                <option value="accepte"    <?= $onglet === 'accepte'    ? 'selected' : '' ?>>✅ Acceptées</option>
                <option value="refuse"     <?= $onglet === 'refuse'     ? 'selected' : '' ?>>❌ Refusées</option>
            </select>
        </form>

        <?php if (empty($demandes)): ?>
            <p>Aucune demande dans cette catégorie.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Crédits accordés</th>
                    <th>Statut</th>
                    <?php if ($onglet === 'en_attente'): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $d): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($d['date'])) ?></td>
                    <td><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></td>
                    <td><?= htmlspecialchars($d['email']) ?></td>
                    <td><?= CREDITS_ACCORDES ?> crédits</td>
                    <td>
                        <?php if ($d['statut'] === 'en_attente'): ?>⏳ En attente
                        <?php elseif ($d['statut'] === 'accepte'): ?>✅ Acceptée
                        <?php else: ?>❌ Refusée
                        <?php endif; ?>
                    </td>
                    <?php if ($onglet === 'en_attente'): ?>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="demande_id" value="<?= htmlspecialchars($d['id']) ?>">
                            <button type="submit" name="action" value="accepter" class="btn-valider">✅ Accepter</button>
                            <button type="submit" name="action" value="refuser"  class="btn-refuser">❌ Refuser</button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</main>
</div>

<?php if (isset($_GET['toast'])): ?>
    <div id="toast-success" style="position:fixed;bottom:20px;right:20px;background:<?= $_GET['toast'] === 'accepter' ? '#4BB543' : '#e74c3c' ?>;color:white;padding:12px 20px;border-radius:8px;font-family:'Quicksand',sans-serif;z-index:9999;">
        <?= $_GET['toast'] === 'accepter' ? '✅ Demande acceptée — 20 crédits accordés !' : '❌ Demande refusée.' ?>
    </div>
    <script>setTimeout(() => document.getElementById('toast-success').remove(), 3000);</script>
<?php endif; ?>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>
</body>
</html>