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
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-avis-trajet.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
<div class="avis-wrapper">

    <!-- CARTE TRAJET -->
    <div class="trajet-card">
        <div class="trajet-route">
            <?= htmlspecialchars($trajet['depart']) ?>
            <span>→</span>
            <?= htmlspecialchars($trajet['arrivee']) ?>
        </div>
        <div class="trajet-conducteur">
            Conducteur : <strong><?= htmlspecialchars($trajet['prenom_conducteur'] . ' ' . $trajet['nom_conducteur']) ?></strong>
        </div>
    </div>

    <!-- FORMULAIRE AVIS -->
    <div class="form-block">
        <h2 class="form-block-title">Laisser un avis</h2>
        <form action="../PHP/soumettre-avis.php" method="POST" novalidate>
            <input type="hidden" name="id_trajet" value="<?= $id_trajet ?>">

            <div class="form-group">
                <label>Note *</label>
                <div class="star-rating">
                    <input type="radio" name="note" id="star5" value="5">
                    <label for="star5" title="Excellent">★</label>
                    <input type="radio" name="note" id="star4" value="4">
                    <label for="star4" title="Bien">★</label>
                    <input type="radio" name="note" id="star3" value="3">
                    <label for="star3" title="Correct">★</label>
                    <input type="radio" name="note" id="star2" value="2">
                    <label for="star2" title="Décevant">★</label>
                    <input type="radio" name="note" id="star1" value="1">
                    <label for="star1" title="Mauvais">★</label>
                </div>
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire (optionnel)</label>
                <textarea name="commentaire" id="commentaire" rows="4" placeholder="Décrivez votre expérience..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Envoyer mon avis</button>
                <a href="USR-mes-trajets.php" class="btn-annuler">Passer</a>
            </div>
        </form>
    </div>

    <!-- SIGNALEMENT -->
    <div class="form-block">
        <h2 class="form-block-title danger">🚨 Signaler un problème</h2>
        <p style="font-family:'Quicksand',sans-serif; font-size:0.95rem; color:var(--gris-doux); margin:0;">
            Si ce trajet s'est mal passé, vous pouvez le signaler. Un employé prendra contact avec le conducteur.
        </p>
        <form action="../PHP/signaler-trajet.php" method="POST" novalidate
              onsubmit="return confirm('Confirmer le signalement de ce trajet ?')">
            <input type="hidden" name="id_trajet" value="<?= $id_trajet ?>">

            <div class="form-group">
                <label for="commentaire_signalement">Décrivez le problème *</label>
                <textarea name="commentaire_signalement" id="commentaire_signalement" rows="4"
                          placeholder="Décrivez ce qui s'est mal passé..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-probleme">🚨 Signaler ce trajet</button>
            </div>
        </form>
    </div>

</div>
</main>

<script src="../JS/USR-avis-trajet.js"></script>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>
