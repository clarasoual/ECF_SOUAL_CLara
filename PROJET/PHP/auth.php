<?php
// Démarrer la session à chaque page qui inclut ce fichier
session_start();

// --- Fonctions utiles pour la session ---

// Vérifie si l'utilisateur est connecté, sinon redirige vers login
function requireLogin() {
    if(!isset($_SESSION['user_id'])){
        // page actuelle
        $currentPage = $_SERVER['REQUEST_URI'];
        // encode pour l'URL
        $redirectUrl = urlencode($currentPage);
        header("Location: USR-connexion-inscription.php?redirect=$redirectUrl");
        exit();
    }
}

// Retourne les infos de l'utilisateur connecté
function currentUser() {
    return isset($_SESSION['user_id']) ? [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? ''
    ] : null;
}

// Connecter un utilisateur après login
function loginUser($id, $username, $email){
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
}

// Déconnecter l'utilisateur
function logoutUser(){
    session_unset();
    session_destroy();
    header('Location: USR-connexion-inscription.php'); // Redirige vers la page de connexion
    exit();
}
?>
