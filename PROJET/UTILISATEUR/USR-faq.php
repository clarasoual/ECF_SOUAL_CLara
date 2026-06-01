<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Aide</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-aide.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
<section>
    <h2>Aide & Support</h2>
    <p>Bienvenue dans la section d'aide. Voici les réponses aux questions les plus fréquentes. Si vous ne trouvez pas votre réponse, vous pouvez <a href="contacterecoride.php">nous contacter</a>.</p>

    <div class="faq-section">
        <h3>Questions fréquentes</h3>

        <!-- COMPTE -->
        <p class="faq-category">Mon compte</p>

        <div class="faq-question">
            <p><strong>Comment modifier mon mot de passe ?</strong></p>
            <p>Allez dans "Mon profil" puis cliquez sur "Modifier le mot de passe".</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment modifier mes informations personnelles ?</strong></p>
            <p>Rendez-vous dans "Mon profil" pour modifier votre prénom, nom, email ou photo de profil.</p>
        </div>

        <div class="faq-question">
            <p><strong>Mon compte a été suspendu, que faire ?</strong></p>
            <p>Si votre compte est suspendu, contactez notre support à <a href="mailto:support@ecoride.fr">support@ecoride.fr</a>. Un employé étudiera votre situation.</p>
        </div>

        <!-- TRAJETS -->
        <p class="faq-category">Trajets</p>

        <div class="faq-question">
            <p><strong>Comment supprimer un trajet ?</strong></p>
            <p>Rendez-vous dans "Mes trajets", cliquez sur le trajet concerné puis sélectionnez "Supprimer".</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment proposer un trajet ?</strong></p>
            <p>Depuis votre espace personnel, cliquez sur "Proposer un trajet" et renseignez les informations demandées (départ, arrivée, date, nombre de places).</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment rejoindre un trajet en tant que passager ?</strong></p>
            <p>Recherchez un trajet depuis la page d'accueil, sélectionnez celui qui vous convient et cliquez sur "Réserver". Votre demande sera soumise au conducteur.</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment annuler ma réservation ?</strong></p>
            <p>Allez dans "Mes trajets", retrouvez la réservation concernée et cliquez sur "Annuler". Notez que des conditions peuvent s'appliquer selon le délai d'annulation.</p>
        </div>

        <!-- CRÉDITS -->
        <p class="faq-category">Crédits</p>

        <div class="faq-question">
            <p><strong>Comment fonctionnent les crédits ?</strong></p>
            <p>Les crédits sont la monnaie de la plateforme. En tant que passager, vous payez des crédits pour rejoindre un trajet. En tant que conducteur, vous en recevez à la fin du trajet.</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment obtenir des crédits ?</strong></p>
            <p>Vous pouvez faire une demande de crédits depuis votre espace personnel. Un employé traitera votre demande dans les plus brefs délais.</p>
        </div>

        <div class="faq-question">
            <p><strong>Pourquoi mes crédits n'ont-ils pas été versés après un trajet ?</strong></p>
            <p>Les crédits sont versés une fois le trajet marqué comme terminé et validé. Si le trajet a fait l'objet d'un signalement, le versement peut être temporairement suspendu le temps de l'instruction.</p>
        </div>

        <!-- AVIS & SIGNALEMENTS -->
        <p class="faq-category">Avis & Signalements</p>

        <div class="faq-question">
            <p><strong>Comment laisser un avis sur un conducteur ?</strong></p>
            <p>Après chaque trajet terminé, vous serez invité à laisser un avis. Vous pouvez également le faire depuis "Mes trajets" en cliquant sur "Laisser un avis".</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment signaler un trajet problématique ?</strong></p>
            <p>Depuis la page de dépôt d'avis, une section "Signaler un problème" vous permet de décrire la situation. Un employé prendra contact avec le conducteur.</p>
        </div>

        <div class="faq-question">
            <p><strong>Que se passe-t-il après un signalement ?</strong></p>
            <p>Un employé Eco Ride examine le signalement. Il peut contacter les deux parties et décider de bloquer ou débloquer les crédits du trajet concerné.</p>
        </div>

        <!-- CONTACT -->
        <p class="faq-category">Contact</p>

        <div class="faq-question">
            <p><strong>Comment contacter le support ?</strong></p>
            <p>Utilisez notre <a href="contacterecoride.php">page contact</a> ou écrivez-nous directement à <a href="mailto:support@ecoride.fr">support@ecoride.fr</a>.</p>
        </div>

        <div class="faq-question">
            <p><strong>Comment contacter un autre utilisateur ?</strong></p>
            <p>Utilisez la messagerie intégrée dans votre espace personnel.</p>
        </div>

    </div>
</section>
</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
<script src="../JS/USR-aide.js"></script>

</body>
</html>