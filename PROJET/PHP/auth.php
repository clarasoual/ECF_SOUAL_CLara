<?php
session_start();
include('../PHP/connexion.php');
include('../PHP/logs.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

$niveau  = $_GET['niveau']  ?? '';
$action  = $_GET['action']  ?? '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

$niveaux_autorises = ['INFO', 'WARNING', ''];
if (!in_array($niveau, $niveaux_autorises)) $niveau = '';

$logs       = getLogs($niveau, $action, $page, $perPage);
$total      = countLogs($niveau, $action);
$totalPages = ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journaux d'activité - Admin</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-logs.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<main>
    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

    <div class="logs-container">
        <h2>Journaux d'activité</h2>

        <form method="GET" class="logs-filters">
            <select name="niveau">
                <option value="">Tous les niveaux</option>
                <option value="INFO"    <?= $niveau === 'INFO'    ? 'selected' : '' ?>>INFO</option>
                <option value="WARNING" <?= $niveau === 'WARNING' ? 'selected' : '' ?>>WARNING</option>
            </select>
            <input type="text" name="action" value="<?= htmlspecialchars($action) ?>" placeholder="Filtrer par action...">
            <button type="submit" class="btn-filtrer">Filtrer</button>
            <a href="admin_logs.php" class="btn-reset">Réinitialiser</a>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Niveau</th>
                        <th>Action</th>
                        <th>Message</th>
                        <th>Utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="5" style="text-align:center; color:var(--gris-doux);">Aucun log trouvé.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="log-date"><?= htmlspecialchars($log['date'] ?? '—') ?></td>
                            <td>
                                <span class="badge-niveau badge-<?= strtolower($log['niveau'] ?? '') ?>">
                                    <?= htmlspecialchars($log['niveau'] ?? '—') ?>
                                </span>
                            </td>
                            <td class="log-action"><?= htmlspecialchars($log['action'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($log['message'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($log['user_id'] ?? '—') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&niveau=<?= urlencode($niveau) ?>&action=<?= urlencode($action) ?>">← Précédent</a>
            <?php endif; ?>
            <span>Page <?= $page ?> / <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&niveau=<?= urlencode($niveau) ?>&action=<?= urlencode($action) ?>">Suivant →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>