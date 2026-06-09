<?php
if (defined('AUTH_PHP_LOADED')) return;
define('AUTH_PHP_LOADED', true);

// ==============================================
// ENVIRONNEMENT : dev ou prod
// En prod sur Railway, définir APP_ENV=production
// dans les variables d'environnement Railway
// En local sur XAMPP, APP_ENV n'existe pas → dev
// ==============================================
$isProd = (getenv('APP_ENV') === 'production');

if ($isProd) {
    // Production : aucune erreur affichée côté client
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
} else {
    // Local : tout afficher pour débugger
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==============================================
// TIMEOUT DE SESSION
// Déconnexion automatique après inactivité
// ==============================================
define('SESSION_TIMEOUT', 30 * 60); // 30 minutes

function checkSessionTimeout() {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
            session_unset();
            session_destroy();
            session_start();
            return false;
        }
    }
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
        $_SESSION['last_activity'] = time();
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