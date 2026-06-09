<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/connexion.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt_veh = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = ? ORDER BY vehicule_id ASC");
$stmt_veh->execute([$user_id]);
$vehicules = $stmt_veh->fetchAll(PDO::FETCH_ASSOC);

$couleurs   = ['Noir','Bleu','Blanc','Rouge','Gris','Orange','Vert','Jaune','Marron','Beige','Violet','Rose'];
$carburants = ['Essence','Diesel','Electrique','Hybride'];
$musiques   = ['none' => 'Pas de musique','classic' => 'Classique','pop' => 'Pop','rock' => 'Rock','jazz' => 'Jazz'];

$icones_carburant = [
    'Electrique' => '⚡',
    'Hybride'    => '🌿',
    'Essence'    => '⛽',
    'Diesel'     => '⛽',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations Conducteur</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-infos-conducteur.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .success-screen {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #1f1f1f;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            flex-direction: column;
            text-align: center;
            font-family: 'Quicksand', sans-serif;
        }
        .success-message h1 { font-size: 2rem; color: #4CAF50; margin: 0; }
        .confetti {
            position: absolute; width: 10px; height: 10px;
            background: #FFC107; animation: confetti-fall linear forwards;
            top: -10px; border-radius: 50%;
        }
        @keyframes confetti-fall {
            0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        .input-hint {
            font-family: 'Quicksand', sans-serif;
            font-size: 0.78rem;
            color: var(--gris-doux);
            margin-top: 0.3rem;
        }
    </style>
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

    <div class="conducteur-section">
        <h2>Mes véhicules</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="toast-success" style="margin-bottom:1rem;">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="toast-error" style="margin-bottom:1rem;">❌ <?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="vehicules-grid">
            <?php if (empty($vehicules)): ?>
                <p style="font-family:'Quicksand',sans-serif; color:var(--gris-doux);">Aucun véhicule enregistré.</p>
            <?php else: ?>
                <?php foreach ($vehicules as $v): ?>
                <div class="vehicule-card">
                    <div class="vehicule-card-icon">🚗</div>
                    <div class="vehicule-card-body">
                        <p class="vehicule-card-title"><?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?></p>
                        <div class="vehicule-card-details">
                            <span><?= $icones_carburant[$v['carburant']] ?? '⛽' ?> <?= htmlspecialchars($v['carburant'] ?? '—') ?></span>
                            <span>🎨 <?= htmlspecialchars($v['couleur'] ?? '—') ?></span>
                            <span>💺 <?= $v['places'] ?> places</span>
                            <span>🪪 <?= htmlspecialchars($v['plaque']) ?></span>
                            <span><?= $v['animaux_acceptes'] === 'oui' ? '🐾 Animaux OK' : '🚫 Sans animaux' ?></span>
                            <span><?= $v['fumeur'] === 'oui' ? '🚬 Fumeur' : '🚭 Non-fumeur' ?></span>
                        </div>
                    </div>
                    <button class="btn-modifier-vehicule"
                            data-id="<?= $v['vehicule_id'] ?>"
                            data-plaque="<?= htmlspecialchars($v['plaque'], ENT_QUOTES) ?>"
                            data-date="<?= htmlspecialchars($v['date_premiere_immat'] ?? '', ENT_QUOTES) ?>"
                            data-marque="<?= htmlspecialchars($v['marque'], ENT_QUOTES) ?>"
                            data-modele="<?= htmlspecialchars($v['modele'], ENT_QUOTES) ?>"
                            data-couleur="<?= htmlspecialchars($v['couleur'], ENT_QUOTES) ?>"
                            data-carburant="<?= htmlspecialchars($v['carburant'], ENT_QUOTES) ?>"
                            data-places="<?= $v['places'] ?>"
                            data-animaux="<?= $v['animaux_acceptes'] ?>"
                            data-fumeur="<?= $v['fumeur'] ?>"
                            data-musique="<?= htmlspecialchars($v['musique'] ?? '', ENT_QUOTES) ?>">
                        ✏️ Modifier
                    </button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button class="btn-ajouter-vehicule" id="btnAjouterVehicule">+ Ajouter un véhicule</button>
    </div>
</main>

<!-- MODAL MODIFIER -->
<div id="modal-modifier" class="modal-vehicule">
    <div class="modal-vehicule-content">
        <span class="modal-vehicule-close" id="closeModalModifier">&times;</span>
        <h2>Modifier le véhicule</h2>
        <form action="../PHP/traitement-vehicule.php" method="POST" novalidate>
            <input type="hidden" name="action" value="modifier">
            <input type="hidden" name="vehicule_id" id="mod_vehicule_id">

            <div class="form-group">
                <label>Plaque</label>
                <input type="text" name="plate" id="mod_plaque" placeholder="AB-123-CD" maxlength="20">
            </div>
            <div class="form-group">
                <label>Date de première immatriculation</label>
                <input type="date" name="date" id="mod_date">
            </div>
            <div class="form-group">
                <label>Marque</label>
                <input type="text" name="marque" id="mod_marque" placeholder="ex : Renault, Peugeot, Tesla..." maxlength="50">
                <p class="input-hint">Saisissez librement la marque de votre véhicule</p>
            </div>
            <div class="form-group">
                <label>Modèle</label>
                <input type="text" name="modele" id="mod_modele" placeholder="ex : Clio, 308, Model 3..." maxlength="50">
                <p class="input-hint">Saisissez librement le modèle de votre véhicule</p>
            </div>
            <div class="form-group">
                <label>Couleur</label>
                <select name="color" id="mod_couleur">
                    <option value="">Choisir une couleur</option>
                    <?php foreach ($couleurs as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Carburant</label>
                <select name="carburant" id="mod_carburant">
                    <option value="">Choisir un carburant</option>
                    <?php foreach ($carburants as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nombre de places</label>
                <input type="number" name="places" id="mod_places" min="1" max="8">
            </div>
            <div class="form-group">
                <label>Animaux acceptés</label>
                <div class="radio-inline">
                    <label><input type="radio" name="pets" id="mod_animaux_oui" value="oui"> Oui</label>
                    <label><input type="radio" name="pets" id="mod_animaux_non" value="non"> Non</label>
                </div>
            </div>
            <div class="form-group">
                <label>Fumeur</label>
                <div class="radio-inline">
                    <label><input type="radio" name="smoking" id="mod_fumeur_oui" value="oui"> Oui</label>
                    <label><input type="radio" name="smoking" id="mod_fumeur_non" value="non"> Non</label>
                </div>
            </div>
            <div class="form-group">
                <label>Musique</label>
                <select name="music" id="mod_musique">
                    <?php foreach ($musiques as $val => $label): ?><option value="<?= $val ?>"><?= $label ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="modal-vehicule-actions">
                <button type="submit" class="btn-enregistrer">Enregistrer</button>
                <button type="button" class="btn-supprimer-vehicule" id="btnSupprimerVehicule">🗑️ Supprimer</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL AJOUTER -->
<div id="modal-ajouter" class="modal-vehicule">
    <div class="modal-vehicule-content">
        <span class="modal-vehicule-close" id="closeModalAjouter">&times;</span>
        <h2>Ajouter un véhicule</h2>
        <form action="../PHP/traitement-vehicule.php" method="POST" novalidate>
            <input type="hidden" name="action" value="ajouter">

            <div class="form-group">
                <label>Plaque</label>
                <input type="text" name="plate" placeholder="AB-123-CD" maxlength="20">
            </div>
            <div class="form-group">
                <label>Date de première immatriculation</label>
                <input type="date" name="date">
            </div>
            <div class="form-group">
                <label>Marque</label>
                <input type="text" name="marque" placeholder="ex : Renault, Peugeot, Tesla..." maxlength="50">
                <p class="input-hint">Saisissez librement la marque de votre véhicule</p>
            </div>
            <div class="form-group">
                <label>Modèle</label>
                <input type="text" name="modele" placeholder="ex : Clio, 308, Model 3..." maxlength="50">
                <p class="input-hint">Saisissez librement le modèle de votre véhicule</p>
            </div>
            <div class="form-group">
                <label>Couleur</label>
                <select name="color">
                    <option value="">Choisir une couleur</option>
                    <?php foreach ($couleurs as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Carburant</label>
                <select name="carburant">
                    <option value="">Choisir un carburant</option>
                    <?php foreach ($carburants as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nombre de places</label>
                <input type="number" name="places" min="1" max="8">
            </div>
            <div class="form-group">
                <label>Animaux acceptés</label>
                <div class="radio-inline">
                    <label><input type="radio" name="pets" value="oui"> Oui</label>
                    <label><input type="radio" name="pets" value="non" checked> Non</label>
                </div>
            </div>
            <div class="form-group">
                <label>Fumeur</label>
                <div class="radio-inline">
                    <label><input type="radio" name="smoking" value="oui"> Oui</label>
                    <label><input type="radio" name="smoking" value="non" checked> Non</label>
                </div>
            </div>
            <div class="form-group">
                <label>Musique</label>
                <select name="music">
                    <?php foreach ($musiques as $val => $label): ?><option value="<?= $val ?>"><?= $label ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="modal-vehicule-actions">
                <button type="submit" class="btn-enregistrer">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- FORM SUPPRESSION (caché) -->
<form id="form-supprimer" action="../PHP/supprimer-vehicule.php" method="POST" style="display:none;">
    <input type="hidden" name="vehicule_id" id="suppr_vehicule_id">
</form>

<?php include('../COMPONENTS/COMP-footer.php'); ?>

<div id="success-screen" class="success-screen">
    <div class="success-message">
        <h1>🎉 C'est bon ! Votre profil est entièrement complété 🎉</h1>
    </div>
</div>

<script>
document.querySelectorAll('.btn-modifier-vehicule').forEach(btn => {
    btn.addEventListener('click', () => {
        const d = btn.dataset;
        document.getElementById('mod_vehicule_id').value = d.id;
        document.getElementById('mod_plaque').value      = d.plaque;
        document.getElementById('mod_date').value        = d.date;
        document.getElementById('mod_marque').value      = d.marque;
        document.getElementById('mod_modele').value      = d.modele;
        document.getElementById('mod_couleur').value     = d.couleur;
        document.getElementById('mod_carburant').value   = d.carburant;
        document.getElementById('mod_places').value      = d.places;
        document.getElementById('mod_animaux_' + d.animaux).checked = true;
        document.getElementById('mod_fumeur_'  + d.fumeur).checked  = true;
        document.getElementById('mod_musique').value     = d.musique;
        document.getElementById('suppr_vehicule_id').value = d.id;
        document.getElementById('modal-modifier').style.display = 'flex';
    });
});

document.getElementById('btnAjouterVehicule').addEventListener('click', () => {
    document.getElementById('modal-ajouter').style.display = 'flex';
});

document.getElementById('closeModalModifier').addEventListener('click', () => {
    document.getElementById('modal-modifier').style.display = 'none';
});
document.getElementById('closeModalAjouter').addEventListener('click', () => {
    document.getElementById('modal-ajouter').style.display = 'none';
});

['modal-modifier','modal-ajouter'].forEach(id => {
    document.getElementById(id).addEventListener('click', e => {
        if (e.target === document.getElementById(id))
            document.getElementById(id).style.display = 'none';
    });
});

document.getElementById('btnSupprimerVehicule').addEventListener('click', () => {
    if (confirm('Confirmer la suppression de ce véhicule ?')) {
        document.getElementById('form-supprimer').submit();
    }
});

function launchConfetti(count = 100) {
    const screen = document.getElementById('success-screen');
    for (let i = 0; i < count; i++) {
        const conf = document.createElement('div');
        conf.classList.add('confetti');
        conf.style.left              = Math.random() * 100 + 'vw';
        conf.style.background        = `hsl(${Math.random() * 360}, 70%, 50%)`;
        conf.style.animationDuration = 2 + Math.random() * 2 + 's';
        screen.appendChild(conf);
        setTimeout(() => conf.remove(), 4000);
    }
}
</script>
<script src="../JS/USR-infos-conducteur.js"></script>
</body>
</html>