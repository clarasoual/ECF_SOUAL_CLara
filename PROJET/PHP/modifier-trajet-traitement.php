<?php
require_once __DIR__ . '/../PHP/auth.php';
requireLogin(); 
require_once __DIR__ . '/../PHP/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_trajet    = $_POST['id_trajet']    ?? null;
    $depart       = $_POST['departure']    ?? '';
    $arrivee      = $_POST['arrival']      ?? '';
    $date_depart  = $_POST['date']         ?? '';
    $heure_depart = $_POST['time']         ?? '';
    $heure_arrivee = $_POST['time_arrivee'] ?? null;
    $vehicule_id  = $_POST['vehicle_used'] ?? null;
    $places       = $_POST['places']       ?? 0;
    $commentaire  = $_POST['commentaire']  ?? '';

    $etapes = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'step') === 0 && !empty(trim($value))) {
            $etapes[] = trim($value);
        }
    }
    $etapes_json = !empty($etapes) ? json_encode($etapes, JSON_UNESCAPED_UNICODE) : null;

    $stmtCheck = $bdd->prepare("SELECT id_conducteur FROM trajets WHERE id = :id");
    $stmtCheck->execute([':id' => $id_trajet]);
    $trajet = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$trajet || $trajet['id_conducteur'] != $_SESSION['user_id']) {
        die("Vous n'avez pas la permission de modifier ce trajet.");
    }

    $stmtUpdate = $bdd->prepare("
        UPDATE trajets SET
            depart             = :depart,
            arrivee            = :arrivee,
            etapes             = :etapes,
            date_depart        = :date_depart,
            heure_depart       = :heure_depart,
            heure_arrivee      = :heure_arrivee,
            vehicule_id        = :vehicule_id,
            places_disponibles = :places,
            commentaire        = :commentaire
        WHERE id = :id
    ");

    $stmtUpdate->execute([
        ':depart'        => $depart,
        ':arrivee'       => $arrivee,
        ':etapes'        => $etapes_json,
        ':date_depart'   => $date_depart,
        ':heure_depart'  => $heure_depart,
        ':heure_arrivee' => $heure_arrivee ?: null,
        ':vehicule_id'   => $vehicule_id,
        ':places'        => $places,
        ':commentaire'   => $commentaire,
        ':id'            => $id_trajet
    ]);

    header("Location: ../UTILISATEUR/USR-details-trajet.php?id=$id_trajet&success=1");
    exit;
}
?>