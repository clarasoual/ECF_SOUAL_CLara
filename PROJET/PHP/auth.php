<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        $currentPage = $_SERVER['REQUEST_URI'];
        $redirectUrl = urlencode($currentPage);
        header("Location: ../UTILISATEUR/USR-connexion-inscription.php?redirect=$redirectUrl");
        exit();
    }
}

function loginUser($id, $username, $email, $photo = 'default.jpg'){
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['photo'] = $photo;
}

function logoutUser(){
    session_unset();
    session_destroy();
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit();
}
?>