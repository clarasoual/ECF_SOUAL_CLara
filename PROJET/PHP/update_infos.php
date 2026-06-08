<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "connexion.php";
require_once "logs.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../UTILISATEUR/USR-connexion-inscription.php");
    exit;
}

$userId = $_SESSION['user_id'];

$date_naissance = $_POST['date_naissance'] ?? null;
$bio            = trim($_POST['bio'] ?? '');
$password       = $_POST['password'] ?? '';
$role           = $_POST['role'] ?? null;

$roles_autorises = ['passager', 'conducteur', 'passager-conducteur'];
if ($role && !in_array($role, $roles_autorises)) {
    $_SESSION['error'] = "❌ Rôle invalide.";
    header("Location: ../UTILISATEUR/USR-infos-perso.php");
    exit;
}

$photoFileName = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/eco_ride/IMAGES/profiles/";

    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        $_SESSION['error'] = "❌ Dossier upload inaccessible.";
        header("Location: ../UTILISATEUR/USR-infos-perso.php");
        exit;
    }

    $photoFileName = uniqid() . "_" . basename($_FILES['photo']['name']);
    $targetPath    = $uploadDir . $photoFileName;

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        $_SESSION['error'] = "❌ Échec de l'upload de la photo.";
        header("Location: ../UTILISATEUR/USR-infos-perso.php");
        exit;
    }
}

$params   = [':date_naissance' => $date_naissance, ':bio' => $bio, ':id' => $userId];
$sqlParts = ['date_naissance = :date_naissance', 'bio = :bio'];

if ($role !== null) {
    $sqlParts[]      = 'role = :role';
    $params[':role'] = $role;
}

if ($password !== '') {
    $sqlParts[]          = 'mot_de_passe = :password';
    $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
}

if ($photoFileName !== null) {
    $sqlParts[]        = 'photo = :photo';
    $params[':photo']  = $photoFileName;
}

$sql  = "UPDATE utilisateurs SET " . implode(", ", $sqlParts) . " WHERE id = :id";
$stmt = $bdd->prepare($sql);
$stmt->execute($params);

// Mettre à jour le rôle en session si changé
if ($role !== null) {
    $_SESSION['role'] = $role;
}

// Log
$details = [];
if ($role !== null) $details[] = "rôle → $role";
if ($password !== '') $details[] = "mot de passe modifié";
if ($photoFileName !== null) $details[] = "photo mise à jour";
$details_str = !empty($details) ? implode(', ', $details) : "bio/date de naissance";

logAction('profil_modifie', "Profil modifié : $details_str", 'INFO', $userId);

$_SESSION['success'] = "Informations mises à jour avec succès.";
header("Location: ../UTILISATEUR/USR-infos-perso.php");
exit;