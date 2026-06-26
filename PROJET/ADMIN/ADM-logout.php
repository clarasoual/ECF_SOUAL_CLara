<?php
session_start();
session_destroy(); // détruire la session
header('Location: ADM-login.php'); // rediriger vers login
exit();
?>
