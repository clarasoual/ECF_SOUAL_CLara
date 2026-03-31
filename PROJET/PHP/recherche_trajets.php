<?php
require_once('connexion.php');
require_once(__DIR__ . '/logs.php'); // ✅ Logs JSON (remplace mongodb.php)

// ============================================================
// chercherTrajets() — Recherche les trajets exacts en SQL
// ============================================================
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
            ':depart'  => '%' . trim($depart) . '%',
            ':arrivee' => '%' . trim($arrivee) . '%',
            ':date'    => $date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Log l'erreur SQL
        logAction('erreur_sql', 'Erreur dans chercherTrajets : ' . $e->getMessage(), 'ERROR');
        die("Erreur SQL : " . htmlspecialchars($e->getMessage()));
    }
}

// ============================================================
// chercherTrajetsAlternatifs() — Trajets depuis villes proches
// ============================================================
// Note : MongoDB retiré — les villes proches sont maintenant
// définies en dur ici. Tu pourras les mettre en BDD plus tard.
// ============================================================
function chercherTrajetsAlternatifs($bdd, $depart, $arrivee, $date, $trajetsDejaTrouves) {

    // IDs déjà trouvés pour éviter les doublons
    $idsDejatrouves = array_column($trajetsDejaTrouves, 'id');

    // Villes proches de Bordeaux (remplace MongoDB)
    $nomsVilles = [
        'Mérignac', 'Pessac', 'Talence', 'Mérignac', 'Bègles',
        'Villenave-d\'Ornon', 'Gradignan', 'Lormont', 'Cenon',
        'Floirac', 'Artigues', 'Le Bouscat', 'Bruges', 'Eysines',
        'Blanquefort', 'Parempuyre', 'Ambès', 'Libourne', 'Arcachon'
    ];

    if (empty($nomsVilles)) return [];

    // Construit la requête SQL avec les villes proches
    $placeholders = implode(',', array_fill(0, count($nomsVilles), '?'));

    try {
        $stmt = $bdd->prepare("
            SELECT 
                t.*, 
                u.nom AS nom_conducteur, 
                u.prenom AS prenom_conducteur
            FROM trajets t
            JOIN utilisateurs u ON t.id_conducteur = u.id
            WHERE (
                t.depart IN ($placeholders)
                OR t.arrivee IN ($placeholders)
            )
            AND t.date_depart = ?
            AND t.statut = 'publie'
        ");

        $params = array_merge($nomsVilles, $nomsVilles, [$date]);
        $stmt->execute($params);
        $trajetsAlternatifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Filtre les doublons avec les résultats principaux
        return array_values(array_filter(
            $trajetsAlternatifs,
            fn($trajet) => !in_array($trajet['id'], $idsDejatrouves)
        ));

    } catch (PDOException $e) {
        logAction('erreur_sql', 'Erreur dans chercherTrajetsAlternatifs : ' . $e->getMessage(), 'ERROR');
        return [];
    }
}