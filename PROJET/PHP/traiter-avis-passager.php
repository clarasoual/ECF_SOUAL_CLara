<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../PHP/auth.php');
requireLogin();
require_once('../PHP/connexion.php');

$id_conducteur   = $_SESSION['user_id'] ?? null;
$id_trajet       = isset($_POST['id_trajet'])       ? (int)$_POST['id_trajet']       : 0;
$id_destinataire = isset($_POST['id_destinataire']) ? (int)$_POST['id_destinataire'] : 0;
$note            = isset($_POST['note'])            ? (int)$_POST['note']            : 0;
$commentaire     = trim($_POST['commentaire']       ?? '');
$motif_signal    = trim($_POST['motif_signalement'] ?? '');

if (!$id_conducteur || !$id_trajet || !$id_destinataire || $note < 1 || $note > 5) {
    header('Location: ../UTILISATEUR/USR-avis-passager.php?id_trajet=' . $id_trajet . '&erreur=' . urlencode('Données invalides.'));
    exit;
}

$stmtCheck = $bdd->prepare("
    SELECT id FROM trajets
    WHERE id = ? AND id_conducteur = ? AND statut = 'termine'
");
$stmtCheck->execute([$id_trajet, $id_conducteur]);
if (!$stmtCheck->fetch()) {
    header('Location: ../UTILISATEUR/USR-mes-trajets.php');
    exit;
}

$stmtPass = $bdd->prepare("
    SELECT id FROM trajets_passagers
    WHERE id_trajet = ? AND id_passager = ? AND statut NOT IN ('annule')
");
$stmtPass->execute([$id_trajet, $id_destinataire]);
if (!$stmtPass->fetch()) {
    header('Location: ../UTILISATEUR/USR-avis-passager.php?id_trajet=' . $id_trajet . '&erreur=' . urlencode('Passager introuvable.'));
    exit;
}

$stmtDeja = $bdd->prepare("
    SELECT id FROM avis
    WHERE id_trajet = ? AND id_auteur = ? AND id_destinataire = ?
");
$stmtDeja->execute([$id_trajet, $id_conducteur, $id_destinataire]);
if ($stmtDeja->fetch()) {
    header('Location: ../UTILISATEUR/USR-avis-passager.php?id_trajet=' . $id_trajet . '&erreur=' . urlencode('Vous avez déjà noté ce passager.'));
    exit;
}

try {
    $bdd->beginTransaction();

    $stmtAvis = $bdd->prepare("
        INSERT INTO avis (id_trajet, id_auteur, id_destinataire, note, commentaire, date_creation, statut)
        VALUES (?, ?, ?, ?, ?, NOW(), 'en_attente')
    ");
    $stmtAvis->execute([
        $id_trajet,
        $id_conducteur,
        $id_destinataire,
        $note,
        $commentaire !== '' ? $commentaire : null,
    ]);

    $signalement_soumis = false;
    if ($motif_signal !== '') {
        // Vérifier qu'aucun signalement conducteur→passager n'existe déjà pour cette paire
        $stmtDejaSignal = $bdd->prepare("
            SELECT id FROM signalements
            WHERE id_trajet = ? AND id_utilisateur = ? AND type = 'conducteur_vers_passager'
        ");
        $stmtDejaSignal->execute([$id_trajet, $id_destinataire]);

        if (!$stmtDejaSignal->fetch()) {
            $stmtSignal = $bdd->prepare("
                INSERT INTO signalements (id_trajet, id_utilisateur, motif, date_creation, statut, type)
                VALUES (?, ?, ?, NOW(), 'en_cours', 'conducteur_vers_passager')
            ");
            $stmtSignal->execute([$id_trajet, $id_destinataire, $motif_signal]);
            $signalement_soumis = true;
        }
    }

    $bdd->commit();

    $param_succes = $signalement_soumis ? 'avis_et_signalement' : 'avis';
    header('Location: ../UTILISATEUR/USR-avis-passager.php?id_trajet=' . $id_trajet . '&succes=' . $param_succes);
    exit;

} catch (PDOException $e) {
    $bdd->rollBack();
    $msg = urlencode('Erreur lors de l\'enregistrement. Veuillez réessayer.');
    header('Location: ../UTILISATEUR/USR-avis-passager.php?id_trajet=' . $id_trajet . '&erreur=' . $msg);
    exit;
}