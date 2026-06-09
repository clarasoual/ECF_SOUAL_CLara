<?php
if (defined('AUTH_PHP_LOADED')) return;
define('AUTH_PHP_LOADED', true);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================================
// TIMEOUT DE SESSION
// Déconnexion automatique après inactivité
// ==============================================
define('SESSION_TIMEOUT', 30 * 60); // 30 minutes — modifiable ici

function checkSessionTimeout() {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            // Session expirée : on nettoie
            session_unset();
            session_destroy();
            session_start();
            return false;
        }
    }
    // Mettre à jour le timestamp d'activité à chaque requête
    $_SESSION['last_activity'] = time();
    return true;
}

// ==============================================
// UTILISATEURS
// ==============================================

if (!function_exists('requireLogin')) {
    function requireLogin() {
        checkSessionTimeout();
        if (!isset($_SESSION['user_id'])) {
            $currentPage = $_SERVER['REQUEST_URI'];
            $redirectUrl = urlencode($currentPage);
            header("Location: ../UTILISATEUR/USR-connexion-inscription.php?redirect=$redirectUrl");
            exit();
        }
    }
}

if (!function_exists('loginUser')) {
    function loginUser($id, $username, $email, $photo = 'default.jpg', $role = 'passager') {
        $_SESSION['user_id']       = $id;
        $_SESSION['username']      = $username;
        $_SESSION['email']         = $email;
        $_SESSION['photo']         = $photo;
        $_SESSION['role']          = $role;
        $_SESSION['last_activity'] = time(); // démarrer le timer
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

// ==============================================
// EMPLOYÉS
// ==============================================

if (!function_exists('requireEmploye')) {
    function requireEmploye() {
        checkSessionTimeout();
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