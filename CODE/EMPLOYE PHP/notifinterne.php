<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Espace employé - Notifications internes </title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/headeremploye.html') ; ?>

<?php include('../COMPONENTS/menuemploye.html') ; ?>

<main>
    <section>
        <h2>Fil d'actualité interne</h2>

    <div class="notifications-feed">
        <article class="notification-item">
            <h3>Mise à jour du système</h3>
            <p>Le site sera en maintenance ce soir de 22h à 23h.</p>
            <small>Posté par administrateur - 10:00</small>
        </article>

        <article class="notification-item">
            <h3>Nouvelle fonctionnalité</h3>
            <p>La messagerie interne permet désormais d'envoyer des fichiers.</p>
            <small>Posté par Sophie - Employée - 14:23</small>
        </article>

        <article class="notification-item">
            <h3>Rappel</h3>
            <p>Pensez à mettre à jour vos photos de profil avant la fin du mois.</p>
            <small>Posté par Administrateur - 17:55</small>
        </article>

        <section class="post-notification">
            <h3>Publier une notification</h3>
            <form method="post">
                <label for="notif-title">Titre :</label><br>
                <input type="text" id="notif-title" name="notif-title" size="40" required><br><br>

                <label for="notif-content">Message :</label><br>
                <textarea id="notif-content" name="notif-content" rows="4" cols="50" required></textarea><br><br>

                <button type="submit">Publier</button>
            </form>
        </section>
    </div>
</main>
</body>
</html>