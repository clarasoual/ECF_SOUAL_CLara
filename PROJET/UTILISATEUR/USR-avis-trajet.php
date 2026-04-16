<?php
require_once('../PHP/auth.php');
requireLogin();
require_once('../PHP/connexion.php');

$id_trajet = isset($_GET['id_trajet']) ? (int)$_GET['id_trajet'] : 0;

if (!$id_trajet) {
    header('Location: USR-mes-trajets.php');
    exit;
}

$stmt = $bdd->prepare("
    SELECT t.*, u.prenom AS prenom_conducteur, u.nom AS nom_conducteur
    FROM trajets t
    JOIN utilisateurs u ON u.id = t.id_conducteur
    WHERE t.id = ?
");
$stmt->execute([$id_trajet]);
$trajet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    header('Location: USR-mes-trajets.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un avis</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
    <h1>Laisser un avis</h1>

    <p>Trajet : <strong><?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?></strong></p>
    <p>Conducteur : <strong><?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?></strong></p>

    <!-- Formulaire avis -->
    <form action="../PHP/soumettre-avis.php" method="POST" novalidate>
        <input type="hidden" name="id_trajet" value="<?= $id_trajet ?>">

        <div class="form-group">
            <label for="note">Note (1 à 5) *</label>
            <select name="note" id="note">
                <option value="">-- Choisir une note --</option>
                <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                <option value="4">⭐⭐⭐⭐ - Bien</option>
                <option value="3">⭐⭐⭐ - Correct</option>
                <option value="2">⭐⭐ - Décevant</option>
                <option value="1">⭐ - Mauvais</option>
            </select>
        </div>

        <div class="form-group">
            <label for="commentaire">Commentaire (optionnel)</label>
            <textarea name="commentaire" id="commentaire" rows="4" placeholder="Décrivez votre expérience..."></textarea>
        </div>

        <button type="submit" class="btn-submit">Envoyer mon avis</button>
        <a href="USR-mes-trajets.php" class="btn-annuler">Passer</a>
    </form>

    <!-- Signalement -->
    <hr>
    <h2>Un problème avec ce trajet ?</h2>
    <p>Si ce trajet s'est mal passé, vous pouvez le signaler. Un employé prendra contact avec le conducteur.</p>

    <form action="../PHP/signaler-trajet.php" method="POST" novalidate
          onsubmit="return confirm('Confirmer le signalement de ce trajet ?')">
        <input type="hidden" name="id_trajet" value="<?= $id_trajet ?>">

        <div class="form-group">
            <label for="commentaire_signalement">Décrivez le problème *</label>
            <textarea name="commentaire_signalement" id="commentaire_signalement" rows="4"
                      placeholder="Décrivez ce qui s'est mal passé..."></textarea>
        </div>

        <button type="submit" class="btn-probleme">🚨 Signaler ce trajet</button>
    </form>

</main>

<script src="../JS/USR-avis-trajet.js"></script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>