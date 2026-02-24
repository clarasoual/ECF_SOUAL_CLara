<?php
require_once('connexion.php'); // utilise $pdo pour la cohérence avec tes autres pages

// Fonction pour chercher les trajets
function chercherTrajets($pdo, $depart, $arrivee, $date) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                t.*, 
                u.nom AS nom_conducteur, 
                u.prenom AS prenom_conducteur
            FROM trajets t
            JOIN utilisateurs u ON t.id_conducteur = u.id
            WHERE LOWER(t.depart) LIKE LOWER(:depart)
              AND LOWER(t.arrivee) LIKE LOWER(:arrivee)
              AND t.date_depart = :date
              AND t.statut IN ('futur', 'en_cours')
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