<?php
require_once('auth.php');
require_once('connexion.php');
require_once('logs.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? '../UTILISATEUR/USR-index.php';

if ($email === '' || $password === '') {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php?error=champs_manquants');
    exit;
}

$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['mot_de_passe'])) {
    logAction('connexion_echouee', "Tentative de connexion échouée pour : $email", 'WARNING');
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php?error=identifiants_incorrects');
    exit;
}

if ($user['suspendu']) {
    logAction('connexion_suspendu', "Tentative de connexion d'un compte suspendu : $email", 'WARNING', $user['id']);
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php?error=suspendu');
    exit;
}

loginUser(
    $user['id'],
    $user['pseudo'] ?? '',
    $user['email'],
    $user['photo'] ?? 'default.jpg',
    $user['role'] ?? 'passager'
);

logAction('connexion', "Connexion réussie : {$user['email']}", 'INFO', $user['id']);

header('Location: ' . $redirect);
exit;