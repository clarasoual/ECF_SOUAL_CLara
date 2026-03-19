<?php
session_start();
include('../PHP/auth.php'); 
requireLogin();
require('../PHP/connexion.php'); // $bdd créé ici

// --- Réinitialiser le trajet temporaire uniquement si on arrive ici depuis "Proposer un nouveau trajet" ---
if (isset($_GET['new']) && $_GET['new'] == 1) {
    unset($_SESSION['trajet_temp']);
}
$userId = $_SESSION['user_id'] ?? 0;

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

// --- Pré-remplir les champs uniquement si $_SESSION['trajet_temp'] existe ---
$trajet_temp = $_SESSION['trajet_temp'] ?? [
    'departure' => '',
    'arrival' => '',
    'date' => '',
    'time' => '',
    'vehicle_used' => '',
    'places' => '',
    'commentaire' => '',
    'etapes' => ['']
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

<form action="USR-proposer-trajet-2.php" method="POST">
    <table class="trip-table">
        <tr>
            <td class="trip-info">
                <h3 class="trip-subtitle">D'où partons-nous ?</h3>
                <label for="departure">Adresse de départ *</label><br>
                <input type="text" id="departure" name="departure" placeholder="Adresse de départ" required
                       value="<?= htmlspecialchars($trajet_temp['departure']) ?>"><br><br>

                <label>Arrêts (optionnel)</label><br>
                <div id="etapes-container">
                    <?php foreach ($etapes as $i => $etape): ?>
                        <div class="stop-container">
                            <input type="text" id="step<?= $i+1 ?>" name="step<?= $i+1 ?>" placeholder="Arrêt n°<?= $i+1 ?>" 
                                   value="<?= htmlspecialchars($etape) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-stop-btn">+ Ajouter un arrêt</button><br><br>

                <label for="vehicle-used">Véhicule utilisé *</label><br>
                <select id="vehicle-used" name="vehicle_used" required>
                    <option value="">-- Sélectionnez un véhicule --</option>
                    <?php foreach ($vehicules as $vehicule): 
                        $vehicule_label = htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele'] . ' ' . $vehicule['couleur']); 
                        $selected = ($trajet_temp['vehicle_used'] == $vehicule['vehicule_id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $vehicule['vehicule_id'] ?>" <?= $selected ?>><?= $vehicule_label ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label for="date">Date de départ *</label><br>
                <input type="date" id="date" name="date" required value="<?= htmlspecialchars($trajet_temp['date']) ?>"><br><br>

                <label for="time">Heure de départ *</label><br>
                <input type="time" id="time" name="time" required value="<?= htmlspecialchars($trajet_temp['time']) ?>"><br><br>
            </td>

            <td class="trip-info-destination">
                <h3 class="trip-subtitle">Où allons-nous ?</h3>
                <label for="arrival">Adresse d'arrivée *</label><br>
                <input type="text" id="arrival" name="arrival" placeholder="Adresse d'arrivée" required
                       value="<?= htmlspecialchars($trajet_temp['arrival']) ?>"><br><br>

                <label for="places">Nombre de places disponibles *</label><br>
                <input type="number" id="places" name="places" min="1" max="8" required
                       value="<?= htmlspecialchars($trajet_temp['places']) ?>"><br><br>

                <label for="commentaire">Autres précisions (optionnel)</label><br>
                <textarea id="commentaire" name="commentaire" rows="4" cols="40" 
                          placeholder="Ex : passage par autoroute, coffre petit..."><?= htmlspecialchars($trajet_temp['commentaire']) ?></textarea><br><br>

                <p class="credit-infos">
                    Ce trajet vous fera gagner <strong>5 crédits</strong> par passager une fois effectué.
                </p>

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