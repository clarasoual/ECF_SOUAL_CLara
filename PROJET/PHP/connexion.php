<?php
// connexion.php : connexion à la BDD

$host = "localhost";
$db   = "eco_ride";
$user = "root";
$pass = "";

try {
    // On renomme $pdo en $bdd pour que ce soit cohérent avec le reste du projet
    $bdd = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>