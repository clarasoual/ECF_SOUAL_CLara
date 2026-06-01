<?php
include('connexion.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}
?>