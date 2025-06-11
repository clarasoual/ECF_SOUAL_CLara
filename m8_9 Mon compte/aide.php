<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<?php include('COMPONENTS/menu-profil.html'); ?>

<section>
    <h2>Aide & Support</h2>
    <p>Bienvenue dans la section d'aide. Voici les réponses aux questions les plus fréquentes. Si vous ne trouver pas votre réponse, vous pouvez nous contacter.</p>

<div class="faq-section">
    <h3>F.A.Q.</h3>
    <h3>Questions fréquentes</h3>

    <div class="faq-question">
        <p><strong>Comment modifier mon mot de passe ?</strong></p>
        <p>Allez dans "Mon profil" puis cliquez sur "Modifier le mot de passe".</p>
    </div>

    <div class="faq-question">
        <p><strong>Comment supprimer le trajet ?</strong></p>
        <p>Rendez-vous dans la sections "Mes trajets", cliquez sur le trajet concerné puis sélectionnez "Supprimer".</p>
    </div>

    <div class="faq-question">
        <p><strong>Comment contacter un autre utilisateur ?</strong></p>
        <p>Utilisez la messagerie intégrée dans votre espace personnel.</p>
    </div>
</div>

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