<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../PHP/auth.php');
requireLogin();
require_once('../PHP/connexion.php');
require_once('../PHP/logs.php');

$id_passager = $_SESSION['user_id'] ?? null;
$id_trajet   = isset($_POST['id_trajet']) ? (int)$_POST['id_trajet'] : 0;
$note        = isset($_POST['note'])      ? (int)$_POST['note']      : 0;
$commentaire = trim($_POST['commentaire'] ?? '');

// --- Validations de base ---
if (!$id_passager || !$id_trajet || $note < 1 || $note > 5) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=erreur');
    exit;
}

// Vérifier que l'utilisateur est bien passager de ce trajet et qu'il est terminé
$stmtCheck = $bdd->prepare("
    SELECT tp.id, t.id_conducteur
    FROM trajets_passagers tp
    JOIN trajets t ON t.id = tp.id_trajet
    WHERE tp.id_trajet = ? AND tp.id_passager = ?
    AND tp.statut IN ('termine', 'valide')
    AND t.statut = 'termine'
");
$stmtCheck->execute([$id_trajet, $id_passager]);
$row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=erreur');
    exit;
}

$id_conducteur = $row['id_conducteur'];

// Vérifier que l'avis n'a pas déjà été soumis
$stmtDeja = $bdd->prepare("
    SELECT id FROM avis
    WHERE id_trajet = ? AND id_auteur = ? AND id_destinataire = ?
");
$stmtDeja->execute([$id_trajet, $id_passager, $id_conducteur]);
if ($stmtDeja->fetch()) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=deja_soumis');
    exit;
}

try {
    $bdd->beginTransaction();

    // Insérer l'avis
    $stmtAvis = $bdd->prepare("
        INSERT INTO avis (id_trajet, id_auteur, id_destinataire, note, commentaire, date_creation, statut)
        VALUES (?, ?, ?, ?, ?, NOW(), 'en_attente')
    ");
    $stmtAvis->execute([
        $id_trajet,
        $id_passager,
        $id_conducteur,
        $note,
        $commentaire !== '' ? $commentaire : null,
    ]);

    // Mettre à jour le statut du passager
    $bdd->prepare("
        UPDATE trajets_passagers SET statut = 'avis_laisse'
        WHERE id_trajet = ? AND id_passager = ?
    ")->execute([$id_trajet, $id_passager]);

    $bdd->commit();

    logAction(
        'avis_soumis',
        "Avis soumis pour le trajet #$id_trajet — note : $note",
        'INFO',
        $id_passager
    );

    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=ok');
    exit;

} catch (PDOException $e) {
    $bdd->rollBack();
    header('Location: ../UTILISATEUR/USR-mes-trajets.php?avis=erreur');
    exit;
}