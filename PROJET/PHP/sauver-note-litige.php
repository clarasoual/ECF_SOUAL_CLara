<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$id_signalement = isset($_POST['id_signalement']) ? (int)$_POST['id_signalement'] : 0;
$note           = trim($_POST['note_employe'] ?? '');

if (!$id_signalement) {
    echo json_encode(['success' => false, 'message' => 'ID invalide.']);
    exit;
}

$stmt = $bdd->prepare("UPDATE signalements SET note_employe = ? WHERE id = ?");
$stmt->execute([$note, $id_signalement]);

echo json_encode(['success' => true]);
exit;