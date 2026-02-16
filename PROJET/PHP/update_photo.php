<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('connexion.php'); // inclut la connexion BDD

// Vérifie qu'un fichier a été envoyé
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
    $_SESSION['error'] = "❌ Aucun fichier reçu ou erreur upload.";
    header("Location: /eco_ride/PROJET/UTILISATEUR/USR-infos-perso.php");
    exit;
}

// Dossier où on va stocker les photos
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/eco_ride/IMAGES/profiles/";

// Vérifie que le dossier existe et est accessible
if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
    $_SESSION['error'] = "❌ Dossier upload inaccessible.";
    header("Location: /eco_ride/PROJET/UTILISATEUR/USR-infos-perso.php");
    exit;
}

// Crée un nom unique pour éviter les doublons
$fileName = uniqid() . "_" . basename($_FILES['photo']['name']);
$targetPath = $uploadDir . $fileName;

// Déplace le fichier depuis le dossier temporaire
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
    $_SESSION['error'] = "❌ Échec de l'upload.";
    header("Location: /eco_ride/PROJET/UTILISATEUR/USR-infos-perso.php");
    exit;
}

// ✅ Upload réussi, met à jour la BDD
try {
    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "❌ Utilisateur non connecté.";
        header("Location: /eco_ride/PROJET/UTILISATEUR/USR-infos-perso.php");
        exit;
    }

    // Met à jour la photo dans la table "utilisateurs"
    $stmt = $bdd->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
    $stmt->execute([$fileName, $_SESSION['user_id']]);

    $_SESSION['success'] = "Photo uploadée avec succès !";
} catch (PDOException $e) {
    $_SESSION['error'] = "❌ Erreur BDD : " . $e->getMessage();
}

// Redirection vers la page profil
header("Location: /eco_ride/PROJET/UTILISATEUR/USR-infos-perso.php");
exit;
