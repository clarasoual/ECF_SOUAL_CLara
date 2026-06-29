<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/infos-perso.php');

$est_conducteur = in_array($user['role'], ['conducteur', 'passager-conducteur']);

$vehicules = [];
if ($est_conducteur) {
    $stmt_veh = $bdd->prepare("SELECT * FROM vehicules WHERE id_utilisateur = ? ORDER BY vehicule_id ASC");
    $stmt_veh->execute([$user['id']]);
    $vehicules = $stmt_veh->fetchAll(PDO::FETCH_ASSOC);
}

$roles = [
    'passager'            => '🧳 Passager',
    'conducteur'          => '🚗 Conducteur',
    'passager-conducteur' => '🧳🚗 Passager & Conducteur',
];

$stmt_trajets = $bdd->prepare("SELECT COUNT(*) FROM trajets_passagers WHERE id_passager = ? AND statut = 'termine'");
$stmt_trajets->execute([$user['id']]);
$nb_trajets = $stmt_trajets->fetchColumn();

$stmt_avis = $bdd->prepare("SELECT COUNT(*), COALESCE(AVG(note), 0) FROM avis WHERE id_destinataire = ? AND statut = 'valide'");
$stmt_avis->execute([$user['id']]);
[$nb_avis, $note_moy] = $stmt_avis->fetch(PDO::FETCH_NUM);

$stmt_solde = $bdd->prepare("SELECT solde FROM credits WHERE id_utilisateur = ?");
$stmt_solde->execute([$user['id']]);
$solde = $stmt_solde->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte – Informations personnelles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-infos-perso.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php if (isset($_SESSION['success'])): ?>
    <div id="toast-success" class="toast-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div id="toast-error" class="toast-error">❌ <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Mon compte —</option>
    <option value="../UTILISATEUR/USR-infos-perso.php">Informations personnelles</option>
    <option value="../UTILISATEUR/USR-mes-trajets.php">Mes trajets</option>
    <option value="../UTILISATEUR/USR-avis.php">Avis</option>
    <option value="../UTILISATEUR/USR-gestion-credits.php">Crédits</option>
    <?php if ($est_conducteur): ?>
    <option value="../UTILISATEUR/USR-infos-conducteur.php">Informations conducteur</option>
    <?php endif; ?>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

    <div class="profile-content">
        <section class="profil-header">

            <div class="profil-top">
                <img src="/IMAGES/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>"
                     alt="Photo de profil" class="profil-photo">

                <div class="profil-infos">
                    <div class="profil-nom-role">
                        <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
                        <span class="profil-role-badge"><?= $roles[$user['role']] ?? '—' ?></span>
                    </div>

                    <div class="profil-info-grid">
                        <div class="profil-info-item">
                            <span class="profil-info-label">Email</span>
                            <span class="profil-info-value"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="profil-info-item">
                            <span class="profil-info-label">Date de naissance</span>
                            <span class="profil-info-value">
                                <?= !empty($user['date_naissance']) ? date('d/m/Y', strtotime($user['date_naissance'])) : '—' ?>
                            </span>
                        </div>
                        <?php if (!empty($user['bio'])): ?>
                        <div class="profil-info-item profil-info-full">
                            <span class="profil-info-label">Bio</span>
                            <span class="profil-info-value"><?= htmlspecialchars($user['bio']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="profil-stats">
                <div class="profil-stat-item">
                    <span class="profil-stat-value"><?= $nb_trajets ?></span>
                    <span class="profil-stat-label">Trajet<?= $nb_trajets > 1 ? 's' : '' ?> effectué<?= $nb_trajets > 1 ? 's' : '' ?></span>
                </div>
                <div class="profil-stat-item">
                    <span class="profil-stat-value"><?= $nb_avis > 0 ? number_format($note_moy, 1) . ' ⭐' : '—' ?></span>
                    <span class="profil-stat-label">Note moyenne</span>
                </div>
                <div class="profil-stat-item">
                    <span class="profil-stat-value"><?= $nb_avis ?></span>
                    <span class="profil-stat-label">Avis reçu<?= $nb_avis > 1 ? 's' : '' ?></span>
                </div>
                <div class="profil-stat-item">
                    <span class="profil-stat-value"><?= $solde ?> 💳</span>
                    <span class="profil-stat-label">Crédits</span>
                </div>
            </div>

            <?php if ($est_conducteur): ?>
            <div class="profil-vehicules">
                <h3>Mes véhicules</h3>
                <?php if (empty($vehicules)): ?>
                    <p class="profil-vehicule-vide">Aucun véhicule enregistré.</p>
                <?php else: ?>
                    <div class="profil-vehicules-liste">
                        <?php foreach ($vehicules as $v): ?>
                        <div class="profil-vehicule-item">
                            <span class="profil-vehicule-icon">🚗</span>
                            <div>
                                <p class="profil-vehicule-nom">
                                    <?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>
                                    <?php if ($v['couleur']): ?> — <?= htmlspecialchars($v['couleur']) ?><?php endif; ?>
                                </p>
                                <p class="profil-vehicule-detail">
                                    <?= htmlspecialchars($v['carburant'] ?? '—') ?>
                                    · <?= $v['places'] ?> places
                                    · <?= htmlspecialchars($v['plaque']) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="profil-actions">
                <button id="openModalBtn" type="button" class="btn-submit">✏️ Modifier mes informations</button>
                <?php if ($est_conducteur): ?>
                <a href="../UTILISATEUR/USR-infos-conducteur.php" class="btn-gerer-vehicules">🚗 Gérer mes véhicules</a>
                <?php endif; ?>
            </div>

        </section>
    </div>
</main>

<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span id="closeModalBtn" class="close">&times;</span>
        <h2>Modifier mes informations</h2>

        <form action="../PHP/update_infos.php" method="POST" enctype="multipart/form-data" novalidate>

            <div class="form-group">
                <label for="photo">Photo de profil</label>
                <input type="file" name="photo" id="photo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="text" id="date_naissance"
                       value="<?= !empty($user['date_naissance']) ? date('d/m/Y', strtotime($user['date_naissance'])) : '—' ?>"
                       disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" rows="3"><?= htmlspecialchars($user['bio']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Rôle</label>
                <div class="modal-radio-group">
                    <label class="modal-radio-option">
                        <input type="radio" name="role" value="passager" <?= $user['role'] === 'passager' ? 'checked' : '' ?>>
                        🧳 Passager
                    </label>
                    <label class="modal-radio-option">
                        <input type="radio" name="role" value="conducteur" <?= $user['role'] === 'conducteur' ? 'checked' : '' ?>>
                        🚗 Conducteur
                    </label>
                    <label class="modal-radio-option">
                        <input type="radio" name="role" value="passager-conducteur" <?= $user['role'] === 'passager-conducteur' ? 'checked' : '' ?>>
                        🧳🚗 Passager & Conducteur
                    </label>
                </div>
            </div>

            <div class="password-link">
                <a href="/eco_ride/PROJET/UTILISATEUR/USR-modif-mdp.php">Modifier le mot de passe</a>
            </div>

            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </div>
</div>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/USR-modal.js"></script>
</body>
</html>
