<?php
// Affiche toutes les erreurs pour debug (supprime en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('connexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Méthode non autorisée.');
}

// Récupérer les champs du formulaire
$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Vérification des champs
if ($prenom === '' || $nom === '' || $email === '' || $password === '' || $password_confirm === '') {
    die("Tous les champs sont obligatoires.");
}

if ($password !== $password_confirm) {
    die("Les mots de passe ne correspondent pas.");
}

// Vérifier si l'email existe déjà
$stmt = $bdd->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) {
    die("Cette adresse email est déjà utilisée.");
}

// Hash du mot de passe
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Valeurs par défaut
$role = 'passager';
$photo = 'default.jpg';

// Insertion dans la BDD
$stmt = $bdd->prepare("
    INSERT INTO utilisateurs (
        nom,
        prenom,
        email,
        mot_de_passe,
        role,
        photo,
        date_inscription
    ) VALUES (
        :nom,
        :prenom,
        :email,
        :mot_de_passe,
        :role,
        :photo,
        NOW()
    )
");
$stmt->execute([
    'nom' => $nom,
    'prenom' => $prenom,
    'email' => $email,
    'mot_de_passe' => $password_hash,
    'role' => $role,
    'photo' => $photo
]);

// Connexion automatique
$new_user_id = $bdd->lastInsertId();
$_SESSION['user_id'] = $new_user_id;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = $role;

// Sécuriser le prénom pour le HTML
$prenom_safe = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');

// Message de bienvenue + redirection vers USR-inscription2.php
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
<!-- Redirection automatique après 6 secondes -->
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
