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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-statistiques.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<!-- SELECT MOBILE -->
<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Navigation —</option>
    <option value="ADM-employes.php">Employés</option>
    <option value="ADM-utilisateurs.php">Utilisateurs</option>
    <option value="ADM-statistiques.php">Statistiques</option>
    <option value="admin_logs.php">Logs</option>
</select>

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
