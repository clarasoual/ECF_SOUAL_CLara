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

    <div class="container-messagerie">
        <aside class="liste-conversations">
            <h3>Conversations</h3>
            <ul>
                <li><strong>Marie D.</strong><br><small>A tout à l'heure !</small></li>
                <li><strong>Julien S.</strong><br><small>Merci pour le trajet</small></li>
                <li><strong>Nina R.</strong><br><small>Ok pour 8h </small></li>
            </ul>
        </aside>

        <section class="zone-message">
            <h3>Conversation avec Marie D.</h3>

            <div class="fil-message">
                <div class="message-recu">
                    <p><strong>Marie :</strong>Salut, c'est toujours bon pour toi pour le trajet de demain ?</p>
                    <small>20:16</small>
                </div>
                <div class="message-envoye">
                    <p><strong>Moi :</strong>Oui c'est toujours bon pour moi !</p>
                    <small>20:18</small>
                </div>
                <div class="message-recu">
                    <p><strong>Marie :</strong>Super, à demain alors ! Bonne soirée</p>
                    <small>20:26</small>
                </div>
            </div>

            <div class="zone-envoi">
                <label for="nouveau-message">Nouveau message :</label><br>
                <textarea id="nouveau-message" name="nouveau-message" rows="3" cols="40" placeholder="Ecrire un message..."></textarea>
                <button>Envoyer</button>
            </div>
        </section>
    </div>
</section>
<script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>