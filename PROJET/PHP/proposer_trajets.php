<?php
include('../PHP/auth.php');
requireLogin();
require('../PHP/connexion.php'); // connexion PDO à la BDD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer l'ID de l'utilisateur connecté
    $id_conducteur = $_SESSION['id'] ?? 0;
    if (!$id_conducteur) {
        die("Utilisateur non identifié.");
    }

    // Récupération des champs du formulaire
    $depart = trim($_POST['departure'] ?? '');
    $arrivee = trim($_POST['arrival'] ?? '');
    $date_depart = $_POST['date'] ?? '';
    $heure_depart = $_POST['time'] ?? '';
    $vehicule_id = $_POST['vehicle_used'] ?? null;
    $places_disponibles = intval($_POST['places'] ?? 1);
    $commentaire = trim($_POST['commentaire'] ?? null);

    // --- Validation rapide côté serveur ---
    if (!$depart || !$arrivee || !$date_depart || !$heure_depart || !$vehicule_id) {
        die("Merci de remplir tous les champs obligatoires.");
    }

    // --- Gestion des arrêts dynamiques ---
    $etapes = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'step') === 0 && !empty(trim($value))) {
            $etapes[] = trim($value);
        }
    }
    // Stocker en JSON pour plus de sécurité
    $etapes_str = !empty($etapes) ? json_encode($etapes, JSON_UNESCAPED_UNICODE) : null;

    try {
        // --- Préparation et insertion SQL ---
        $sql = "INSERT INTO trajets
                (id_conducteur, depart, arrivee, date_depart, heure_depart, vehicule_id, places_disponibles, statut, etapes, commentaire)
                VALUES (:id_conducteur, :depart, :arrivee, :date_depart, :heure_depart, :vehicule_id, :places_disponibles, 'futur', :etapes, :commentaire)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_conducteur' => $id_conducteur,
            ':depart' => $depart,
            ':arrivee' => $arrivee,
            ':date_depart' => $date_depart,
            ':heure_depart' => $heure_depart,
            ':vehicule_id' => $vehicule_id ?: null,
            ':places_disponibles' => $places_disponibles,
            ':etapes' => $etapes_str,
            ':commentaire' => $commentaire
        ]);

        // --- Redirection vers l'étape suivante ---
        header('Location: proposer_trajet-2.php');
        exit;

    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement du trajet : " . htmlspecialchars($e->getMessage());
        exit;
    }
}
?>