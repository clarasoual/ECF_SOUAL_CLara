<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('connexion.php');
include('transactions.php');
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/logs.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet      = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];

$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND id_conducteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Action non autorisée.");
}

$stmt = $bdd->prepare("UPDATE trajets SET statut = 'termine' WHERE id = ?");
$stmt->execute([$id_trajet]);

$stmt = $bdd->prepare("
    SELECT tp.id_passager, u.email, u.prenom, u.nom
    FROM trajets_passagers tp
    JOIN utilisateurs u ON u.id = tp.id_passager
    WHERE tp.id_trajet = ? AND tp.statut = 'reserve'
");
$stmt->execute([$id_trajet]);
$passagers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($passagers as $p) {
    envoyerMailFinTrajet($p, $trajet);
}

$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'termine' WHERE id_trajet = ? AND statut = 'reserve'");
$stmt->execute([$id_trajet]);

logAction(
    'trajet_termine',
    "Trajet #$id_trajet terminé ({$trajet['depart']} → {$trajet['arrivee']}) — " . count($passagers) . " passager(s)",
    'INFO',
    $id_utilisateur
);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?finished=1');
exit;
