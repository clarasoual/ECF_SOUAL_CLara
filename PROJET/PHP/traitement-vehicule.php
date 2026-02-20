<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../PHP/connexion.php');

// Vérifier utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifier méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../UTILISATEUR/USR-infos-conducteur.php');
    exit;
}

// Récupération des données
$plaque = trim($_POST['plate'] ?? '');
$date_immat = !empty($_POST['date']) ? $_POST['date'] : null;
$marque = trim($_POST['marque'] ?? '');
$modele = trim($_POST['modele'] ?? '');
$couleur = trim($_POST['color'] ?? '');
$carburant = trim($_POST['carburant'] ?? '');
$places = intval($_POST['places'] ?? 0);
$animaux = $_POST['pets'] ?? 'non';
$fumeur = $_POST['smoking'] ?? 'non';
$musique = $_POST['music'] ?? 'none';

// Vérifications simples
if (empty($plaque) || empty($marque) || empty($modele) || empty($carburant) || $places <= 0) {
    die("Champs obligatoires manquants.");
}

// Insertion dans la base
$stmt = $bdd->prepare("
    INSERT INTO vehicules
    (id_utilisateur, plaque, date_premiere_immat, marque, modele, couleur, carburant, places, animaux_acceptes, fumeur, musique)
    VALUES
    (:id_utilisateur, :plaque, :date_immat, :marque, :modele, :couleur, :carburant, :places, :animaux, :fumeur, :musique)
");

$stmt->execute([
    ':id_utilisateur' => $user_id,
    ':plaque' => $plaque,
    ':date_immat' => $date_immat,
    ':marque' => $marque,
    ':modele' => $modele,
    ':couleur' => $couleur,
    ':carburant' => $carburant,
    ':places' => $places,
    ':animaux' => $animaux,
    ':fumeur' => $fumeur,
    ':musique' => $musique
]);

// Redirection après enregistrement
header("Location: ../UTILISATEUR/USR-infos-perso.php");
exit;
?>
