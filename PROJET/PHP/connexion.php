<?php
// connexion.php : connexion à la BDD

$host = "127.0.0.1"; // <- 127.0.0.1 pour forcer TCP et éviter les problèmes de socket
$db   = "eco_ride";
$user = "root";
$pass = "";

try {
    // On utilise les variables définies ci-dessus
    $bdd = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>