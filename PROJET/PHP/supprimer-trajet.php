<?php
require_once __DIR__ . '/auth.php';
requireLogin();
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/transactions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trajet = (int)($_POST['id_trajet'] ?? 0);

    if (!$id_trajet) {
        die("Aucun trajet spécifié.");
    }

    // Vérifier que le trajet appartient bien au conducteur
    $stmt = $bdd->prepare("SELECT * FROM trajets WHERE id = ?");
    $stmt->execute([$id_trajet]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['id_conducteur'] != $_SESSION['user_id']) {
        die("Vous n'avez pas la permission de supprimer ce trajet.");
    }

    // Récupérer les passagers inscrits
    $stmt = $bdd->prepare("SELECT id_passager FROM trajets_passagers WHERE id_trajet = ? AND statut = 'reserve'");
    $stmt->execute([$id_trajet]);
    $passagers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Rembourser chaque passager
    foreach ($passagers as $p) {
        $id_passager = $p['id_passager'];

        // Récupérer le solde actuel
        $stmt = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
        $stmt->execute([$id_passager]);
        $credit = $stmt->fetch(PDO::FETCH_ASSOC);
        $solde_actuel = $credit ? $credit['solde'] : 0;
        $nouveau_solde = $solde_actuel + $trajet['prix'];

        // Rembourser
        $stmt = $bdd->prepare("UPDATE credits SET solde = ? WHERE id_utilisateur = ?");
        $stmt->execute([$nouveau_solde, $id_passager]);

        // Enregistrer la transaction
        ajouterTransaction(
            $id_passager,
            'entree',
            'Remboursement - trajet annulé par le conducteur : ' . htmlspecialchars($trajet['depart']) . ' → ' . htmlspecialchars($trajet['arrivee']),
            $trajet['prix'],
            $nouveau_solde,
            $id_trajet
        );
    }

    // Supprimer les passagers
    $stmt = $bdd->prepare("DELETE FROM trajets_passagers WHERE id_trajet = ?");
    $stmt->execute([$id_trajet]);

    // Supprimer le trajet
    $stmt = $bdd->prepare("DELETE FROM trajets WHERE id = ?");
    $stmt->execute([$id_trajet]);

    header("Location: ../UTILISATEUR/USR-mes-trajets.php?deleted=1");
    exit;
}
?>