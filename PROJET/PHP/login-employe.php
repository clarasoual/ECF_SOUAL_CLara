<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('connexion.php');
require_once('logs.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../EMPLOYE/EMP-login-employe.php');
    exit;
}

$email    = trim($_POST['email-connexion'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=champs');
    exit;
}

$stmt = $bdd->prepare("SELECT * FROM employes WHERE email = ?");
$stmt->execute([$email]);
$employe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employe || !password_verify($password, $employe['mot_de_passe'])) {
    logAction('connexion_employe_echouee', "Tentative de connexion employé échouée : $email", 'WARNING');
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=identifiants');
    exit;
}

if ($employe['suspendu']) {
    logAction('connexion_employe_suspendu', "Tentative de connexion d'un employé suspendu : $email", 'WARNING', $employe['id']);
    header('Location: ../EMPLOYE/EMP-login-employe.php?error=suspendu');
    exit;
}

$_SESSION['employe_id']     = $employe['id'];
$_SESSION['employe_prenom'] = $employe['prenom'];
$_SESSION['employe_email']  = $employe['email'];

logAction('connexion_employe', "Connexion employé réussie : $email", 'INFO', $employe['id']);

header('Location: ../EMPLOYE/EMP-gestion-avis.php');
exit;
?>