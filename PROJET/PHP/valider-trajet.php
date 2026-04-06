<?php
session_start();
include('connexion.php');
include('transactions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];
$validation = $_POST['validation'] ?? '';

// Récupérer le trajet
$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND statut = 'termine'");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Trajet invalide.");
}

// Vérifier que l'utilisateur est bien passager
$stmt = $bdd->prepare("SELECT * FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ? AND statut = 'termine'");
$stmt->execute([$id_trajet, $id_utilisateur]);
$inscription = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$inscription) {
    die("Action non autorisée.");
}

if ($validation === 'ok') {
    // Tout s'est bien passé → créditer le chauffeur
    $prix = $trajet['prix'];
    $credits_conducteur = $prix - 2; // 2 crédits pour la plateforme

    // Récupérer le solde actuel du conducteur
    $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
    $stmt->execute([$trajet['id_conducteur']]);
    $credit = $stmt->fetch(PDO::FETCH_ASSOC);
    $solde_actuel = $credit ? $credit['solde'] : 0;
    $nouveau_solde = $solde_actuel + $credits_conducteur;

    // Mettre à jour le solde
    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nouveau_solde, $trajet['id_conducteur']]);

    // Enregistrer la transaction
    ajouterTransaction(
        $trajet['id_conducteur'],
        'entree',
        'Trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']) . ' validé',
        $credits_conducteur,
        $nouveau_solde,
        $id_trajet
    );

    // Marquer la validation du passager
    $stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'valide' WHERE id_trajet = ? AND id_passager = ?");
    $stmt->execute([$id_trajet, $id_utilisateur]);

    header('Location: ../UTILISATEUR/USR-mes-trajets.php?validated=1');

} elseif ($validation === 'probleme') {
    // Problème → marquer pour l'employé
    $stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'litige' WHERE id_trajet = ? AND id_passager = ?");
    $stmt->execute([$id_trajet, $id_utilisateur]);

    header('Location: ../UTILISATEUR/USR-mes-trajets.php?litige=1');
}

exit;