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
   Si le trajet est terminé → avis de CE trajet uniquement
   Sinon → tous les avis validés du conducteur
   ========================= */
$trajetTermine = ($trajet['statut'] === 'termine');

if ($trajetTermine) {
    // Avis liés spécifiquement à ce trajet
    $stmt = $bdd->prepare("
        SELECT 
            a.note, 
            a.commentaire, 
            u.prenom, 
            u.nom
        FROM avis a
        JOIN utilisateurs u ON a.id_auteur = u.id
        WHERE a.id_trajet = :id_trajet
        AND a.id_destinataire = :id_conducteur
        AND a.statut = 'valide'
        ORDER BY a.date_creation DESC
    ");
    $stmt->execute([
        ':id_trajet'     => $id_trajet,
        ':id_conducteur' => $trajet['id_conducteur'],
    ]);
} else {
    // Tous les avis validés du conducteur (pour se faire une idée avant de réserver)
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
        ':id_conducteur' => $trajet['id_conducteur'],
    ]);
}

$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   NOTE MOYENNE
   Toujours calculée sur l'ensemble des avis du conducteur
   (indépendamment du filtre trajet)
   ========================= */
$stmtNote = $bdd->prepare("
    SELECT COUNT(*) AS nb, ROUND(AVG(note), 1) AS moyenne
    FROM avis
    WHERE id_destinataire = :id_conducteur
    AND statut = 'valide'
");
$stmtNote->execute([':id_conducteur' => $trajet['id_conducteur']]);
$statsNote = $stmtNote->fetch(PDO::FETCH_ASSOC);

$nb_avis      = (int)($statsNote['nb'] ?? 0);
$note_moyenne = $statsNote['moyenne'] ?? 0;

/* =========================
   VÉRIFICATION PROPRIÉTAIRE
   ========================= */
$isOwner = (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $trajet['id_conducteur']);

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
