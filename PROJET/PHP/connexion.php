<?php
// connexion.php : connexion à la BDD

$host = getenv('DB_HOST') ?: '127.0.0.1';
$db   = getenv('DB_NAME') ?: 'eco_ride';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$port = getenv('DB_PORT') ?: '3306';

try {
    $bdd = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Erreur de connexion à la BDD — on logue si possible puis on arrête
    if (function_exists('logAction')) {
        logAction('erreur_connexion_bdd', $e->getMessage(), 'ERROR');
    }
    die("Erreur de connexion : " . $e->getMessage());
}

// Handler global pour toutes les exceptions PDO non catchées ailleurs
set_exception_handler(function (Throwable $e) {
    if (function_exists('logAction')) {
        logAction(
            'erreur_sql',
            '[' . get_class($e) . '] ' . $e->getMessage() . ' — ' . $e->getFile() . ':' . $e->getLine(),
            'ERROR'
        );
    }
    // En prod on n'affiche pas le détail, on log seulement
    http_response_code(500);
    echo "Une erreur est survenue. Veuillez réessayer.";
});
?>