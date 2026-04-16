<?php
session_start();
require_once('auth.php');
requireEmploye();
require_once('connexion.php');

// On répond toujours en JSON
header('Content-Type: application/json');

// Vérifier que c'est bien une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$avis_id = isset($_POST['avis_id']) ? (int)$_POST['avis_id'] : 0;
$action  = trim($_POST['action'] ?? '');

if (!$avis_id || !$action) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit;
}

$actionsAutorisees = ['valider', 'refuser', 'remettre_en_attente', 'supprimer'];
if (!in_array($action, $actionsAutorisees)) {
    echo json_encode(['success' => false, 'message' => 'Action non autorisée.']);
    exit;
}

try {
    if ($action === 'valider') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'valide' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'refuser') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'refuse' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'remettre_en_attente') {
        $stmt = $bdd->prepare("UPDATE avis SET statut = 'en_attente' WHERE id = ?");
        $stmt->execute([$avis_id]);
    } elseif ($action === 'supprimer') {
        $stmt = $bdd->prepare("DELETE FROM avis WHERE id = ?");
        $stmt->execute([$avis_id]);
    }

    echo json_encode(['success' => true, 'action' => $action, 'avis_id' => $avis_id]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur BDD : ' . $e->getMessage()]);
}
exit;