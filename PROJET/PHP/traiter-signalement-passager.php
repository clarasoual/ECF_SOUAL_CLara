<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

header('Content-Type: application/json');

$id_signalement = isset($_POST['id_signalement']) ? (int)$_POST['id_signalement'] : 0;
$action         = trim($_POST['action'] ?? '');

if (!$id_signalement || !in_array($action, ['traite', 'suspendre_passager'])) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

// Récupérer le signalement et le passager ciblé
$stmtSig = $bdd->prepare("
    SELECT s.*, t.id_conducteur
    FROM signalements s
    JOIN trajets t ON t.id = s.id_trajet
    WHERE s.id = ?
");
$stmtSig->execute([$id_signalement]);
$sig = $stmtSig->fetch(PDO::FETCH_ASSOC);

if (!$sig) {
    echo json_encode(['success' => false, 'message' => 'Signalement introuvable.']);
    exit;
}

try {
    $bdd->beginTransaction();

    if ($action === 'traite') {
        $bdd->prepare("
            UPDATE signalements SET statut = 'traite' WHERE id = ?
        ")->execute([$id_signalement]);

        $statut_affiche = 'Signalement traité';

    } elseif ($action === 'suspendre_passager') {
        $bdd->prepare("
            UPDATE utilisateurs SET statut = 'suspendu' WHERE id = ?
        ")->execute([$sig['id_utilisateur']]);

        $bdd->prepare("
            UPDATE signalements SET statut = 'traite' WHERE id = ?
        ")->execute([$id_signalement]);

        $statut_affiche = 'Passager suspendu';
    }

    $bdd->commit();

    echo json_encode([
        'success'        => true,
        'statut_affiche' => $statut_affiche,
    ]);

} catch (PDOException $e) {
    $bdd->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur base de données : ' . $e->getMessage()]);
}
