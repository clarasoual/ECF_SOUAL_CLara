<?php
require_once __DIR__ . '/auth.php';
requireLogin();
require_once __DIR__ . '/connexion.php'; // $bdd PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trajet = $_POST['id_trajet'] ?? null;

    if (!$id_trajet) {
        die("Aucun trajet spécifié.");
    }

    // Vérifier que le trajet appartient bien à l'utilisateur
    $stmtCheck = $bdd->prepare("SELECT id_conducteur FROM trajets WHERE id = :id");
    $stmtCheck->execute([':id' => $id_trajet]);
    $trajet = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['id_conducteur'] != $_SESSION['user_id']) {
        die("Vous n'avez pas la permission de supprimer ce trajet.");
    }

    // Supprimer le trajet
    $stmtDelete = $bdd->prepare("DELETE FROM trajets WHERE id = :id");
    $stmtDelete->execute([':id' => $id_trajet]);

    // Rediriger vers la page des trajets ou la page d'accueil avec toast
    header("Location: ../UTILISATEUR/USR-mes-trajets.php?deleted=1");
    exit;
}
?>