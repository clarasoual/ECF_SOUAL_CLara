<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$photo = $_SESSION['photo'] ?? 'default.jpg';

if ($isLoggedIn) {
    $profilLink = '../UTILISATEUR/USR-infos-perso.php';
} else {
    $profilLink = '../UTILISATEUR/USR-connexion-inscription.php?redirect=../UTILISATEUR/USR-infos-perso.php';
}
?>

<header>
    <div class="header-employe">
        <div class="header-bottom">

            <div class="logo">
                <a href="../UTILISATEUR/USR-index.php" class="logo-link">
                    <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                    <span class="logo-text">Eco Ride</span>
                </a>
            </div>

            <div class="user-profile">
                <nav class="nav-container">
                    <a class="nav-links" href="../UTILISATEUR/USR-index.php">Accueil</a>
                    <a class="nav-links" href="../UTILISATEUR/USR-proposer-trajet.php">Proposer un trajet</a>
                    <a class="nav-links" href="../UTILISATEUR/USR-recherche_trajet.php">Rechercher un covoiturage</a>
                </nav>

                <div class="profil-container">
                    <div class="user-info">
                        <a href="<?= $profilLink ?>">
                            <img
                                src="/eco_ride/IMAGES/profiles/<?= htmlspecialchars($photo) ?>"
                                alt="Photo de profil"
                                class="photo-profil"
                                id="profil-click">
                        </a>

                        <?php if ($isLoggedIn): ?>
                            <div class="menu-profil">
                                <a href="../UTILISATEUR/USR-infos-perso.php">Mon compte</a>
                                <a href="../UTILISATEUR/USR-deconnexion.php"
                                   onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');"
                                   style="color:red;">
                                   Déconnexion
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</header>