<?php
// 🔒 Inclure auth et démarrer session
require_once __DIR__ . '/auth.php';
requireLogin(); // Redirige si non connecté

// Connexion BDD
require_once __DIR__ . '/connexion.php'; // $bdd

$userId = $_SESSION['user_id'] ?? 0;

// --- Récupérer les trajets de l'utilisateur ---
try {
    $stmt = $bdd->prepare("
        SELECT * 
        FROM trajets 
        WHERE id_conducteur = ? 
        ORDER BY date_depart DESC, heure_depart DESC
    ");
    $stmt->execute([$userId]);
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur PDO : " . htmlspecialchars($e->getMessage()));
}

// --- Fonction pour récupérer les passagers d'un trajet ---
function getPassagers($bdd, $id_trajet){
    $stmt = $bdd->prepare("
        SELECT u.nom, u.prenom, tp.note_passager 
        FROM trajets_passagers tp
        JOIN utilisateurs u ON tp.id_passager = u.id
        WHERE tp.id_trajet = ? 
    ");
    $stmt->execute([$id_trajet]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>