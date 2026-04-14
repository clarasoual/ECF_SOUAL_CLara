<?php
include('connexion.php');

if (!isset($_GET['id'])) {
    die("Aucun trajet sélectionné.");
}

$id_trajet = (int) $_GET['id'];

/* =========================
   TRAJET + CONDUCTEUR
   ========================= */
$stmt = $bdd->prepare("
    SELECT 
        t.*, 
        u.id AS id_conducteur,
        u.nom AS nom_conducteur, 
        u.prenom AS prenom_conducteur,
        u.photo AS photo_conducteur,
        v.marque, 
        v.modele, 
        v.couleur, 
        v.carburant
    FROM trajets t
    JOIN utilisateurs u ON t.id_conducteur = u.id
    LEFT JOIN vehicules v ON t.vehicule_id = v.vehicule_id
    WHERE t.id = :id
");
$stmt->execute([':id' => $id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    die("Trajet introuvable.");
}

/* =========================
   PASSAGERS RÉSERVÉS
   ========================= */
$stmt = $bdd->prepare("
    SELECT 
        u.id,
        u.prenom,
        u.nom,
        u.photo
    FROM trajets_passagers tp
    JOIN utilisateurs u ON tp.id_passager = u.id
    WHERE tp.id_trajet = :id
    AND tp.statut IN ('reserve', 'termine', 'valide', 'litige', 'avis_laisse')
");
$stmt->execute([':id' => $id_trajet]);
$passagers = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   AVIS SUR LE CONDUCTEUR
   ========================= */
$stmt = $bdd->prepare("
    SELECT 
        a.note, 
        a.commentaire, 
        u.prenom, 
        u.nom
    FROM avis a
    JOIN utilisateurs u ON a.id_auteur = u.id
    WHERE a.id_destinataire = :id_conducteur
    AND a.statut = 'valide'
    ORDER BY a.date_creation DESC
");
$stmt->execute([
    ':id_conducteur' => $trajet['id_conducteur']
]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   VÉRIFICATION PROPRIÉTAIRE
   ========================= */
$isOwner = ($_SESSION['user_id'] === $trajet['id_conducteur']);

/* =========================
   VÉRIFICATION PASSAGER INSCRIT
   ========================= */
$isPassenger = false;
if (isset($_SESSION['user_id'])) {
    foreach ($passagers as $p) {
        if ($_SESSION['user_id'] == $p['id']) {
            $isPassenger = true;
            break;
        }
    }
}