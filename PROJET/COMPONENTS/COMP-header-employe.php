<header class="header-employe">
    <h1 class="espace-employe">Espace Employé</h1>
    <div class="header-bottom">
        <div class="logo">
            <a href="accueil.php" class="logo-link">
                <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                <span class="logo-text">Eco Ride</span>
            </a>
        </div>
        <?php if (!empty($_SESSION['employe_prenom'])): ?>
        <div class="user-profile">
            <div class="user-info">
                <p><span class="label-employe">Nom :</span> <?= htmlspecialchars($_SESSION['employe_prenom']) ?></p>
                <p><span class="label-employe">Email :</span> <?= htmlspecialchars($_SESSION['employe_email']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</header>
