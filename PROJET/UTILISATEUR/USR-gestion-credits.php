<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');
include('../PHP/transactions.php');

$id_utilisateur = $_SESSION['user_id'];

// Récupère le solde depuis MySQL
$stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$credit = $stmt->fetch(PDO::FETCH_ASSOC);
$solde = $credit ? $credit['solde'] : 0;

// Récupère l'historique depuis le JSON
$transactions = getTransactions($id_utilisateur);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Crédits</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-gestion-credits.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>

<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<section class="credits-section">

    <h2>Mes crédits</h2>

    <?php if (isset($_GET['demande']) && $_GET['demande'] === 'ok'): ?>
        <p class="message-succes">✅ Votre demande a bien été envoyée. Notre équipe reviendra vers vous rapidement.</p>
    <?php endif; ?>

    <p class="solde-actuel"><strong>Solde actuel :</strong> <?= $solde ?> crédits</p>
    <p>Vous gagnez des crédits en proposant des trajets à d'autres utilisateurs, lorsque vous êtes conducteur.</p>

    <!-- Historique -->
    <h3>Historique des crédits</h3>
    <?php if (empty($transactions)): ?>
        <p>Aucune transaction pour le moment.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Solde à ce jour</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
            <tr class="transaction-<?= $t['type'] ?>">
                <td><?= date('d/m/Y', strtotime($t['date'])) ?></td>
                <td><?= $t['type'] === 'entree' ? 'Entrée' : 'Sortie' ?></td>
                <td>
                    <?php if ($t['id_trajet']): ?>
                        <a href="USR-details-trajet.php?id=<?= $t['id_trajet'] ?>">
                            <?= htmlspecialchars($t['description']) ?>
                        </a>
                    <?php else: ?>
                        <?= htmlspecialchars($t['description']) ?>
                    <?php endif; ?>
                </td>
                <td><?= $t['type'] === 'entree' ? '+' : '-' ?><?= $t['montant'] ?></td>
                <td><?= $t['solde_apres'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- Demande de crédits -->
    <h3>Besoin de crédits ?</h3>
    <p><em>Vous pouvez faire une demande de crédits à EcoRide.</em></p>
    <form action="../PHP/demande_credits.php" method="post" onsubmit="return confirmerDemande()">
        <button type="submit">Demander des crédits à EcoRide</button>
    </form>
    <p><strong>Remarque :</strong> Votre demande sera étudiée par notre équipe. Vous recevrez une réponse sous peu.</p>

    <!-- À propos -->
    <h3>À propos des crédits</h3>
    <p>Chez <strong>EcoRide</strong>, chaque trajet partagé est un pas vers un monde plus solidaire et écologique.</p>
    <p>Notre système de crédit permet de vérifier régulièrement si l'ensemble de nos trajets se passent dans le respect de notre charte de confiance (sécurité, respect, fiabilité), tout en encourageant les utilisateurs à adopter un comportement éco-responsable.</p>

</section>

</main>

<script>
function confirmerDemande() {
    return confirm("Confirmez-vous votre demande de crédits à EcoRide ?");
}
</script>

<?php include('../COMPONENTS/COMP-footer.html'); ?>
</body>
</html>