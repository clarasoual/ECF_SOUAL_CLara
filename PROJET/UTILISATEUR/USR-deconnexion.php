<?php
// Inclure le fichier auth pour gérer la session
include('../PHP/auth.php');

// Détruire la session
logoutUser(); // Cette fonction supprime la session et redirige
?>
