<?php
session_start();
require_once "connexion.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../UTILISATEUR/USR-connexion-inscription.php");
    exit;
}

$userId = $_SESSION['user_id'];

$date_naissance = $_POST['date_naissance'] ?? null;
$bio = trim($_POST['bio'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? null;

// Validation du rôle
$roles_autorises = ['passager', 'conducteur', 'passager-conducteur'];
if ($role && !in_array($role, $roles_autorises)) {
    $_SESSION['error'] = "❌ Rôle invalide.";
    header("Location: ../UTILISATEUR/USR-infos-perso.php");
    exit;
}

// =====================================
// Upload photo
// =====================================
$photoFileName = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/eco_ride/IMAGES/profiles/";

    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        $_SESSION['error'] = "❌ Dossier upload inaccessible.";
        header("Location: ../UTILISATEUR/USR-infos-perso.php");
        exit;
    }

    $photoFileName = uniqid() . "_" . basename($_FILES['photo']['name']);
    $targetPath = $uploadDir . $photoFileName;

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        $_SESSION['error'] = "❌ Échec de l'upload de la photo.";
        header("Location: ../UTILISATEUR/USR-infos-perso.php");
        exit;
    }
}

// =====================================
// Construire la requête SQL
// =====================================
$params = [
    ':date_naissance' => $date_naissance,
    ':bio' => $bio,
    ':id' => $userId
];

$sqlParts = [
    'date_naissance = :date_naissance',
    'bio = :bio'
];

if ($role !== null) {
    $sqlParts[] = 'role = :role';
    $params[':role'] = $role;
}

if ($password !== '') {
    $sqlParts[] = 'mot_de_passe = :password';
    $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
}

if ($photoFileName !== null) {
    $sqlParts[] = 'photo = :photo';
    $params[':photo'] = $photoFileName;
}

$sql = "UPDATE utilisateurs SET " . implode(", ", $sqlParts) . " WHERE id = :id";

$stmt = $bdd->prepare($sql);
$stmt->execute($params);

$_SESSION['success'] = "Informations mises à jour avec succès.";
header("Location: ../UTILISATEUR/USR-infos-perso.php");
exit;