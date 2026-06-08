<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('connexion.php');
include('transactions.php');
require_once('logs.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];
$id_trajet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_trajet) {
    die("Trajet invalide.");
}

$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ?");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    die("Trajet introuvable.");
}

if ($trajet['places_disponibles'] <= 0) {
    die("Plus de places disponibles.");
}

if ($trajet['id_conducteur'] == $id_utilisateur) {
    die("Vous ne pouvez pas réserver votre propre trajet.");
}

$stmt = $bdd->prepare("SELECT id FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
if ($stmt->fetch()) {
    die("Vous êtes déjà inscrit à ce trajet.");
}

$stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$credit = $stmt->fetch(PDO::FETCH_ASSOC);
$solde = $credit ? $credit['solde'] : 0;

$prix = $trajet['prix'];

if ($solde < $prix) {
    die("Vous n'avez pas assez de crédits. Solde actuel : " . $solde . " crédits, prix du trajet : " . $prix . " crédits.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {

    $nouveau_solde = $solde - $prix;
    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nouveau_solde, $id_utilisateur]);

    ajouterTransaction(
        $id_utilisateur,
        'sortie',
        'Réservation trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']),
        $prix,
        $nouveau_solde,
        $id_trajet
    );

    $stmt = $bdd->prepare("INSERT INTO trajets_passagers (id_trajet, id_passager, statut) VALUES (?, ?, 'reserve')");
    $stmt->execute([$id_trajet, $id_utilisateur]);

    $stmt = $bdd->prepare("UPDATE trajets SET places_disponibles = places_disponibles - 1 WHERE id = ?");
    $stmt->execute([$id_trajet]);

    $stmt = $bdd->prepare("UPDATE trajets SET statut = 'complet' WHERE id = ? AND places_disponibles = 0");
    $stmt->execute([$id_trajet]);

    logAction(
        'reservation',
        "Réservation du trajet #$id_trajet ({$trajet['depart']} → {$trajet['arrivee']}) — $prix crédits débités",
        'INFO',
        $id_utilisateur
    );

    header('Location: ../UTILISATEUR/USR-details-trajet.php?id=' . $id_trajet . '&success=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer la réservation</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        main { max-width: 520px; margin: 2rem auto; padding: 1rem; display: block; }
        main h1 { font-size: 1.5rem; color: var(--vert-doux); margin-bottom: 1.5rem; }
        .reservation-recap { background-color: var(--beige-fonce); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px var(--shadow); display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem; border-left: 4px solid var(--vert-doux); }
        .reservation-recap p { font-family: 'Quicksand', sans-serif; font-size: 0.95rem; color: var(--texte); margin: 0; }
        .reservation-recap strong { color: var(--vert-doux); }
        main > p { font-family: 'Quicksand', sans-serif; font-size: 1rem; color: var(--gris-doux); margin-bottom: 1.5rem; }
        form { display: flex; flex-direction: column; gap: 0.75rem; }
        .btn-submit { background-color: var(--vert-doux); color: black; font-family: 'Quicksand', sans-serif; font-weight: 700; font-size: 1rem; padding: 0.85rem; border-radius: 8px; border: none; cursor: pointer; width: 100%; text-align: center; }
        .btn-submit:hover { background-color: #8ec9a4; }
        .btn-annuler { background: transparent; color: var(--gris-doux); font-family: 'Quicksand', sans-serif; font-weight: 600; padding: 0.85rem; border-radius: 8px; border: 1px solid rgba(224,224,224,0.3); text-decoration: none; width: 100%; text-align: center; display: block; }
        .btn-annuler:hover { border-color: var(--gris-doux); color: var(--texte); text-decoration: none; }
    </style>
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
    <h1>Confirmer la réservation</h1>

    <div class="reservation-recap">
        <p><strong>Trajet :</strong> <?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?></p>
        <p><strong>Heure :</strong> <?= htmlspecialchars($trajet['heure_depart']) ?></p>
        <p><strong>Prix :</strong> <?= $prix ?> crédits</p>
        <p><strong>Votre solde actuel :</strong> <?= $solde ?> crédits</p>
        <p><strong>Solde après réservation :</strong> <?= $solde - $prix ?> crédits</p>
    </div>

    <p>Confirmez-vous l'utilisation de <strong><?= $prix ?> crédits</strong> pour ce trajet ?</p>

    <form action="../PHP/reserver-trajet.php?id=<?= $id_trajet ?>" method="POST">
        <input type="hidden" name="confirmer" value="1">
        <button type="submit" class="btn-submit">✅ Confirmer la réservation</button>
        <a href="../UTILISATEUR/USR-details-trajet.php?id=<?= $id_trajet ?>" class="btn-annuler">Annuler</a>
    </form>
</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>