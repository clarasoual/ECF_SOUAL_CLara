<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('connexion.php');
require_once('logs.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Méthode non autorisée.');
}

$prenom           = trim($_POST['prenom'] ?? '');
$nom              = trim($_POST['nom'] ?? '');
$email            = trim($_POST['email'] ?? '');
$password         = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if ($prenom === '' || $nom === '' || $email === '' || $password === '' || $password_confirm === '') {
    die("Tous les champs sont obligatoires.");
}

if ($password !== $password_confirm) {
    die("Les mots de passe ne correspondent pas.");
}

$stmt = $bdd->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) {
    logAction('inscription_echouee', "Email déjà utilisé : $email", 'WARNING');
    die("Cette adresse email est déjà utilisée.");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$role  = 'passager';
$photo = 'default.jpg';

$stmt = $bdd->prepare("
    INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, photo, date_inscription)
    VALUES (:nom, :prenom, :email, :mot_de_passe, :role, :photo, NOW())
");
$stmt->execute([
    'nom'          => $nom,
    'prenom'       => $prenom,
    'email'        => $email,
    'mot_de_passe' => $password_hash,
    'role'         => $role,
    'photo'        => $photo
]);

$new_user_id = $bdd->lastInsertId();

$stmt = $bdd->prepare("INSERT INTO credits (id_utilisateur, solde) VALUES (?, 20)");
$stmt->execute([$new_user_id]);

include('transactions.php');
ajouterTransaction($new_user_id, 'entree', 'Crédits de bienvenue', 20, 20);

$_SESSION['user_id']    = $new_user_id;
$_SESSION['user_email'] = $email;
$_SESSION['user_role']  = $role;

logAction('inscription', "Nouvel utilisateur inscrit : $email", 'INFO', $new_user_id);

$prenom_safe = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');

echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription réussie</title>
<link rel="stylesheet" href="../CSS/style_global.css">
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: 'Quicksand', sans-serif;
        background: #f0f4f8;
    }
    .message {
        text-align: center;
        background: #ffffff;
        padding: 2.5rem 3rem;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        border-left: 6px solid #4CAF50;
        max-width: 400px;
        animation: fadeIn 0.8s ease-in-out;
    }
    .message h2 { color: #4CAF50; margin-bottom: 1rem; }
    .message p { color: #333; font-size: 1.1rem; margin: 0.5rem 0; }
    .message .redirect { font-size: 0.9rem; color: #555; margin-top: 1rem; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
</style>
<meta http-equiv="refresh" content="6;url=/eco_ride/PROJET/UTILISATEUR/USR-inscription2.php">
</head>
<body>
    <div class="message">
        <h2>✅ Inscription réussie !</h2>
        <p>Bienvenue, {$prenom_safe} ! Complète ton profil pour commencer à utiliser Eco Ride.</p>
        <p class="redirect">Redirection automatique vers la suite de l'inscription dans 6 secondes...</p>
    </div>
</body>
</html>
HTML;

exit;
?>
