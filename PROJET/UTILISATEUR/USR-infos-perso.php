<?php
session_start();
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

<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<div class="profile-content">

<section class="profil-header">

    <img
        src="/eco_ride/IMAGES/profiles/<?= htmlspecialchars($user['photo'] ?? 'default.jpg') ?>"
        alt="Photo de profil"
        class="profil-photo"
    >

    <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>

    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>

    <button
        id="openModalBtn"
        type="button"
        class="btn-submit"
    >
        Modifier mes informations
    </button>
</section>



<section class="user-role">
    <h3>Vous</h3>

    <label>
        <input type="radio" disabled <?= $user['role'] === 'passager' ? 'checked' : '' ?>>
        Passager
    </label>

    <label>
        <input type="radio" disabled <?= $user['role'] === 'conducteur' ? 'checked' : '' ?>>
        Conducteur
    </label>

    <label>
        <input type="radio" disabled <?= $user['role'] === 'passager-conducteur' ? 'checked' : '' ?>>
        Passager-conducteur
    </label>

    <div class="alert-message">
        Merci de compléter vos informations conducteur pour activer ce rôle !
    </div>
</section>

<!-- ================= VÉRIFICATION ================= -->
<section class="profile-verification">
    <h3>Vérifier le profil</h3>
    <ul>
        <li>Ajouter une carte d'identité</li>
        <li>Confirmer l'adresse mail</li>
        <li>Confirmer le numéro de téléphone</li>
    </ul>
</section>
</div>
<!-- ================= RÔLE ================= -->

</main>

<!-- ================= MODAL ================= -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span id="closeModalBtn" class="close">&times;</span>

        <h2>Modifier mes informations</h2>

        <form action="../PHP/update_infos.php" method="POST" enctype="multipart/form-data">
            
            <!-- Photo de profil -->
            <label for="photo">Photo de profil :</label>
            <input type="file" name="photo" id="photo" accept="image/*">

            <!-- Nom et prénom (grisés) -->
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
            <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
            <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>

            <!-- Email (grisé) -->
            <label for="email">Email :</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            <div class="contact-note">Merci de contacter Eco Ride pour modifier cette information</div>

            <!-- Date de naissance -->
            <label for="date_naissance">Date de naissance :</label>
            <input type="date" name="date_naissance" id="date_naissance" value="<?= htmlspecialchars($user['date_naissance']) ?>">

            <!-- Bio -->
            <label for="bio">Bio :</label>
            <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

            <!-- Lien pour changer le mot de passe -->
            <div class="password-link">
                <a href="/eco_ride/PROJET/UTILISATEUR/USR-modif-mdp.php">Modifier le mot de passe</a>
            </div>

            <button type="submit" class="btn-submit">
                Enregistrer
            </button>
        </form>
    </div>
</div>

<script src="../JS/USR-modal.js"></script>
</body>
</html>
