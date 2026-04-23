<header class="header-employe">
    <h1 class="espace-employe">Espace Administrateur</h1>

    <div class="header-bottom">
        <div class="logo">
            <a href="accueil.php" class="logo-link">
                <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                <span class="logo-text">Eco Ride</span>
            </a>
        </div>
        <?php if (!empty($admin) && !empty($admin['prenom'])): ?>
        <div class="user-profile">
            <div class="user-info">
                <p><span class="label-employe">Nom :</span> <?= htmlspecialchars($admin['prenom']) ?></p>
                <p><span class="label-employe">Email :</span> <?= htmlspecialchars($admin['email']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>