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

            <!-- Navigation desktop -->
            <div class="user-profile">
                <nav class="nav-container">
                    <a class="nav-links" href="../UTILISATEUR/USR-index.php">Accueil</a>
                    <a class="nav-links" href="../UTILISATEUR/USR-proposer-trajet.php">Proposer un trajet</a>
                    <a class="nav-links" href="../UTILISATEUR/USR-recherche_trajet.php">Rechercher un covoiturage</a>
                </nav>

                <div class="profil-container">
                    <div class="user-info">
                        <a href="<?= $profilLink ?>">
                            <img src="../../IMAGES/profiles/<?= htmlspecialchars($photo) ?>"
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

            <!-- Burger menu mobile -->
            <button class="burger-btn" id="burger-btn" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>
    </div>

    <!-- Menu mobile déroulant -->
    <nav class="mobile-nav" id="mobile-nav">
        <a href="../UTILISATEUR/USR-index.php">Accueil</a>
        <a href="../UTILISATEUR/USR-proposer-trajet.php">Proposer un trajet</a>
        <a href="../UTILISATEUR/USR-recherche_trajet.php">Rechercher un covoiturage</a>
        <div class="mobile-profil-links">
            <?php if ($isLoggedIn): ?>
                <a href="../UTILISATEUR/USR-infos-perso.php">👤 Mon compte</a>
                <a href="../UTILISATEUR/USR-deconnexion.php"
                   onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');"
                   style="color:#e74c3c;">
                   Déconnexion
                </a>
            <?php else: ?>
                <a href="../UTILISATEUR/USR-connexion-inscription.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
const burgerBtn = document.getElementById('burger-btn');
const mobileNav = document.getElementById('mobile-nav');
burgerBtn?.addEventListener('click', () => {
    mobileNav.classList.toggle('open');
});
</script>