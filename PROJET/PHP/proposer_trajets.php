<?php
session_start();
include('../PHP/auth.php');
requireLogin();
require('../PHP/connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_conducteur = $_SESSION['user_id'] ?? 0;
    if (!$id_conducteur) {
        die("Utilisateur non identifié.");
    }

    // Récupérer depuis la session
    $trajet_temp = $_SESSION['trajet_temp'] ?? [];

    $depart            = trim($trajet_temp['departure'] ?? '');
    $arrivee           = trim($trajet_temp['arrival'] ?? '');
    $date_depart       = $trajet_temp['date'] ?? '';
    $heure_depart      = $trajet_temp['time'] ?? '';
    $vehicule_id       = $trajet_temp['vehicle_used'] ?? null;
    $places_disponibles = intval($trajet_temp['places'] ?? 1);
    $commentaire       = trim($trajet_temp['commentaire'] ?? '');
    $prix              = intval($trajet_temp['prix'] ?? 2);

    if (!$depart || !$arrivee || !$date_depart || !$heure_depart || !$vehicule_id) {
        die("Merci de remplir tous les champs obligatoires.");
    }

    $etapes = [];
    foreach ($trajet_temp as $key => $value) {
        if (strpos($key, 'step') === 0 && !empty(trim($value))) {
            $etapes[] = trim($value);
        }
    }
    $etapes_str = !empty($etapes) ? json_encode($etapes, JSON_UNESCAPED_UNICODE) : null;

    try {
        $sql = "INSERT INTO trajets
                (id_conducteur, depart, arrivee, date_depart, heure_depart, vehicule_id, places_disponibles, prix, statut, etapes, commentaire)
                VALUES (:id_conducteur, :depart, :arrivee, :date_depart, :heure_depart, :vehicule_id, :places_disponibles, :prix, 'publie', :etapes, :commentaire)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            ':id_conducteur'     => $id_conducteur,
            ':depart'            => $depart,
            ':arrivee'           => $arrivee,
            ':date_depart'       => $date_depart,
            ':heure_depart'      => $heure_depart,
            ':vehicule_id'       => $vehicule_id ?: null,
            ':places_disponibles'=> $places_disponibles,
            ':prix'              => $prix,
            ':etapes'            => $etapes_str,
            ':commentaire'       => $commentaire
        ]);

        unset($_SESSION['trajet_temp']);

        header('Location: ../UTILISATEUR/USR-mes-trajets.php?new=1');
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
        exit;
    }
}
?>