<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id     = $_SESSION['user_id'];
$vehicule_id = $_POST['vehicule_id'] ?? null;

if (!$vehicule_id) {
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

// Vérifier que le véhicule appartient bien à l'utilisateur
$stmt_check = $bdd->prepare("SELECT vehicule_id FROM vehicules WHERE vehicule_id = ? AND id_utilisateur = ?");
$stmt_check->execute([$vehicule_id, $user_id]);

if (!$stmt_check->fetch()) {
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

$stmt = $bdd->prepare("DELETE FROM vehicules WHERE vehicule_id = ? AND id_utilisateur = ?");
$stmt->execute([$vehicule_id, $user_id]);

header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
exit;