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

// Vérifier que c'est bien le conducteur
$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND id_conducteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Action non autorisée.");
}

// Passer le statut à termine
$stmt = $bdd->prepare("UPDATE trajets SET statut = 'termine' WHERE id = ?");
$stmt->execute([$id_trajet]);

// Passer tous les passagers à termine
$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'termine' WHERE id_trajet = ? AND statut = 'reserve'");
$stmt->execute([$id_trajet]);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?finished=1');
exit;