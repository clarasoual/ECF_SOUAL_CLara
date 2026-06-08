<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../PHP/auth.php'); 
requireLogin();
require('../PHP/connexion.php');
require_once('../PHP/logs.php');

if (!isset($_SESSION['trajet_temp'])) {
    header('Location: USR-proposer-trajet.php');
    exit;
}

$trajet        = $_SESSION['trajet_temp'];
$id_conducteur = $_SESSION['user_id'] ?? 0;

if (!$id_conducteur) {
    die("Utilisateur non identifié.");
}

$etapes     = array_values(array_filter($trajet['etapes'] ?? []));
$etapes_str = !empty($etapes) ? json_encode($etapes, JSON_UNESCAPED_UNICODE) : null;

try {
    $sql = "INSERT INTO trajets
        (id_conducteur, depart, arrivee, date_depart, heure_depart, heure_arrivee, vehicule_id, places_disponibles, prix, statut, etapes, commentaire)
        VALUES (:id_conducteur, :depart, :arrivee, :date_depart, :heure_depart, :heure_arrivee, :vehicule_id, :places_disponibles, :prix, 'publie', :etapes, :commentaire)";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':id_conducteur'      => $id_conducteur,
        ':depart'             => $trajet['departure'],
        ':arrivee'            => $trajet['arrival'],
        ':date_depart'        => $trajet['date'],
        ':heure_depart'       => $trajet['time'],
        ':heure_arrivee'      => $trajet['time_arrivee'] ?? null,
        ':vehicule_id'        => $trajet['vehicle_used'],
        ':places_disponibles' => $trajet['places'],
        ':prix'               => intval($trajet['prix'] ?? 2),
        ':etapes'             => $etapes_str,
        ':commentaire'        => $trajet['commentaire'] ?? ''
    ]);

    $id_trajet = $bdd->lastInsertId();

    logAction(
        'trajet_cree',
        "Nouveau trajet #$id_trajet créé : {$trajet['departure']} → {$trajet['arrival']} le {$trajet['date']} à {$trajet['time']} — {$trajet['places']} place(s) — " . intval($trajet['prix'] ?? 2) . " crédit(s)",
        'INFO',
        $id_conducteur
    );

    unset($_SESSION['trajet_temp']);

} catch (PDOException $e) {
    die("Erreur lors de l'enregistrement du trajet : " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trajet confirmé</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-proposer-trajet-3.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main class="trip-confirmation">
    <section class="confirmation-message">

        <div class="success-icon">✅</div>
        <h2 class="step-title">Votre trajet est en ligne !</h2>
        <p>Félicitations ! Votre proposition de trajet a été enregistrée et est désormais visible par les passagers.</p>

        <div class="confirmation-actions">
            <a href="../UTILISATEUR/USR-mes-trajets.php" class="btn-action btn-primary">
                🗓️ Voir mes trajets
            </a>
            <a href="../UTILISATEUR/USR-proposer-trajet.php?new=1" class="btn-action btn-secondary">
                ➕ Proposer un nouveau trajet
            </a>
            <a href="../UTILISATEUR/USR-index.php" class="btn-action btn-ghost">
                ← Retour à l'accueil
            </a>
        </div>

    </section>
</main>

<script src="../JS/USR-proposer-trajet3.js"></script>
<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>