<?php
// ============================================================
// admin_logs.php — Page d'administration des logs
// ============================================================
// À protéger : vérifier que l'utilisateur est admin avant d'afficher
// Exemple : if (!estAdmin()) { header('Location: /login.php'); exit; }
// ============================================================

require_once __DIR__ . '/../PHP/logs.php';

// --- Paramètres de filtre depuis l'URL ---
$filtreNiveau = isset($_GET['niveau']) && $_GET['niveau'] !== '' ? $_GET['niveau'] : null;
$filtreAction = isset($_GET['action']) && $_GET['action'] !== '' ? $_GET['action'] : null;

// --- Pagination ---
$parPage = 20;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $parPage;

// --- Récupération des logs ---
$logs  = getLogs($filtreNiveau, $filtreAction, null, $parPage, $offset);
$total = countLogs($filtreNiveau, $filtreAction);
$nbPages = (int)ceil($total / $parPage);

// --- Action : vider les logs ---
if (isset($_POST['clear_logs'])) {
    clearLogs();
    header('Location: admin_logs.php?cleared=1');
    exit;
}

// --- Actions humaines pour l'affichage ---
$niveauLabels = [
    'INFO'    => ['label' => 'Info',    'color' => '#3b82f6'],
    'WARNING' => ['label' => 'Warning', 'color' => '#f59e0b'],
    'ERROR'   => ['label' => 'Erreur',  'color' => '#ef4444'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs — EcoRide Admin</title>
    <style>
        /* ---- Reset & base ---- */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #0f1117;
            --surface:    #1a1d27;
            --border:     #2a2d3a;
            --text:       #e2e8f0;
            --muted:      #64748b;
            --accent:     #6ee7b7;
            --info:       #3b82f6;
            --warning:    #f59e0b;
            --error:      #ef4444;
            --font:       'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 2rem;
        }

        /* ---- Header ---- */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .header h1 {
            font-size: 1.4rem;
            letter-spacing: 0.1em;
            color: var(--accent);
        }

        .header h1 span {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .badge-total {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        /* ---- Alertes ---- */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            background: rgba(110, 231, 183, 0.1);
            border: 1px solid var(--accent);
            color: var(--accent);
        }

        /* ---- Filtres ---- */
        .filters {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem 1.2rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .filter-group label {
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .filter-group select,
        .filter-group input {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.4rem 0.7rem;
            border-radius: 5px;
            font-family: var(--font);
            font-size: 0.85rem;
            outline: none;
            cursor: pointer;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: var(--accent);
        }

        .btn {
            padding: 0.45rem 1rem;
            border-radius: 5px;
            font-family: var(--font);
            font-size: 0.85rem;
            cursor: pointer;
            border: none;
            transition: opacity 0.15s;
        }

        .btn:hover { opacity: 0.85; }

        .btn-primary {
            background: var(--accent);
            color: #0f1117;
            font-weight: 700;
        }

        .btn-danger {
            background: transparent;
            color: var(--error);
            border: 1px solid var(--error);
        }

        /* ---- Stats rapides ---- */
        .stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1;
            min-width: 130px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.9rem 1.2rem;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ---- Table des logs ---- */
        .table-wrap {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        thead tr {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            color: var(--muted);
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.03); }

        td {
            padding: 0.65rem 1rem;
            vertical-align: top;
            color: var(--text);
        }

        .td-date { color: var(--muted); white-space: nowrap; font-size: 0.78rem; }
        .td-message { max-width: 350px; word-break: break-word; }
        .td-page { color: var(--muted); font-size: 0.75rem; word-break: break-all; }
        .td-ip { color: var(--muted); white-space: nowrap; }

        /* Badge niveau */
        .badge {
            display: inline-block;
            padding: 0.15rem 0.55rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .badge-INFO    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .badge-WARNING { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-ERROR   { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }

        /* ---- Vide ---- */
        .empty {
            text-align: center;
            padding: 3rem;
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* ---- Pagination ---- */
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 5px;
            font-size: 0.82rem;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--text);
            background: var(--surface);
            transition: border-color 0.15s;
        }

        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .current { border-color: var(--accent); color: var(--accent); font-weight: 700; }
        .pagination .disabled { opacity: 0.4; pointer-events: none; }

        /* ---- Footer ---- */
        .footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer p { font-size: 0.75rem; color: var(--muted); }

        .confirm-clear {
            display: none;
            background: rgba(239,68,68,0.1);
            border: 1px solid var(--error);
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }

        .confirm-clear.visible { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        .confirm-clear p { font-size: 0.85rem; color: var(--error); flex: 1; }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<div class="header">
    <h1>
        📋 EcoRide — Logs
        <span>/ admin</span>
    </h1>
    <span class="badge-total"><?= $total ?> entrée<?= $total > 1 ? 's' : '' ?> au total</span>
</div>

<?php if (isset($_GET['cleared'])): ?>
    <div class="alert">✅ Les logs ont été vidés.</div>
<?php endif; ?>

<!-- ===== STATS RAPIDES ===== -->
<?php
$allLogs = getLogs(null, null, null, LOGS_MAX, 0);
$nbInfo    = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'INFO'));
$nbWarning = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'WARNING'));
$nbError   = count(array_filter($allLogs, fn($l) => $l['niveau'] === 'ERROR'));
?>
<div class="stats">
    <div class="stat-card">
        <div class="stat-value" style="color:#e2e8f0"><?= count($allLogs) ?></div>
        <div class="stat-label">Total logs</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:#60a5fa"><?= $nbInfo ?></div>
        <div class="stat-label">Info</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:#fbbf24"><?= $nbWarning ?></div>
        <div class="stat-label">Warning</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color:#f87171"><?= $nbError ?></div>
        <div class="stat-label">Erreur</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
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

        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="admin_logs.php" class="btn" style="border:1px solid var(--border); color:var(--muted); text-decoration:none;">Réinitialiser</a>
    </div>
</form>

<!-- ===== CONFIRMATION CLEAR ===== -->
<div class="confirm-clear" id="confirmClear">
    <p>⚠️ Vider tous les logs ? Cette action est irréversible.</p>
    <form method="POST">
        <button type="submit" name="clear_logs" class="btn btn-danger">Confirmer la suppression</button>
    </form>
    <button class="btn" style="border:1px solid var(--border);color:var(--muted)" onclick="document.getElementById('confirmClear').classList.remove('visible')">Annuler</button>
</div>

<!-- ===== TABLE ===== -->
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
                <td><?= $log['utilisateur'] ? '#' . htmlspecialchars($log['utilisateur']) : '<span style="color:var(--muted)">—</span>' ?></td>
                <td class="td-ip"><?= htmlspecialchars($log['ip']) ?></td>
                <td class="td-page"><?= htmlspecialchars($log['page']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- ===== PAGINATION ===== -->
<?php if ($nbPages > 1): ?>
<div class="pagination">
    <?php
    // Construit les paramètres de l'URL sans la page
    $params = array_filter(['niveau' => $filtreNiveau, 'action' => $filtreAction]);
    $queryBase = $params ? '&' . http_build_query($params) : '';
    ?>

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

<!-- ===== FOOTER ===== -->
<div class="footer">
    <p>Page <?= $page ?> / <?= max(1, $nbPages) ?> — <?= $total ?> résultat<?= $total > 1 ? 's' : '' ?></p>
    <button class="btn btn-danger" onclick="document.getElementById('confirmClear').classList.add('visible')">
        🗑️ Vider les logs
    </button>
</div>

</body>
</html>