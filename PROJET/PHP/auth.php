<?php
// 🔒 Verrou pour éviter inclusions multiples
if (defined('AUTH_PHP_LOADED')) return;
define('AUTH_PHP_LOADED', true);

// --- Afficher toutes les erreurs ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Démarrer la session si pas déjà faite ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Fonctions pour gérer la connexion utilisateur ---

if (!function_exists('requireLogin')) {
    function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $currentPage = $_SERVER['REQUEST_URI'];
            $redirectUrl = urlencode($currentPage);
            header("Location: ../UTILISATEUR/USR-connexion-inscription.php?redirect=$redirectUrl");
            exit();
        }
    }
}

if (!function_exists('loginUser')) {
    function loginUser($id, $username, $email, $photo = 'default.jpg') {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['photo'] = $photo;
    }
}

if (!function_exists('logoutUser')) {
    function logoutUser() {
        session_unset();
        session_destroy();
        header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
        exit();
    }
}

// --- Fonctions pour gérer la connexion employé ---

if (!function_exists('requireEmploye')) {
    function requireEmploye() {
        if (!isset($_SESSION['employe_id'])) {
            header("Location: ../EMPLOYE/EMP-login-employe.php");
            exit();
        }
    }
}

if (!function_exists('logoutEmploye')) {
    function logoutEmploye() {
        session_unset();
        session_destroy();
        header('Location: ../EMPLOYE/EMP-login-employe.php');
        exit();
    }
}
?>