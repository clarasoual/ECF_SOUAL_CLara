<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Espace employé - Messagerie </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
        <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-messagerie.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/headeremploye.html') ; ?>

<?php include('../COMPONENTS/menuemploye.html') ; ?>

<main>
    <section>
        <h2>Messagerie employé</h2>
        <div class="messaging-container">
            <aside class="conversations-list">
                <h3>Conversations</h3>
                <ul>
                    <li><strong>Administrateur</strong><br><small>Merci, nous allons corriger cela rapidement.</small></li>
                    <li><strong>Employé</strong><br><small>Moi : Je te tiens au courant.</small></li>
                </ul>
            </aside>

            <section class="message-area">
                <h3>Conversation avec Administrateur</h3>
                <div class="message-thread">
                    <div class="message-received">
                        <p><strong>Admin : </strong>Merci, nous allons corriger cela rapidement.</p>
                        <small>9:50</small>
                    </div>
                </div>

                <div class="send-message-area">
                    <label for="new-message">Nouveau message :</label><br>
                    <textarea id="new-message" name="new-message" rows="3" cols="40" placeholder="Écrire un message..."></textarea>
                    <button type="button">Envoyer</button>
                </div>
            </section>
        </div>
    </section>

</main>
</body>
</html>

