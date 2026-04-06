<?php
session_start();
include('connexion.php');

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
$stmt = $bdd->prepare("SELECT id FROM trajets WHERE id = ? AND id_conducteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
if (!$stmt->fetch()) {
    die("Action non autorisée.");
}

// Passer le statut à en_cours
$stmt = $bdd->prepare("UPDATE trajets SET statut = 'en_cours' WHERE id = ?");
$stmt->execute([$id_trajet]);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?started=1');
exit;