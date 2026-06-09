<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../PHP/auth.php');
requireLogin();
require_once('../PHP/connexion.php');

$id_conducteur = $_SESSION['user_id'] ?? null;
if (!$id_conducteur) {
    header('Location: ../index.php');
    exit;
}

$id_trajet = isset($_GET['id_trajet']) ? (int)$_GET['id_trajet'] : 0;
if (!$id_trajet) {
    header('Location: USR-mes-trajets.php');
    exit;
}

$stmtTrajet = $bdd->prepare("
    SELECT * FROM trajets
    WHERE id = ? AND id_conducteur = ? AND statut = 'termine'
");
$stmtTrajet->execute([$id_trajet, $id_conducteur]);
$trajet = $stmtTrajet->fetch(PDO::FETCH_ASSOC);

if (!$trajet) {
    header('Location: USR-mes-trajets.php');
    exit;
}

$stmtPassagers = $bdd->prepare("
    SELECT
        u.id,
        u.prenom,
        u.nom,
        u.photo,
        tp.statut AS statut_reservation,
        (SELECT COUNT(*) FROM avis
         WHERE id_trajet = :id_trajet AND id_auteur = :id_conducteur AND id_destinataire = u.id
        ) AS deja_note,
        (SELECT COUNT(*) FROM signalements
         WHERE id_trajet = :id_trajet2 AND id_utilisateur = u.id
        ) AS deja_signale
    FROM trajets_passagers tp
    JOIN utilisateurs u ON tp.id_passager = u.id
    WHERE tp.id_trajet = :id_trajet3 AND tp.statut NOT IN ('annule')
");
$stmtPassagers->execute([
    ':id_trajet'     => $id_trajet,
    ':id_conducteur' => $id_conducteur,
    ':id_trajet2'    => $id_trajet,
    ':id_trajet3'    => $id_trajet,
]);
$passagers = $stmtPassagers->fetchAll(PDO::FETCH_ASSOC);

if (empty($passagers)) {
    header('Location: USR-mes-trajets.php');
    exit;
}

$date_fmt  = date('d/m/Y', strtotime($trajet['date_depart']));
$heure_fmt = substr($trajet['heure_depart'], 0, 5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter mes passagers</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-avis-passager.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Mon compte —</option>
    <option value="../UTILISATEUR/USR-infos-perso.php">Informations personnelles</option>
    <option value="../UTILISATEUR/USR-mes-trajets.php">Mes trajets</option>
    <option value="../UTILISATEUR/USR-avis.php">Avis</option>
    <option value="../UTILISATEUR/USR-gestion-credits.php">Crédits</option>
    <option value="../UTILISATEUR/USR-infos-conducteur.php">Informations conducteur</option>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

    <section class="avis-passager-section">

        <div class="avis-header">
            <a href="USR-mes-trajets.php" class="btn-retour">← Retour</a>
            <h2>⭐ Noter mes passagers</h2>
            <p class="trajet-resume">
                <?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?>
                &nbsp;·&nbsp; <?= $date_fmt ?> à <?= $heure_fmt ?>
            </p>
        </div>

        <?php if (isset($_GET['succes'])): ?>
            <?php if ($_GET['succes'] === 'avis_et_signalement'): ?>
                <div class="toast-inline toast-success">✅ Avis envoyé et signalement transmis à notre équipe.</div>
            <?php else: ?>
                <div class="toast-inline toast-success">✅ Avis envoyé, il sera publié après modération.</div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['erreur'])): ?>
            <div class="toast-inline toast-error">❌ <?= htmlspecialchars($_GET['erreur']) ?></div>
        <?php endif; ?>

        <div class="passagers-liste">
            <?php foreach ($passagers as $p): ?>
            <div class="passager-card <?= $p['deja_note'] ? 'deja-note' : '' ?>">

                <div class="passager-info">
                    <div class="passager-avatar">
                        <?php if (!empty($p['photo'])): ?>
                            <img src="/eco_ride/IMAGES/profiles/<?= htmlspecialchars($p['photo']) ?>" alt="Photo">
                        <?php else: ?>
                            <div class="avatar-placeholder"><?= strtoupper(substr($p['prenom'], 0, 1)) ?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></strong>
                    </div>
                </div>

                <?php if ($p['deja_note']): ?>
                    <div class="deja-note-badge">✅ Avis déjà laissé</div>

                <?php else: ?>
                <form action="../PHP/traiter-avis-passager.php" method="POST" class="form-avis-passager">
                    <input type="hidden" name="id_trajet"       value="<?= $id_trajet ?>">
                    <input type="hidden" name="id_destinataire" value="<?= $p['id'] ?>">

                    <div class="stars-group">
                        <label class="field-label">Note</label>
                        <div class="stars" data-name="note_<?= $p['id'] ?>">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="note" id="star<?= $i ?>_<?= $p['id'] ?>" value="<?= $i ?>">
                                <label for="star<?= $i ?>_<?= $p['id'] ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="commentaire_<?= $p['id'] ?>">Commentaire <span class="optionnel">(optionnel)</span></label>
                        <textarea
                            id="commentaire_<?= $p['id'] ?>"
                            name="commentaire"
                            placeholder="Votre ressenti sur ce passager..."
                            maxlength="500"
                            rows="3"
                        ></textarea>
                    </div>

                    <?php if (!$p['deja_signale']): ?>
                    <div class="field-group signalement-toggle-group">
                        <label class="toggle-signalement">
                            <input type="checkbox" class="cb-signalement" id="cb_signal_<?= $p['id'] ?>">
                            <span>🚨 Signaler ce passager</span>
                        </label>
                        <div class="signalement-motif" id="motif_<?= $p['id'] ?>" style="display:none;">
                            <label class="field-label" for="motif_txt_<?= $p['id'] ?>">Motif du signalement <span class="optionnel">(visible uniquement par les employés)</span></label>
                            <textarea
                                id="motif_txt_<?= $p['id'] ?>"
                                name="motif_signalement"
                                placeholder="Décrivez le problème rencontré avec ce passager..."
                                maxlength="1000"
                                rows="3"
                            ></textarea>
                        </div>
                    </div>
                    <?php else: ?>
                        <p class="deja-signale-info">🚨 Passager déjà signalé</p>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn-soumettre">Envoyer l'avis</button>
                    </div>
                </form>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
        </div>

    </section>
</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>

<script>
document.querySelectorAll('.cb-signalement').forEach(cb => {
    cb.addEventListener('change', function () {
        const passagerId = this.id.replace('cb_signal_', '');
        const motifDiv   = document.getElementById('motif_' + passagerId);
        if (motifDiv) motifDiv.style.display = this.checked ? 'block' : 'none';
    });
});

document.querySelectorAll('.form-avis-passager').forEach(form => {
    form.addEventListener('submit', function (e) {
        const noteSelectionnee = form.querySelector('input[name="note"]:checked');
        if (!noteSelectionnee) {
            e.preventDefault();
            form.querySelector('.stars').classList.add('stars-error');
            setTimeout(() => form.querySelector('.stars').classList.remove('stars-error'), 2000);
        }
    });
});
</script>
</body>
</html>