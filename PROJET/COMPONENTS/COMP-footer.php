<footer>
    <div class="footer-container">
        <a href="#">Mentions légales</a>
        <a href="#">Règlement de la plateforme</a>
        <!-- Bouton déconnexion si l'utilisateur est connecté -->
            <?php
        require_once(__DIR__ . '/../PHP/auth.php'); // plus sûr que include relatif
        if (isset($_SESSION['user_id'])) {
            echo '<a href="../UTILISATEUR/USR-deconnexion.php" 
                    onclick="return confirm(\'Voulez-vous vraiment vous déconnecter ?\');" 
                    style="color:red;">Déconnexion</a>';
        }
        ?>
    </div>
    <div class="footer-copyright">
<a href="mailto:ecoride@contact.com">ecoride@contact.com</a>        <p>&copy; 2025 ECO RIDE. Tous droits réservés</p>
    </div>
</footer>

<!-- A FAIRE :
- Mettre le lien des pages lorsqu'elles seront crées-->
