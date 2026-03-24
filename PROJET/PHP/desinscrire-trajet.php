<?php
require_once 'auth.php';
requireLogin();
require_once 'connexion.php';

$idUser = $_SESSION['user_id'];
$idTrajet = $_POST['id_trajet'] ?? 0;

if ($idTrajet) {
    $stmt = $bdd->prepare("DELETE FROM trajets_passagers WHERE id_passager = ? AND id_trajet = ?");
    $stmt->execute([$idUser, $idTrajet]);
}

header("Location: ../UTILISATEUR/USR-details-trajet.php?id=$idTrajet&success=1");
exit;