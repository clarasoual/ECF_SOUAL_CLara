<?php
session_start();
include('../PHP/connexion.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifier que le formulaire est envoyé en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-inscription2.php');
    exit;
}

// Récupération des données
$role = $_POST['role'] ?? '';
$date_naissance = !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null;
$bio = trim($_POST['bio'] ?? '');

// Vérification du rôle
$roles_valides = ['passager', 'conducteur', 'passager-conducteur'];

if (!in_array($role, $roles_valides)) {
    die("Rôle invalide.");
}

// Mise à jour dans la table utilisateurs
$stmt = $bdd->prepare("
    UPDATE utilisateurs
    SET role = :role,
        date_naissance = :date_naissance,
        bio = :bio,
        profile_completed = 1
    WHERE id = :id
");

$stmt->execute([
    ':role' => $role,
    ':date_naissance' => $date_naissance,
    ':bio' => $bio,
    ':id' => $user_id
]);

// 🔥 Redirection selon le rôle
if ($role === 'conducteur' || $role === 'passager-conducteur') {
    header("Location: ../UTILISATEUR/USR-infos-conducteur.php");
} else {
    header("Location: ../UTILISATEUR/USR-infos-perso.php");
}

exit;
?>
