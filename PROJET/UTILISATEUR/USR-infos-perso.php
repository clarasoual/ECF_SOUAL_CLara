<?php
include('../PHP/auth.php');
requireLogin();
include('../PHP/infos-perso.php');
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
    <div id="toast-success" class="toast-success">
        ✅ <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div id="toast-error" class="toast-error">
        ❌ <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<!-- SELECT MOBILE -->
<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Mon compte —</option>
    <option value="../UTILISATEUR/USR-infos-perso.php">Informations personnelles</option>
    <option value="../UTILISATEUR/USR-mes-trajets.php">Mes trajets</option>
    <option value="../UTILISATEUR/USR-avis.php">Avis</option>
    <option value="../UTILISATEUR/USR-gestion-credits.php">Crédits</option>
    <option value="../UTILISATEUR/USR-infos-conducteur.php">Informations conducteur</option>
    <option value="../UTILISATEUR/USR-aide.php">Aide</option>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

    <div class="profile-content">

        <!-- PHOTO + INFOS -->
        <section class="profil-header">
            <img src="/eco_ride/IMAGES/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>"
                 alt="Photo de profil" class="profil-photo">

            <div class="profil-infos">
                <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>

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
                    <div class="profil-info-item">
                        <span class="profil-info-label">Rôle</span>
                        <span class="profil-info-value">
                            <?php
                            $roles = [
                                'passager' => '🧳 Passager',
                                'conducteur' => '🚗 Conducteur',
                                'passager-conducteur' => '🧳🚗 Passager & Conducteur',
                            ];
                            echo $roles[$user['role']] ?? '—';
                            ?>
                        </span>
                    </div>
                    <?php if (!empty($user['bio'])): ?>
                    <div class="profil-info-item profil-info-full">
                        <span class="profil-info-label">Bio</span>
                        <span class="profil-info-value"><?= htmlspecialchars($user['bio']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <button id="openModalBtn" type="button" class="btn-submit">Modifier mes informations</button>
            </div>




        </section>

        <!-- STATUT -->
        <section class="profil-block">
            <h3>Votre statut</h3>
            <p class="profil-role">
                <?= $roles[$user['role']] ?? '—' ?>
            </p>
            <a href="../UTILISATEUR/USR-infos-conducteur.php" class="profil-link">
                Gérer mes informations conducteur →
            </a>
        </section>

        <!-- VOS VÉHICULES -->
        <section class="profil-block">
            <h3>Vos véhicules</h3>
            <div class="profil-vehicule-item">
                <span class="profil-vehicule-icon">🚗</span>
                <div>
                    <p class="profil-vehicule-nom">Renault Clio — Gris</p>
                    <p class="profil-vehicule-detail">Essence · 4 places · AB-123-CD</p>
                </div>
            </div>
            <a href="../UTILISATEUR/USR-infos-conducteur.php" class="profil-link">
                Gérer mes véhicules →
            </a>
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
                <label for="photo">Photo de profil :</label>
                <input type="file" name="photo" id="photo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="text" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>
            </div>

            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" name="date_naissance" id="date_naissance"
                       value="<?= htmlspecialchars($user['date_naissance']) ?>">
            </div>

            <div class="form-group">
                <label for="bio">Bio :</label>
                <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>
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