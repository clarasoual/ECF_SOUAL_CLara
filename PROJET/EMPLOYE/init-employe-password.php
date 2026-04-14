<?php
include('../PHP/connexion.php');

$default_password = 'mdp123';
$hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

$stmt = $bdd->prepare("UPDATE employes SET mot_de_passe = ? WHERE mot_de_passe IS NULL OR mot_de_passe = ''");
$stmt->execute([$hashed_password]);

echo "Tous les employés ont maintenant le mot de passe : $default_password";
?>