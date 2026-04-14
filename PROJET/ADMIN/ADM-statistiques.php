<?php
session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

$stmt_admin = $bdd->prepare("SELECT * FROM admins WHERE id = ?");
$stmt_admin->execute([$_SESSION['admin_id']]);
$admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

$date_fin   = isset($_GET['date_fin'])   ? $_GET['date_fin']   : date('Y-m-d');
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : date('Y-m-d', strtotime('-30 days'));

// ====== GRAPHIQUE 1 : covoiturages par jour ======
$stmt_covoit = $bdd->prepare("
    SELECT t.date_depart, COUNT(DISTINCT t.id) as nb_covoiturages
    FROM trajets t
    JOIN trajets_passagers tp ON tp.id_trajet = t.id
    WHERE t.statut = 'termine'
      AND tp.statut = 'termine'
      AND t.date_depart BETWEEN ? AND ?
    GROUP BY t.date_depart
    ORDER BY t.date_depart ASC
");
$stmt_covoit->execute([$date_debut, $date_fin]);
$covoit_data = $stmt_covoit->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$values = [];
$covoit_map = [];
foreach ($covoit_data as $row) {
    $covoit_map[$row['date_depart']] = $row['nb_covoiturages'];
}
$current = strtotime($date_debut);
$end     = strtotime($date_fin);
while ($current <= $end) {
    $d = date('Y-m-d', $current);
    $labels[] = date('d/m', $current);
    $values[] = isset($covoit_map[$d]) ? (int)$covoit_map[$d] : 0;
    $current  = strtotime('+1 day', $current);
}
$total_covoit = array_sum($values);
$labels_json = json_encode($labels);
$values_json = json_encode($values);

// ====== GRAPHIQUE 2 : crédits gagnés par la plateforme ======
$transactions_raw = file_get_contents(__DIR__ . '/../../transactions.json');
$transactions = json_decode($transactions_raw, true) ?? [];

$credits_map = [];
foreach ($transactions as $t) {
    $date_t = substr($t['date'], 0, 10);
    if ($date_t >= $date_debut && $date_t <= $date_fin) {
        if (!isset($credits_map[$date_t])) $credits_map[$date_t] = 0;
        if ($t['type'] === 'sortie' && isset($t['id_trajet'])) {
            $credits_map[$date_t] += 2;
        }
    }
}

$credits_labels = [];
$credits_values = [];
$current = strtotime($date_debut);
while ($current <= strtotime($date_fin)) {
    $d = date('Y-m-d', $current);
    $credits_labels[] = date('d/m', $current);
    $credits_values[] = isset($credits_map[$d]) ? (int)$credits_map[$d] : 0;
    $current = strtotime('+1 day', $current);
}
$total_credits = array_sum($credits_values);
$credits_labels_json = json_encode($credits_labels);
$credits_values_json = json_encode($credits_values);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Statistiques</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        main {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            padding: 2rem 1rem;
            gap: 1.5rem;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            min-width: 0;
            padding: 0.5rem 0;
        }
        .stats-filter {
            background-color: var(--beige-fonce);
            padding: 1.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 12px var(--shadow);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .stats-filter label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--vert-doux);
        }
        .stats-filter input[type="date"] {
            padding: 0.45rem 0.8rem;
            border: 1px solid var(--vert-doux);
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
            color: #333;
            cursor: pointer;
        }
        .btn-filtrer {
            padding: 0.45rem 1.2rem;
            background-color: var(--vert-doux);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-filtrer:hover { opacity: 0.85; }
        .stats-cards {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .stat-card {
            background-color: var(--beige-fonce);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 12px var(--shadow);
            flex: 1;
            min-width: 180px;
            border-top: 4px solid var(--vert-doux);
        }
        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #888;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--vert-doux);
            line-height: 1;
        }
        .stat-card .stat-sub {
            font-size: 0.8rem;
            color: #aaa;
            margin-top: 0.3rem;
        }
        .charts-wrapper {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .charts-wrapper .chart-section {
            flex: 1;
            min-width: 300px;
        }
        .chart-section {
            background-color: var(--beige-fonce);
            padding: 2rem 2.5rem;
            border-radius: 16px;
            box-shadow: 0 2px 12px var(--shadow);
        }
        .chart-section h2 {
            color: var(--vert-doux);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .chart-wrapper {
            position: relative;
            height: 300px;
        }
        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--vert-doux);
            margin: 0 0 0.2rem 0;
        }
        .page-subtitle {
            font-size: 0.9rem;
            color: #999;
            margin: 0;
        }
    </style>
</head>
<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<main>

    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

    <div class="content-wrapper">

        <div>
            <h1 class="page-title">Statistiques</h1>
            <p class="page-subtitle">Vue d'ensemble de l'activité de la plateforme</p>
        </div>

        <form class="stats-filter" method="GET">
            <label for="date_debut">Du</label>
            <input type="date" id="date_debut" name="date_debut"
                   value="<?= htmlspecialchars($date_debut) ?>">
            <label for="date_fin">au</label>
            <input type="date" id="date_fin" name="date_fin"
                   value="<?= htmlspecialchars($date_fin) ?>">
            <button type="submit" class="btn-filtrer">Appliquer</button>
        </form>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-label">Covoiturages terminés</div>
                <div class="stat-value"><?= $total_covoit ?></div>
                <div class="stat-sub">sur la période sélectionnée</div>
            </div>
            <div class="stat-card" style="border-top-color: var(--orange-doux);">
                <div class="stat-label">Crédits gagnés par la plateforme</div>
                <div class="stat-value" style="color: var(--orange-doux);"><?= $total_credits ?></div>
                <div class="stat-sub">sur la période sélectionnée</div>
            </div>
        </div>

        <div class="charts-wrapper">
            <section class="chart-section">
                <h2>Covoiturages par jour</h2>
                <div class="chart-wrapper">
                    <canvas id="chartCovoit"></canvas>
                </div>
            </section>

            <section class="chart-section">
                <h2>Crédits gagnés par la plateforme</h2>
                <div class="chart-wrapper">
                    <canvas id="chartCredits"></canvas>
                </div>
            </section>
        </div>

    </div>
</main>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>

<script>
const ctx1 = document.getElementById('chartCovoit').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= $labels_json ?>,
        datasets: [{
            label: 'Covoiturages',
            data: <?= $values_json ?>,
            backgroundColor: 'rgba(90, 150, 90, 0.15)',
            borderColor: 'rgba(90, 150, 90, 0.85)',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#999', maxTicksLimit: 15 } },
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 }, color: '#999' }, grid: { color: 'rgba(0,0,0,0.05)' } }
        }
    }
});

const ctx2 = document.getElementById('chartCredits').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?= $credits_labels_json ?>,
        datasets: [{
            label: 'Crédits gagnés',
            data: <?= $credits_values_json ?>,
            backgroundColor: 'rgba(210, 130, 60, 0.15)',
            borderColor: 'rgba(210, 130, 60, 0.85)',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#999', maxTicksLimit: 15 } },
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 }, color: '#999' }, grid: { color: 'rgba(0,0,0,0.05)' } }
        }
    }
});
</script>

</body>
</html>