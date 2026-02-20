<?php
include('connexion.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas, déconnexion
if (!$user) {
    session_destroy();
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}
?>
