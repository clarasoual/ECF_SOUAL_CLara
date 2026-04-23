<?php
session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

$stmt = $bdd->prepare("SELECT prenom, email FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

require_once __DIR__ . '/../PHP/logs.php';

$filtreNiveau = isset($_GET['niveau']) && $_GET['niveau'] !== '' ? $_GET['niveau'] : null;
$filtreAction = isset($_GET['action']) && $_GET['action'] !== '' ? $_GET['action'] : null;

$parPage = 20;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $parPage;

$logs    = getLogs($filtreNiveau, $filtreAction, null, $parPage, $offset);
$total   = countLogs($filtreNiveau, $filtreAction);
$nbPages = (int)ceil($total / $parPage);

if (isset($_POST['clear_logs'])) {
    clearLogs();
    header('Location: ADM-logs.php?cleared=1');
    exit;
}

$allLogs   = getLogs(null, null, null, LOGS_MAX, 0);
$nbInfo    = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'INFO'));
$nbWarning = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'WARNING'));
$nbError   = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'ERROR'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs — EcoRide Admin</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-logs.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>
<main>
    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

    <div class="logs-wrapper">

        <div class="logs-header">
            <h2>📋 Logs</h2>
            <span class="badge-total"><?= $total ?> entrée<?= $total > 1 ? 's' : '' ?></span>
        </div>

        <?php if (isset($_GET['cleared'])): ?>
            <div class="alert">✅ Les logs ont été vidés.</div>
        <?php endif; ?>

        <!-- STATS -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-value"><?= count($allLogs) ?></div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-card" style="border-top-color:#60a5fa">
                <div class="stat-value" style="color:#60a5fa"><?= $nbInfo ?></div>
                <div class="stat-label">Info</div>
            </div>
            <div class="stat-card" style="border-top-color:var(--orange-doux)">
                <div class="stat-value" style="color:var(--orange-doux)"><?= $nbWarning ?></div>
                <div class="stat-label">Warning</div>
            </div>
            <div class="stat-card" style="border-top-color:#e74c3c">
                <div class="stat-value" style="color:#e74c3c"><?= $nbError ?></div>
                <div class="stat-label">Erreur</div>
            </div>
        </div>

        <!-- FILTRES -->
        <form method="GET" action="admin_logs.php">
            <div class="filters">
                <div class="filter-group">
                    <label>Niveau</label>
                    <select name="niveau">
                        <option value="">Tous</option>
                        <option value="INFO"    <?= $filtreNiveau === 'INFO'    ? 'selected' : '' ?>>Info</option>
                        <option value="WARNING" <?= $filtreNiveau === 'WARNING' ? 'selected' : '' ?>>Warning</option>
                        <option value="ERROR"   <?= $filtreNiveau === 'ERROR'   ? 'selected' : '' ?>>Erreur</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Action</label>
                    <input type="text" name="action" value="<?= htmlspecialchars($filtreAction ?? '') ?>" placeholder="ex: recherche_trajet">
                </div>
                <button type="submit" class="btn-filtrer">Filtrer</button>
                <a href="admin_logs.php" class="btn-reset">Réinitialiser</a>
            </div>
        </form>

        <!-- CONFIRMATION CLEAR -->
        <div class="confirm-clear" id="confirmClear">
            <p>⚠️ Vider tous les logs ? Cette action est irréversible.</p>
            <form method="POST">
                <button type="submit" name="clear_logs" class="btn-danger">Confirmer la suppression</button>
            </form>
            <button class="btn-reset" onclick="document.getElementById('confirmClear').classList.remove('visible')">Annuler</button>
        </div>

        <!-- TABLEAU -->
        <div class="table-wrap">
            <?php if (empty($logs)): ?>
                <div class="empty">Aucun log trouvé.</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Niveau</th>
                        <th>Action</th>
                        <th>Message</th>
                        <th>Utilisateur</th>
                        <th>IP</th>
                        <th>Page</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="td-date"><?= htmlspecialchars($log['date']) ?></td>
                        <td><span class="badge badge-<?= htmlspecialchars($log['niveau']) ?>"><?= htmlspecialchars($log['niveau']) ?></span></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td class="td-message"><?= htmlspecialchars($log['message']) ?></td>
                        <td><?= $log['utilisateur'] ? '#' . htmlspecialchars($log['utilisateur']) : '—' ?></td>
                        <td class="td-ip"><?= htmlspecialchars($log['ip']) ?></td>
                        <td class="td-page"><?= htmlspecialchars($log['page']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($nbPages > 1):
            $params = array_filter(['niveau' => $filtreNiveau, 'action' => $filtreAction]);
            $queryBase = $params ? '&' . http_build_query($params) : '';
        ?>
        <div class="pagination">
            <a href="?page=1<?= $queryBase ?>" class="<?= $page === 1 ? 'disabled' : '' ?>">«</a>
            <a href="?page=<?= max(1, $page - 1) ?><?= $queryBase ?>" class="<?= $page === 1 ? 'disabled' : '' ?>">‹</a>
            <?php for ($i = max(1, $page - 2); $i <= min($nbPages, $page + 2); $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?><?= $queryBase ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <a href="?page=<?= min($nbPages, $page + 1) ?><?= $queryBase ?>" class="<?= $page === $nbPages ? 'disabled' : '' ?>">›</a>
            <a href="?page=<?= $nbPages ?><?= $queryBase ?>" class="<?= $page === $nbPages ? 'disabled' : '' ?>">»</a>
        </div>
        <?php endif; ?>

        <!-- FOOTER -->
        <div class="logs-footer">
            <p>Page <?= $page ?> / <?= max(1, $nbPages) ?> — <?= $total ?> résultat<?= $total > 1 ? 's' : '' ?></p>
            <button class="btn-danger" onclick="document.getElementById('confirmClear').classList.add('visible')">
                🗑️ Vider les logs
            </button>
        </div>

    </div>
</main>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>