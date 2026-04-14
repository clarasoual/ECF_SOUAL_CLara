<?php
session_start();
include('connexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../EMPLOYE/EMP-login-employe.php');
    exit;
}

$email = trim($_POST['email-connexion'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=champs');
    exit;
}

$stmt = $bdd->prepare("SELECT * FROM employes WHERE email = ?");
$stmt->execute([$email]);
$employe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employe || !password_verify($password, $employe['mot_de_passe'])) {
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=identifiants');
    exit;
}

if ($employe['suspendu']) {
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=suspendu');
    exit;
}

// Connexion réussie
$_SESSION['employe_id'] = $employe['id'];
$_SESSION['employe_prenom'] = $employe['prenom'];
$_SESSION['employe_email'] = $employe['email'];

header('Location: ../EMPLOYE/EMP-gestion-avis.php');exit;
?>