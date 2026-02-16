<?php
session_start();
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    die('Champs manquants.');
}

$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Email incorrect.');
}

if (!password_verify($password, $user['mot_de_passe'])) {
    die('Mot de passe incorrect.');
}

// ✅ session OK
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];

// ✅ REDIRECTION CORRIGÉE
header('Location: ../UTILISATEUR/USR-infos-perso.php');
exit;
