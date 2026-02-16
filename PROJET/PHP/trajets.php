<?php
// Inclure la connexion à la BDD
include('connexion.php'); // Ajuste le chemin selon ton arborescence

// Fonction pour récupérer tous les trajets actifs
function getTrajetsActifs($bdd) {
    try {
        $stmt = $bdd->prepare("SELECT * FROM trajets WHERE statut = 'actif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // retourne un tableau associatif
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
