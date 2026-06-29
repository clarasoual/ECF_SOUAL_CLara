<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('auth.php');
requireEmploye();
require_once('connexion.php');
require_once('transactions.php');
require_once('logs.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$id_signalement  = (int)($_POST['id_signalement'] ?? 0);
$action          = trim($_POST['action'] ?? '');
$note_employe    = trim($_POST['note_employe'] ?? '');
$employe_id      = $_SESSION['employe_id'] ?? null;
$employe_prenom  = $_SESSION['employe_prenom'] ?? 'Employé';

if (!$id_signalement || !in_array($action, ['debloquer', 'bloquer', 'bloquer_suspendre'])) {
    echo json_encode(['success' => false, 'message' => 'Données invalides.']);
    exit;
}

$stmt = $bdd->prepare("SELECT s.*, t.depart, t.arrivee, t.prix, t.id_conducteur FROM signalements s JOIN trajets t ON t.id = s.id_trajet WHERE s.id = ?");
$stmt->execute([$id_signalement]);
$signalement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$signalement) {
    echo json_encode(['success' => false, 'message' => 'Signalement introuvable.']);
    exit;
}

$date_resolution = date('d/m/Y à H:i');

try {
    if ($action === 'debloquer') {
        $prix               = $signalement['prix'];
        $credits_conducteur = $prix - 2;

        if ($credits_conducteur > 0) {
            $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
            $stmt->execute([$signalement['id_conducteur']]);
            $credit        = $stmt->fetch(PDO::FETCH_ASSOC);
            $solde_actuel  = $credit ? $credit['solde'] : 0;
            $nouveau_solde = $solde_actuel + $credits_conducteur;

            $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nouveau_solde, $signalement['id_conducteur']]);

            ajouterTransaction(
                $signalement['id_conducteur'],
                'entree',
                'Crédits débloqués suite à résolution de litige — trajet ' . $signalement['depart'] . ' → ' . $signalement['arrivee'],
                $credits_conducteur,
                $nouveau_solde,
                $signalement['id_trajet']
            );
        }

        $stmt = $bdd->prepare("UPDATE signalements SET statut = 'resolu_credits_verses', note_employe = ? WHERE id = ?");
        $stmt->execute([$note_employe, $id_signalement]);

        $statut_affiche = "✅ Crédits versés par $employe_prenom le $date_resolution";
        logAction('litige_debloquer', "Litige #$id_signalement — crédits versés au conducteur #{$signalement['id_conducteur']}", 'WARNING', $employe_id);

    } elseif ($action === 'bloquer') {
        $stmt = $bdd->prepare("UPDATE signalements SET statut = 'resolu_credits_bloques', note_employe = ? WHERE id = ?");
        $stmt->execute([$note_employe, $id_signalement]);

        $statut_affiche = "❌ Crédits bloqués par $employe_prenom le $date_resolution";
        logAction('litige_bloquer', "Litige #$id_signalement — crédits bloqués", 'WARNING', $employe_id);

    } elseif ($action === 'bloquer_suspendre') {
        $stmt = $bdd->prepare("UPDATE signalements SET statut = 'resolu_credits_bloques', note_employe = ? WHERE id = ?");
        $stmt->execute([$note_employe, $id_signalement]);

        $stmt = $bdd->prepare("UPDATE utilisateurs SET suspendu = 1 WHERE id = ?");
        $stmt->execute([$signalement['id_conducteur']]);

        $statut_affiche = "🚫 Crédits bloqués + conducteur suspendu par $employe_prenom le $date_resolution";
        logAction('litige_bloquer_suspendre', "Litige #$id_signalement — crédits bloqués et conducteur #{$signalement['id_conducteur']} suspendu", 'WARNING', $employe_id);
    }

    echo json_encode(['success' => true, 'action' => $action, 'statut_affiche' => $statut_affiche]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur BDD : ' . $e->getMessage()]);
}
exit;
