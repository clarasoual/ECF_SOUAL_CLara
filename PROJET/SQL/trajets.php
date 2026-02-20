<?php
// sql/trajets.php
include('../config/connexion.php'); // ajuste le chemin si nécessaire

function getTrajetsActifs($bdd) {
    // Requête : tous les trajets dont le statut est 'actif'
    $stmt = $bdd->prepare("SELECT * FROM trajets WHERE statut = 'actif'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // retourne un tableau associatif
}
?>
