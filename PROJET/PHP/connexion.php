<?php
// connexion.php : se connecte à la BDD

$host = "localhost";      // XAMPP = localhost
$db   = "eco_ride";       // nom de ta base
$user = "root";           // utilisateur par défaut XAMPP
$pass = "";               // mot de passe par défaut XAMPP (vide)

// On essaie de se connecter
try {
    $bdd = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion OK !"; // juste pour tester
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
