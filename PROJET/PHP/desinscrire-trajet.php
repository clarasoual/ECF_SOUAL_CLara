<?php
require_once 'auth.php';
requireLogin();
require_once 'connexion.php';
require_once 'transactions.php';
require_once 'logs.php';

$idUser   = $_SESSION['user_id'];
$idTrajet = (int)($_POST['id_trajet'] ?? 0);

if ($idTrajet) {
    $stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ?");
    $stmt->execute([$idTrajet]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($trajet) {
        $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
        $stmt->execute([$idUser]);
        $credit = $stmt->fetch(PDO::FETCH_ASSOC);
        $solde_actuel = $credit ? $credit['solde'] : 0;
        $nouveau_solde = $solde_actuel + $trajet['prix'];

        $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nouveau_solde, $idUser]);

        ajouterTransaction(
            $idUser,
            'entree',
            'Remboursement annulation trajet ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']),
            $trajet['prix'],
            $nouveau_solde,
            $idTrajet
        );

        $stmt = $bdd->prepare("UPDATE trajets SET places_disponibles = places_disponibles + 1, statut = 'publie' WHERE id = ? AND statut = 'complet'");
        $stmt->execute([$idTrajet]);

        $stmt = $bdd->prepare("UPDATE trajets SET places_disponibles = places_disponibles + 1 WHERE id = ? AND statut = 'publie'");
        $stmt->execute([$idTrajet]);

        logAction(
            'desinscription',
            "Désinscription du trajet #$idTrajet ({$trajet['depart']} → {$trajet['arrivee']}) — {$trajet['prix']} crédits remboursés",
            'INFO',
            $idUser
        );
    }

    $stmt = $bdd->prepare("DELETE FROM trajets_passagers WHERE id_passager = ? AND id_trajet = ?");
    $stmt->execute([$idUser, $idTrajet]);
}

header("Location: ../UTILISATEUR/USR-details-trajet.php?id=$idTrajet&success=1");
exit;
