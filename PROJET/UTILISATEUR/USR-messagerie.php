<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-messagerie.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Menu latéral -->
<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<!-- Section principale de la messagerie -->
<section>
    <h2>Messagerie</h2>

    <div class="messaging-container">

        <!-- Liste des conversations (menu à gauche) -->
        <aside class="conversation-list">
            <h3>Conversations</h3>
            <ul>
                <li><strong>Nino C.</strong><br><small>Super, à demain alors ! Bonne soirée</small></li>
                <li><strong>Finn M.</strong><br><small>Moi : Merci pour le trajet</small></li>
            </ul>
        </aside>

        <!-- Zone d'affichage de la conversation selectionnée -->
        <section class="message-area">
            <h3>Conversation avec Nino C.</h3>

            <div class="message-thread">
                <div class="message-received">
                    <p><strong>Nino : </strong>Salut, c'est toujours bon pour toi pour le trajet de demain ?</p>
                    <small>20:16</small>
                </div>
                <div class="message-sent">
                    <p><strong>Moi : </strong>Oui c'est toujours bon pour moi !</p>
                    <small>20:18</small>
                </div>
                <div class="message-received">
                    <p><strong>Nino : </strong>Super, à demain alors ! Bonne soirée</p>
                    <small>20:26</small>
                </div>
            </div>

            <!-- Zone de saisie d'un nouveau message -->
            <div class="send-message-area">
                <label for="new-message">Nouveau message :</label><br>
                <textarea id="new-message" name="new-message" rows="3" cols="40" placeholder="Écrire un message..."></textarea>
                <button>Envoyer</button> <!-- Ajouter type button ou submit -->
            </div>
        </section>
    </div>
</section>
<script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A FAIRE
- CSS
- Mettre l'heure et la date de la conversation
- Mettre msg envoyé a droite
- Aligner avec le menu -->