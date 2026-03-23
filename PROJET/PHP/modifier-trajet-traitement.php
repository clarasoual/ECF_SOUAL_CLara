<?php
require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); 
require_once __DIR__ . '/../PHP/connexion.php'; // $bdd PDO

// Vérifier que le formulaire est bien soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer les valeurs du formulaire
    $id_trajet = $_POST['id_trajet'] ?? null;
    $depart = $_POST['departure'] ?? '';
    $arrivee = $_POST['arrival'] ?? '';
    $date_depart = $_POST['date'] ?? '';
    $heure_depart = $_POST['time'] ?? '';
    $vehicule_id = $_POST['vehicle_used'] ?? null;
    $places = $_POST['places'] ?? 0;
    $commentaire = $_POST['commentaire'] ?? '';

    // Récupérer toutes les étapes dynamiques
    $etapes = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'step') === 0 && !empty(trim($value))) {
            $etapes[] = trim($value);
        }
    }
    $etapes_json = !empty($etapes) ? json_encode($etapes, JSON_UNESCAPED_UNICODE) : null;

    // Vérifier que le trajet appartient bien à l'utilisateur
    $stmtCheck = $bdd->prepare("SELECT id_conducteur FROM trajets WHERE id = :id");
    $stmtCheck->execute([':id' => $id_trajet]);
    $trajet = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['id_conducteur'] != $_SESSION['user_id']) {
        die("Vous n'avez pas la permission de modifier ce trajet.");
    }

    // Mettre à jour le trajet
    $stmtUpdate = $bdd->prepare("
        UPDATE trajets SET
            depart = :depart,
            arrivee = :arrivee,
            etapes = :etapes,
            date_depart = :date_depart,
            heure_depart = :heure_depart,
            vehicule_id = :vehicule_id,
            places_disponibles = :places,
            commentaire = :commentaire
        WHERE id = :id
    ");

    $stmtUpdate->execute([
        ':depart' => $depart,
        ':arrivee' => $arrivee,
        ':etapes' => $etapes_json,
        ':date_depart' => $date_depart,
        ':heure_depart' => $heure_depart,
        ':vehicule_id' => $vehicule_id,
        ':places' => $places,
        ':commentaire' => $commentaire,
        ':id' => $id_trajet
    ]);

    // Rediriger vers la page détail du trajet
header("Location: ../UTILISATEUR/USR-details-trajet.php?id=$id_trajet&success=1");
exit;
}
?>