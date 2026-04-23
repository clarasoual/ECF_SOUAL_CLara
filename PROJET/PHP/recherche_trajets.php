<?php
require_once('connexion.php');
require_once(__DIR__ . '/logs.php');

function chercherTrajets($bdd, $depart, $arrivee, $date) {
    try {
        $stmt = $bdd->prepare("
            SELECT 
                t.*, 
                u.nom AS nom_conducteur, 
                u.prenom AS prenom_conducteur,
                u.photo AS photo_conducteur,
                v.carburant,
                COALESCE(AVG(a.note), 0) AS note_moyenne,
                COUNT(a.id) AS nb_avis
            FROM trajets t
            JOIN utilisateurs u ON t.id_conducteur = u.id
            LEFT JOIN vehicules v ON t.vehicule_id = v.vehicule_id
            LEFT JOIN avis a ON a.id_destinataire = t.id_conducteur AND a.statut = 'valide'
            WHERE (
                LOWER(TRIM(t.depart)) LIKE LOWER(:depart)
                OR LOWER(t.etapes) LIKE LOWER(:depart_etape)
            )
            AND (
                LOWER(TRIM(t.arrivee)) LIKE LOWER(:arrivee)
                OR LOWER(t.etapes) LIKE LOWER(:arrivee_etape)
            )
            AND t.date_depart = :date
            AND t.statut IN ('publie', 'complet')
            GROUP BY t.id
        ");

        $stmt->execute([
            ':depart'        => '%' . trim($depart) . '%',
            ':depart_etape'  => '%' . trim($depart) . '%',
            ':arrivee'       => '%' . trim($arrivee) . '%',
            ':arrivee_etape' => '%' . trim($arrivee) . '%',
            ':date'          => $date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        logAction('erreur_sql', 'Erreur dans chercherTrajets : ' . $e->getMessage(), 'ERROR');
        die("Erreur SQL : " . htmlspecialchars($e->getMessage()));
    }
}

function prochainTrajetDisponible($bdd, $depart, $arrivee, $date) {
    try {
        $stmt = $bdd->prepare("
            SELECT t.date_depart
            FROM trajets t
            WHERE (
                LOWER(TRIM(t.depart)) LIKE LOWER(:depart)
                OR LOWER(t.etapes) LIKE LOWER(:depart_etape)
            )
            AND (
                LOWER(TRIM(t.arrivee)) LIKE LOWER(:arrivee)
                OR LOWER(t.etapes) LIKE LOWER(:arrivee_etape)
            )
            AND t.date_depart > :date
            AND t.statut IN ('publie', 'complet')
            ORDER BY t.date_depart ASC
            LIMIT 1
        ");

        $stmt->execute([
            ':depart'        => '%' . trim($depart) . '%',
            ':depart_etape'  => '%' . trim($depart) . '%',
            ':arrivee'       => '%' . trim($arrivee) . '%',
            ':arrivee_etape' => '%' . trim($arrivee) . '%',
            ':date'          => $date
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return null;
    }
}