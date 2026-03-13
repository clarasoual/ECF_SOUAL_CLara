<div class="header-employe">
    <!-- Header Top : logo à gauche, nav + profil à droite -->
    <div class="header-top">
        
        <!-- Logo à gauche -->
        <div class="logo">
            <a href="../UTILISATEUR/USR-index.php" class="logo-link">
                <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                <span class="logo-text">Eco Ride</span>
            </a>
        </div>

        <!-- Nav + Profil à droite -->
        <div class="header-right">
            <!-- Navigation -->
            <nav class="nav-container">
                <a class="nav-links" href="../UTILISATEUR/USR-index.php">Accueil</a>
                <a class="nav-links" href="../UTILISATEUR/USR-proposer-trajet.php">Proposer un trajet</a>
                <a class="nav-links" href="../UTILISATEUR/USR-recherche_trajet.php">Rechercher un covoiturage</a>
            </nav>

            <!-- Profil -->
            <div class="profil-container">
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

    <!-- Header Bottom : burger menu pour mobile -->
    <div class="header-bottom">
        <div class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Script burger -->
    <script>
        const burger = document.querySelector('.burger-menu');
        const nav = document.querySelector('.nav-container');

        burger.addEventListener('click', () => {
            nav.classList.toggle('active');
            burger.classList.toggle('open');
        });
    </script>
</div>