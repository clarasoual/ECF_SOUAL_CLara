<?php
session_start();
include('../PHP/auth.php'); 
requireLogin();
require('../PHP/connexion.php');

$userId = $_SESSION['user_id'] ?? 0;

if ($userId) {
    try {
        $stmt = $bdd->prepare("SELECT role FROM utilisateurs WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !in_array($user['role'], ['conducteur', 'passager-conducteur'])) {
            header('Location: ../PHP/403.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur PDO : " . htmlspecialchars($e->getMessage()));
    }
} else {
    header('Location: ../PHP/login.php');
    exit;
}

if (isset($_GET['new']) && $_GET['new'] == 1) {
    unset($_SESSION['trajet_temp']);
}

$vehicules = [];
if ($userId) {
    try {
        $stmt = $bdd->prepare("
            SELECT vehicule_id, marque, modele, couleur 
            FROM vehicules 
            WHERE id_utilisateur = ? 
            ORDER BY marque, modele
        ");
        $stmt->execute([$userId]);
        $vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur PDO : " . htmlspecialchars($e->getMessage()));
    }
}

$trajet_temp = $_SESSION['trajet_temp'] ?? [
    'departure'    => '',
    'arrival'      => '',
    'date'         => '',
    'time'         => '',
    'time_arrivee' => '',
    'vehicle_used' => '',
    'places'       => '',
    'prix'         => 2,
    'commentaire'  => '',
    'etapes'       => ['']
];

$etapes = array_values($trajet_temp['etapes'] ?? []);
if (empty($etapes)) { $etapes = ['']; }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposer un trajet</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-proposer-trajet.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<section class="trip-step1">
    <h2 class="step-title">Votre trajet - Étape 1 sur 2</h2>
    <p class="required-note">Champs obligatoires</p>

    <form action="USR-proposer-trajet-2.php" method="POST" novalidate>
        <table class="trip-table">
            <tr>
                <!-- COLONNE GAUCHE : départ -->
                <td class="trip-info">
                    <h3 class="trip-subtitle">D'où partons-nous ?</h3>

                    <div class="form-group">
                        <label for="departure">Adresse de départ *</label>
                        <input type="text" id="departure" name="departure" placeholder="Adresse de départ"
                               value="<?= htmlspecialchars($trajet_temp['departure']) ?>">
                    </div>

                    <label>Arrêts (optionnel)</label>
                    <div id="etapes-container">
                        <?php foreach ($etapes as $i => $etape): ?>
                            <div class="stop-container">
                                <input type="text" id="step<?= $i+1 ?>" name="step<?= $i+1 ?>"
                                       placeholder="Arrêt n°<?= $i+1 ?>"
                                       value="<?= htmlspecialchars($etape) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="add-stop-btn">+ Ajouter un arrêt</button><br><br>

                    <div class="form-group">
                        <label for="vehicle-used">Véhicule utilisé *</label>
                        <select id="vehicle-used" name="vehicle_used">
                            <option value="">-- Sélectionnez un véhicule --</option>
                            <?php foreach ($vehicules as $vehicule): 
                                $vehicule_label = htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele'] . ' ' . $vehicule['couleur']); 
                                $selected = ($trajet_temp['vehicle_used'] == $vehicule['vehicule_id']) ? 'selected' : '';
                            ?>
                                <option value="<?= $vehicule['vehicule_id'] ?>" <?= $selected ?>><?= $vehicule_label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Date de départ *</label>
                        <input type="date" id="date" name="date"
                               value="<?= htmlspecialchars($trajet_temp['date']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="time">Heure de départ *</label>
                        <input type="time" id="time" name="time"
                               value="<?= htmlspecialchars($trajet_temp['time']) ?>">
                    </div>
                </td>

                <!-- COLONNE DROITE : arrivée -->
                <td class="trip-info-destination">
                    <h3 class="trip-subtitle">Où allons-nous ?</h3>

                    <div class="form-group">
                        <label for="arrival">Adresse d'arrivée *</label>
                        <input type="text" id="arrival" name="arrival" placeholder="Adresse d'arrivée"
                               value="<?= htmlspecialchars($trajet_temp['arrival']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="time_arrivee">Heure d'arrivée *</label>
                        <input type="time" id="time_arrivee" name="time_arrivee"
                               value="<?= htmlspecialchars($trajet_temp['time_arrivee'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="places">Nombre de places disponibles *</label>
                        <input type="number" id="places" name="places" min="1" max="8"
                               value="<?= htmlspecialchars($trajet_temp['places']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix par passager (en crédits) *</label>
                        <p class="credit-infos">⚠️ 2 crédits sont retenus par la plateforme. Minimum : 2 crédits.</p>
                        <input type="number" id="prix" name="prix" min="2" max="20"
                               value="<?= htmlspecialchars($trajet_temp['prix'] ?? 2) ?>">
                        <p class="credit-infos" id="gains-info">
                            Vous gagnerez <strong id="gains-calcul"><?= max(0, ($trajet_temp['prix'] ?? 2) - 2) ?></strong> crédits par passager (après retenue plateforme).
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Autres précisions (optionnel)</label>
                        <textarea id="commentaire" name="commentaire" rows="4" cols="40"
                                  placeholder="Ex : passage par autoroute, coffre petit..."><?= htmlspecialchars($trajet_temp['commentaire']) ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Étape suivante</button>
                </td>
            </tr>
        </table>
    </form>
</section>

<script src="../JS/USR-proposer-trajet.js"></script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>