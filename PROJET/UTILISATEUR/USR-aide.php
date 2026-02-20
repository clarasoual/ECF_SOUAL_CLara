<?php
include('../PHP/auth.php'); // Démarre la session et charge les fonctions
requireLogin(); // Redirige si l'utilisateur n'est pas connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte - Aide </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
            <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-aide.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<!-- Header commun -->
<?php include('../COMPONENTS/COMP-header.html') ; ?>
<main>
<!-- Menu latéral -->
<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<section>
    <h2>Aide & Support</h2>

    <!-- Introduction de la section d'aide -->
    <p>Bienvenue dans la section d'aide. Voici les réponses aux questions les plus fréquentes. Si vous ne trouvez pas votre réponse, vous pouvez nous contacter.</p>

<!-- Bloc FAQ -->
<div class="faq-section">
    <h3>F.A.Q.</h3>
    <h3>Questions fréquentes</h3>

    <div class="faq-question">
        <p><strong>Comment modifier mon mot de passe ?</strong></p>
        <p>Allez dans "Mon profil" puis cliquez sur "Modifier le mot de passe".</p>
    </div>

    <div class="faq-question">
        <p><strong>Comment supprimer le trajet ?</strong></p>
        <p>Rendez-vous dans la section "Mes trajets", cliquez sur le trajet concerné puis sélectionnez "Supprimer".</p>
    </div>

    <div class="faq-question">
        <p><strong>Comment contacter un autre utilisateur ?</strong></p>
        <p>Utilisez la messagerie intégrée dans votre espace personnel.</p>
    </div>
</div>

<!-- Section contact -->
<div class="contact-section">
    <h3>Nous contacter</h3>
    <p>Vous n'avez pas trouvé votre réponse ? Laissez-nous un message :</p>
    <form>
        <label for="email">Votre email :</label><br>
        <input type="email" id="email" name="email" placeholder="exemple@email.com"><br><br>

        <label for="message">Votre message :</label><br>
        <textarea id="message" name="message" rows="4" cols="50" placeholder="Expliquez votre problème..."></textarea><br><br>

        <button type="submit">Envoyer</button>
    </form>
</div>
</section>

</main>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/COMP-footer.html'); ?>
    <script src="../JS/USR-aide.js"></script>

</body>
</html>

<!-- A FAIRE 
- Faire des sections ?
- Recherche mot clé ?
 - Ajouter des questions à la FAQ
 -->
