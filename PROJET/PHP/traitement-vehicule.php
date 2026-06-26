<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../PHP/connexion.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

$plaque     = trim($_POST['plate'] ?? '');
$date_immat = !empty($_POST['date']) ? $_POST['date'] : null;
$marque     = trim($_POST['marque'] ?? '');
$modele     = trim($_POST['modele'] ?? '');
$couleur    = trim($_POST['color'] ?? '');
$carburant  = trim($_POST['carburant'] ?? '');
$places     = intval($_POST['places'] ?? 0);
$animaux    = $_POST['pets'] ?? 'non';
$fumeur     = $_POST['smoking'] ?? 'non';
$musique    = $_POST['music'] ?? 'none';
$action     = $_POST['action'] ?? 'ajouter';

// Sécurité : caractères autorisés uniquement (lettres, chiffres, espaces, tirets)
$marque  = preg_replace('/[^a-zA-ZÀ-ÿ0-9 \-]/u', '', $marque);
$modele  = preg_replace('/[^a-zA-ZÀ-ÿ0-9 \-\.]/u', '', $modele);
$couleur = preg_replace('/[^a-zA-ZÀ-ÿ ]/u', '', $couleur);

// Longueurs max
$marque  = mb_substr($marque, 0, 50);
$modele  = mb_substr($modele, 0, 50);
$couleur = mb_substr($couleur, 0, 30);

// Carburant : valeurs autorisées uniquement
$carburants_autorises = ['Essence', 'Diesel', 'Electrique', 'Hybride'];
if (!in_array($carburant, $carburants_autorises)) {
    $_SESSION['error'] = "Carburant invalide.";
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

// Plaque : format basique
$plaque = strtoupper(preg_replace('/[^a-zA-Z0-9\-]/u', '', $plaque));

if (empty($plaque) || empty($marque) || empty($modele) || $places < 1 || $places > 8) {
    $_SESSION['error'] = "Champs obligatoires manquants ou invalides.";
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

if ($action === 'modifier' && !empty($_POST['vehicule_id'])) {

    $stmt_check = $bdd->prepare("SELECT vehicule_id FROM vehicules WHERE vehicule_id = ? AND id_utilisateur = ?");
    $stmt_check->execute([$_POST['vehicule_id'], $user_id]);
    if (!$stmt_check->fetch()) {
        $_SESSION['error'] = "Véhicule introuvable.";
        header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
        exit;
    }

    $stmt = $bdd->prepare("
        UPDATE vehicules SET
            plaque = :plaque,
            date_premiere_immat = :date_immat,
            marque = :marque,
            modele = :modele,
            couleur = :couleur,
            carburant = :carburant,
            places = :places,
            animaux_acceptes = :animaux,
            fumeur = :fumeur,
            musique = :musique
        WHERE vehicule_id = :vehicule_id AND id_utilisateur = :id_utilisateur
    ");
    $stmt->execute([
        ':plaque'         => $plaque,
        ':date_immat'     => $date_immat,
        ':marque'         => $marque,
        ':modele'         => $modele,
        ':couleur'        => $couleur,
        ':carburant'      => $carburant,
        ':places'         => $places,
        ':animaux'        => $animaux,
        ':fumeur'         => $fumeur,
        ':musique'        => $musique,
        ':vehicule_id'    => $_POST['vehicule_id'],
        ':id_utilisateur' => $user_id,
    ]);

} else {

    $stmt = $bdd->prepare("
        INSERT INTO vehicules
            (id_utilisateur, plaque, date_premiere_immat, marque, modele, couleur, carburant, places, animaux_acceptes, fumeur, musique)
        VALUES
            (:id_utilisateur, :plaque, :date_immat, :marque, :modele, :couleur, :carburant, :places, :animaux, :fumeur, :musique)
    ");
    $stmt->execute([
        ':id_utilisateur' => $user_id,
        ':plaque'         => $plaque,
        ':date_immat'     => $date_immat,
        ':marque'         => $marque,
        ':modele'         => $modele,
        ':couleur'        => $couleur,
        ':carburant'      => $carburant,
        ':places'         => $places,
        ':animaux'        => $animaux,
        ':fumeur'         => $fumeur,
        ':musique'        => $musique,
    ]);
}

$_SESSION['success'] = $action === 'modifier' ? "Véhicule modifié avec succès." : "Véhicule ajouté avec succès.";
header("Location: ../UTILISATEUR/USR-infos-conducteur.php");
exit;
