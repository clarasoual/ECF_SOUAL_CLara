<?php
session_start();
include('connexion.php');
include('transactions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

// Récupère les infos de l'utilisateur
$stmt = $bdd->prepare("SELECT prenom, nom, email FROM utilisateurs WHERE id = ?");
$stmt->execute([$id_utilisateur]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Enregistre la demande dans un fichier JSON dédié
$demande = [
    'id'             => 'demande_' . uniqid(),
    'date'           => date('Y-m-d H:i:s'),
    'id_utilisateur' => (int) $id_utilisateur,
    'prenom'         => $user['prenom'],
    'nom'            => $user['nom'],
    'email'          => $user['email'],
    'statut'         => 'en_attente'
];

$fichier = __DIR__ . '/../../demandes_credits.json';
$fp = fopen($fichier, 'c+');
if (flock($fp, LOCK_EX)) {
    $contenu = stream_get_contents($fp);
    $demandes = json_decode($contenu, true) ?? [];
    $demandes[] = $demande;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($demandes, JSON_PRETTY_PRINT));
    flock($fp, LOCK_UN);
}
fclose($fp);

// Redirige vers la page crédits avec un message
header('Location: ../UTILISATEUR/USR-gestion-credits.php?demande=ok');
exit;
?>
