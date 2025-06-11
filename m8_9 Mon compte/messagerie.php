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
    <h2>Messagerie</h2>

    <div class="messaging-container">
        <aside class="conversation-list">
            <h3>Conversations</h3>
            <ul>
                <li><strong>Marie D.</strong><br><small>A tout à l'heure !</small></li>
                <li><strong>Julien S.</strong><br><small>Merci pour le trajet</small></li>
                <li><strong>Nina R.</strong><br><small>Ok pour 8h </small></li>
            </ul>
        </aside>

        <section class="message-area">
            <h3>Conversation avec Marie D.</h3>

            <div class="message-thread">
                <div class="message-received">
                    <p><strong>Marie :</strong>Salut, c'est toujours bon pour toi pour le trajet de demain ?</p>
                    <small>20:16</small>
                </div>
                <div class="message-sent">
                    <p><strong>Moi :</strong>Oui c'est toujours bon pour moi !</p>
                    <small>20:18</small>
                </div>
                <div class="message-received">
                    <p><strong>Marie :</strong>Super, à demain alors ! Bonne soirée</p>
                    <small>20:26</small>
                </div>
            </div>

            <div class="send-message-area">
                <label for="new-message">Nouveau message :</label><br>
                <textarea id="new-message" name="new-message" rows="3" cols="40" placeholder="Ecrire un message..."></textarea>
                <button>Envoyer</button>
            </div>
        </section>
    </div>
</section>
<script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>