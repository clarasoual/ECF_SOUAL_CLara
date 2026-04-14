<?php
session_start();
include('connexion.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];
$note = (float)$_POST['note'];
$commentaire = trim($_POST['commentaire'] ?? '');

// Récupérer le trajet
$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND statut = 'termine'");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Trajet invalide.");
}

// Vérifier que l'utilisateur est bien passager
$stmt = $bdd->prepare("SELECT * FROM trajets_passagers WHERE id_trajet = ? AND id_passager = ? AND statut = 'termine'");
$stmt->execute([$id_trajet, $id_utilisateur]);
$inscription = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$inscription) {
    die("Action non autorisée.");
}

// Vérifier que l'avis n'a pas déjà été soumis
$stmt = $bdd->prepare("SELECT id FROM avis WHERE id_trajet = ? AND id_auteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
if ($stmt->fetch()) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=deja_soumis');
    exit;
}

// Insérer l'avis
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

// Mettre à jour le statut du passager
$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'avis_laisse' WHERE id_trajet = ? AND id_passager = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=ok');
exit;
?>