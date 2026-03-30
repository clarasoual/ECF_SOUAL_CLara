<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../PHP/connexion.php'); // $bdd est défini ici

// Mot de passe par défaut
$default_password = 'Admin123!';

// Hacher le mot de passe
$hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

// Mettre à jour tous les admins sans mot de passe
$stmt = $bdd->prepare("UPDATE admins SET password = ? WHERE password IS NULL OR password = ''");
$stmt->execute([$hashed_password]);

echo "Tous les admins ont maintenant un mot de passe haché par défaut : $default_password";
?>