<?php
session_start();
include('connexion.php');
include('transactions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion-inscription.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_trajet'])) {
    die("Requête invalide.");
}

$id_trajet = (int)$_POST['id_trajet'];
$id_utilisateur = $_SESSION['user_id'];

// Vérifier que c'est bien le conducteur
$stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ? AND id_conducteur = ?");
$stmt->execute([$id_trajet, $id_utilisateur]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trajet) {
    die("Action non autorisée.");
}

// Passer le statut à termine
$stmt = $bdd->prepare("UPDATE trajets SET statut = 'termine' WHERE id = ?");
$stmt->execute([$id_trajet]);

// Récupérer les passagers reservés
$stmt = $bdd->prepare("SELECT id_passager FROM trajets_passagers WHERE id_trajet = ? AND statut = 'reserve'");
$stmt->execute([$id_trajet]);
$passagers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Créditer le conducteur pour chaque passager
$prix = $trajet['prix'];
$credits_conducteur = $prix - 2;

if ($credits_conducteur > 0 && !empty($passagers)) {
    $nb_passagers = count($passagers);

    // Récupérer le solde actuel du conducteur
    $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
    $stmt->execute([$trajet['id_conducteur']]);
    $credit = $stmt->fetch(PDO::FETCH_ASSOC);
    $solde_actuel = $credit ? $credit['solde'] : 0;
    $gains_total = $credits_conducteur * $nb_passagers;
    $nouveau_solde = $solde_actuel + $gains_total;

    // Mettre à jour le solde
    $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
    $stmt->execute([$nouveau_solde, $trajet['id_conducteur']]);

    // Enregistrer la transaction
    ajouterTransaction(
        $trajet['id_conducteur'],
        'entree',
        'Trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']) . ' terminé (' . $nb_passagers . ' passager(s))',
        $gains_total,
        $nouveau_solde,
        $id_trajet
    );
}

// Passer tous les passagers à termine
$stmt = $bdd->prepare("UPDATE trajets_passagers SET statut = 'termine' WHERE id_trajet = ? AND statut = 'reserve'");
$stmt->execute([$id_trajet]);

header('Location: ../UTILISATEUR/USR-mes-trajets.php?finished=1');
exit;