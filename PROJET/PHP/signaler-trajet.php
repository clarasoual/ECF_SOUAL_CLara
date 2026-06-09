<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('connexion.php');
require_once('logs.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet      = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];
$commentaire    = trim($_POST['commentaire_signalement'] ?? '');

$stmt = $bdd->prepare("SELECT * FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ? AND statut = 'termine'");
$stmt->execute([$id_trajet, $id_utilisateur]);
$inscription = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$inscription) {
    die("Action non autorisée.");
}

$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'litige' WHERE id_trajet = ? AND id_passager = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);

$stmt = $bdd->prepare("
    INSERT INTO signalements (id_trajet, id_utilisateur, motif, type)
    VALUES (?, ?, ?, 'passager_vers_conducteur')
");
$stmt->execute([$id_trajet, $id_utilisateur, $commentaire]);

logAction(
    'signalement',
    "Signalement du trajet #$id_trajet — motif : $commentaire",
    'WARNING',
    $id_utilisateur
);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?litige=1');
exit;