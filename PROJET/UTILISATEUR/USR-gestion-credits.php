<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');
include('../PHP/transactions.php');

$id_utilisateur = $_SESSION['user_id'];

$stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$credit = $stmt->fetch(PDO::FETCH_ASSOC);
$solde = $credit ? $credit['solde'] : 0;

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

<?php include('../COMPONENTS/COMP-header.php'); ?>

<!-- SELECT MOBILE -->
<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Mon compte —</option>
    <option value="../UTILISATEUR/USR-infos-perso.php">Informations personnelles</option>
    <option value="../UTILISATEUR/USR-mes-trajets.php">Mes trajets</option>
    <option value="../UTILISATEUR/USR-avis.php">Avis</option>
    <option value="../UTILISATEUR/USR-gestion-credits.php">Crédits</option>
    <option value="../UTILISATEUR/USR-infos-conducteur.php">Informations conducteur</option>
</select>

<main>

<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<section class="credits-section">

    <h2>Mes crédits</h2>

    <?php if (isset($_GET['demande']) && $_GET['demande'] === 'ok'): ?>
        <p class="message-succes">✅ Votre demande a bien été envoyée. Notre équipe reviendra vers vous rapidement.</p>
    <?php endif; ?>

    <p class="solde-actuel"><strong>Solde actuel :</strong> <?= $solde ?> crédits</p>
    <p style="font-family:'Quicksand',sans-serif; font-size:0.95rem; color:var(--gris-doux); margin-bottom:1.5rem;">
        Vous gagnez des crédits en proposant des trajets à d'autres utilisateurs, lorsque vous êtes conducteur.
    </p>

    <h3>Historique des crédits</h3>
    <?php if (empty($transactions)): ?>
        <p style="font-family:'Quicksand',sans-serif; color:var(--gris-doux);">Aucune transaction pour le moment.</p>
    <?php else: ?>
    <div class="table-responsive-credits">
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
    </div>
    <?php endif; ?>

    <h3>Besoin de crédits ?</h3>
    <div class="demande-credits-block">
        <p>Vous pouvez faire une demande de crédits à EcoRide. Notre équipe étudiera votre demande et vous recevrez une réponse sous peu.</p>
        <form action="../PHP/demande_credits.php" method="post" onsubmit="return confirmerDemande()">
            <button type="submit" class="btn-demande-credits">Demander des crédits à EcoRide</button>
        </form>
    </div>

    <h3>À propos des crédits</h3>
    <div class="credits-about">
        <p>Chez <strong style="color:var(--texte);">EcoRide</strong>, chaque trajet partagé est un pas vers un monde plus solidaire et écologique.</p>
        <p>Notre système de crédit permet de vérifier régulièrement si l'ensemble de nos trajets se passent dans le respect de notre charte de confiance (sécurité, respect, fiabilité), tout en encourageant les utilisateurs à adopter un comportement éco-responsable.</p>
    </div>

</section>

</main>

<script>
function confirmerDemande() {
    return confirm("Confirmez-vous votre demande de crédits à EcoRide ?");
}
</script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>