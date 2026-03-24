<?php
require_once('connexion.php'); // utilise $bdd pour cohérence

function chercherTrajets($bdd, $depart, $arrivee, $date) {
    try {
        $stmt = $bdd->prepare("
            SELECT 
                t.*, 
                u.nom AS nom_conducteur, 
                u.prenom AS prenom_conducteur
            FROM trajets t
            JOIN utilisateurs u ON t.id_conducteur = u.id
            WHERE LOWER(TRIM(t.depart)) LIKE LOWER(:depart)
              AND LOWER(TRIM(t.arrivee)) LIKE LOWER(:arrivee)
              AND t.date_depart = :date
              AND t.statut = 'publie'
        ");

        $stmt->execute([
            ':depart' => '%' . trim($depart) . '%',
            ':arrivee' => '%' . trim($arrivee) . '%',
            ':date' => $date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erreur SQL : " . htmlspecialchars($e->getMessage()));
    }
}