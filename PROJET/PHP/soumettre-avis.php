<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('connexion.php');
include('transactions.php');
require_once('logs.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet      = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];
$note           = (float)$_POST['note'];
$commentaire    = trim($_POST['commentaire'] ?? '');

$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND statut = 'termine'");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Trajet invalide.");
}

$stmt = $bdd->prepare("SELECT * FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ? AND statut = 'termine'");
$stmt->execute([$id_trajet, $id_utilisateur]);
$inscription = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$inscription) {
    die("Action non autorisée.");
}

$stmt = $bdd->prepare("SELECT id FROM avis WHERE id_trajet = ? AND id_auteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
if ($stmt->fetch()) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=deja_soumis');
    exit;
}

$stmt = $bdd->prepare("
    INSERT INTO avis (id_trajet, id_auteur, id_destinataire, note, commentaire, statut)
    VALUES (?, ?, ?, ?, ?, 'en_attente')
");
$stmt->execute([
    $id_trajet,
    $id_utilisateur,
    $trajet['id_conducteur'],
    $note,
    $commentaire
]);

$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'avis_laisse' WHERE id_trajet = ? AND id_passager = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);

logAction(
    'avis_soumis',
    "Avis soumis pour le trajet #$id_trajet — note : $note/5",
    'INFO',
    $id_utilisateur
);

$prix               = $trajet['prix'];
$credits_conducteur = $prix - 2;

if ($credits_conducteur > 0) {
    $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
    $stmt->execute([$trajet['id_conducteur']]);
    $credit        = $stmt->fetch(PDO::FETCH_ASSOC);
    $solde_actuel  = $credit ? $credit['solde'] : 0;
    $nouveau_solde = $solde_actuel + $credits_conducteur;

    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nouveau_solde, $trajet['id_conducteur']]);

    ajouterTransaction(
        $trajet['id_conducteur'],
        'entree',
        'Trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']) . ' validé par un passager',
        $credits_conducteur,
        $nouveau_solde,
        $id_trajet
    );

    logAction(
        'credits_verses_conducteur',
        "Versement de $credits_conducteur crédits au conducteur #{$trajet['id_conducteur']} pour le trajet #$id_trajet",
        'INFO',
        $trajet['id_conducteur']
    );
}

header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=ok');
exit;
?>