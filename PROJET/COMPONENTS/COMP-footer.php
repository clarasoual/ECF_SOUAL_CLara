<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../PHP/auth.php');
?>

<footer>
    <div class="footer-container">
        <a href="../UTILISATEUR/USR-mentions-legales.php">Mentions légales</a>
        <a href="../UTILISATEUR/USR-reglement.php">Règlement de la plateforme</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../UTILISATEUR/USR-deconnexion.php"
               onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');"
               style="color:red;">Déconnexion</a>
        <?php endif; ?>
    </div>
    <div class="footer-copyright">
        <a href="mailto:ecoride@contact.com">ecoride@contact.com</a>
        <p>&copy; 2025 ECO RIDE. Tous droits réservés</p>
    </div>
</footer>
