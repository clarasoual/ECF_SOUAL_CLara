<?php
session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

// Période par défaut : 30 derniers jours
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

// Remplir les jours sans données avec 0
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

// Total
$total_covoit = array_sum($values);

$labels_json = json_encode($labels);
$values_json = json_encode($values);
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
        /* ====== LAYOUT ====== */
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

        /* ====== FILTRE PÉRIODE ====== */
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

        .stats-filter input[type="date"]:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(var(--vert-doux-rgb, 80,130,80), 0.2);
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

        .btn-filtrer:hover {
            opacity: 0.85;
        }

        /* ====== CARTES STAT ====== */
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

        /* ====== GRAPHIQUES ====== */
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

        /* ====== PAGE TITLE ====== */
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

        <!-- TITRE -->
        <div>
            <h1 class="page-title">Statistiques</h1>
            <p class="page-subtitle">Vue d'ensemble de l'activité de la plateforme</p>
        </div>

        <!-- FILTRE PÉRIODE -->
        <form class="stats-filter" method="GET">
            <label for="date_debut">Du</label>
            <input type="date" id="date_debut" name="date_debut"
                   value="<?= htmlspecialchars($date_debut) ?>">

            <label for="date_fin">au</label>
            <input type="date" id="date_fin" name="date_fin"
                   value="<?= htmlspecialchars($date_fin) ?>">

            <button type="submit" class="btn-filtrer">Appliquer</button>
        </form>

        <!-- CARTES RÉSUMÉ -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-label">Covoiturages terminés</div>
                <div class="stat-value"><?= $total_covoit ?></div>
                <div class="stat-sub">sur la période sélectionnée</div>
            </div>
            <!-- Carte crédits : à compléter quand la logique crédits sera prête -->
            <div class="stat-card" style="border-top-color: var(--orange-doux); opacity: 0.5;">
                <div class="stat-label">Crédits gagnés</div>
                <div class="stat-value" style="color: var(--orange-doux);">—</div>
                <div class="stat-sub">fonctionnalité à venir</div>
            </div>
        </div>

        <!-- GRAPHIQUE 1 : covoiturages par jour -->
        <section class="chart-section">
            <h2>Covoiturages par jour</h2>
            <div class="chart-wrapper">
                <canvas id="chartCovoit"></canvas>
            </div>
        </section>

        <!-- GRAPHIQUE 2 : crédits (placeholder) -->
        <section class="chart-section" style="opacity: 0.5;">
            <h2>Crédits gagnés par jour <span style="font-size:0.8rem; color:#aaa; font-weight:400;">(à venir)</span></h2>
            <div class="chart-wrapper" style="display:flex; align-items:center; justify-content:center;">
                <p style="color:#bbb; font-size:0.95rem;">Ce graphique sera disponible une fois la gestion des crédits mise en place.</p>
            </div>
        </section>

    </div>
</main>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>

<script>
const labels = <?= $labels_json ?>;
const values = <?= $values_json ?>;

// Couleur principale récupérée depuis les variables CSS
const rootStyle = getComputedStyle(document.documentElement);

const ctx = document.getElementById('chartCovoit').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Covoiturages',
            data: values,
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
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.parsed.y} covoiturage${ctx.parsed.y > 1 ? 's' : ''}`
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    font: { size: 11 },
                    color: '#999',
                    maxTicksLimit: 15,
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: { size: 11 },
                    color: '#999',
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)',
                }
            }
        }
    }
});
</script>

</body>
</html>