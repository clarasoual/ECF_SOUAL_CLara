<?php
require_once('auth.php');
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? '../UTILISATEUR/USR-index.php';

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

if ($user['suspendu']) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php?error=suspendu');
    exit;
}

// Connexion réussie — on passe le rôle
loginUser(
    $user['id'],
    $user['pseudo'] ?? '',
    $user['email'],
    $user['photo'] ?? 'default.jpg',
    $user['role'] ?? 'passager'
);

header('Location: ' . $redirect);
exit;
?>