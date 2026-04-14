<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

// Récupérer les litiges (trajets_passagers avec statut 'litige')
$stmt = $bdd->prepare("
    SELECT 
        tp.id,
        t.id AS id_trajet,
        t.depart,
        t.arrivee,
        t.date_depart,
        t.heure_depart,
        u_passager.prenom AS prenom_passager,
        u_passager.nom AS nom_passager,
        u_passager.email AS email_passager,
        u_conducteur.prenom AS prenom_conducteur,
        u_conducteur.nom AS nom_conducteur,
        u_conducteur.email AS email_conducteur
    FROM trajets_passagers tp
    JOIN trajets t ON t.id = tp.id_trajet
    JOIN utilisateurs u_passager ON u_passager.id = tp.id_passager
    JOIN utilisateurs u_conducteur ON u_conducteur.id = t.id_conducteur
    WHERE tp.statut = 'litige'
    ORDER BY t.date_depart DESC
");
$stmt->execute();
$litiges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace employé - Litiges</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<main>
    <?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section>
        <h2>Covoiturages signalés</h2>

        <?php if (empty($litiges)): ?>
            <p>Aucun litige en cours. ✅</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>N° trajet</th>
                    <th>Trajet</th>
                    <th>Date</th>
                    <th>Passager</th>
                    <th>Mail passager</th>
                    <th>Conducteur</th>
                    <th>Mail conducteur</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($litiges as $l): ?>
                <tr>
                    <td><?= $l['id_trajet'] ?></td>
                    <td><?= htmlspecialchars($l['depart']) ?> → <?= htmlspecialchars($l['arrivee']) ?></td>
                    <td><?= date('d/m/Y', strtotime($l['date_depart'])) ?> à <?= htmlspecialchars($l['heure_depart']) ?></td>
                    <td><?= htmlspecialchars($l['prenom_passager'] . ' ' . $l['nom_passager']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($l['email_passager']) ?>"><?= htmlspecialchars($l['email_passager']) ?></a></td>
                    <td><?= htmlspecialchars($l['prenom_conducteur'] . ' ' . $l['nom_conducteur']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($l['email_conducteur']) ?>"><?= htmlspecialchars($l['email_conducteur']) ?></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</main>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>
</body>
</html>