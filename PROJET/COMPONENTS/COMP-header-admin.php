<header class="header-employe">
    <h1 class="espace-employe">Espace Administrateur</h1>

    <div class="header-bottom">
        <div class="logo">
            <a href="accueil.php" class="logo-link">
                <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                <span class="logo-text">Eco Ride</span>
            </a>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <p><span class="label-employe">Nom :</span> <?= htmlspecialchars($admin['prenom']) ?></p>
                <p><span class="label-employe">Email :</span> <?= htmlspecialchars($admin['email']) ?></p>
            </div>
            <div class="profil-container">
                <img src="../../IMAGES/default-avatar.jpg" alt="Photo de profil par défaut" class="photo-profil" id="profil-click">
                
                <div class="menu-profil">
                    <a href="moncompte.php">Mon compte</a>
                </div>
            </div>
        </div>
    </div>
</header>