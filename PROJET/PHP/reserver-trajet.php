<?php
session_start();
include('connexion.php');
include('transactions.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];
$id_trajet = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_trajet) {
    die("Trajet invalide.");
}

// Récupérer le trajet
$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ?");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    die("Trajet introuvable.");
}

// Vérifications
if ($trajet['places_disponibles'] <= 0) {
    die("Plus de places disponibles.");
}

if ($trajet['id_conducteur'] == $id_utilisateur) {
    die("Vous ne pouvez pas réserver votre propre trajet.");
}

// Vérifier si déjà inscrit
$stmt = $bdd->prepare("SELECT id FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
if ($stmt->fetch()) {
    die("Vous êtes déjà inscrit à ce trajet.");
}

// Récupérer le solde
$stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$credit = $stmt->fetch(PDO::FETCH_ASSOC);
$solde = $credit ? $credit['solde'] : 0;

$prix = $trajet['prix'];

// Vérifier si assez de crédits
if ($solde < $prix) {
    die("Vous n'avez pas assez de crédits. Solde actuel : " . $solde . " crédits, prix du trajet : " . $prix . " crédits.");
}

// Si confirmation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {

    // Débiter les crédits
    $nouveau_solde = $solde - $prix;
    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nouveau_solde, $id_utilisateur]);

    // Enregistrer la transaction
    ajouterTransaction(
        $id_utilisateur,
        'sortie',
        'Réservation trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']),
        $prix,
        $nouveau_solde,
        $id_trajet
    );

    // Inscrire le passager
    $stmt = $bdd->prepare("INSERT INTO trajets_passagers (id_trajet, id_passager) VALUES (?, ?)");
    $stmt->execute([$id_trajet, $id_utilisateur]);

    // Mettre à jour les places disponibles
    $stmt = $bdd->prepare("UPDATE trajets SET places_disponibles = places_disponibles - 1 WHERE id = ?");
    $stmt->execute([$id_trajet]);

    // Mettre à jour le statut si plus de places
    $stmt = $bdd->prepare("UPDATE trajets SET statut = 'complet' WHERE id = ? AND places_disponibles = 0");
    $stmt->execute([$id_trajet]);

    // Rediriger vers la page du trajet
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