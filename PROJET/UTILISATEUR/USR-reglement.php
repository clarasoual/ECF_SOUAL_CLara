<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Règlement de la plateforme - Eco Ride</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-reglement.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.php'); ?>

<main>
<div class="reglement-wrapper">

    <!-- HEADER -->
    <div class="reglement-header">
        <h1>Règlement de la plateforme</h1>
        <p>Ces règles s'appliquent à tous les membres d'Eco Ride — conducteurs comme passagers. Leur respect garantit une expérience de covoiturage sûre, agréable et équitable pour tous.</p>
    </div>

    <!-- GRILLE -->
    <div class="reglement-grid">

        <!-- CONDUCTEURS -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">🚗</span>
                <h2>Règles pour les conducteurs</h2>
            </div>
            <ul class="reglement-liste">
                <li>Avoir un permis de conduire valide et un véhicule assuré.</li>
                <li>Respecter le code de la route en toutes circonstances.</li>
                <li>Renseigner des informations exactes sur le trajet (départ, arrivée, heure, places disponibles).</li>
                <li>Être ponctuel et prévenir les passagers en cas de retard ou d'annulation.</li>
                <li>Ne pas proposer un prix supérieur au partage réel des frais.</li>
                <li>Informer les passagers des règles à bord (animaux, musique, fumeur).</li>
                <li>Ne pas conduire sous l'influence d'alcool ou de substances.</li>
                <li>Marquer le trajet comme terminé une fois arrivé à destination.</li>
            </ul>
        </div>

        <!-- PASSAGERS -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">🧳</span>
                <h2>Règles pour les passagers</h2>
            </div>
            <ul class="reglement-liste">
                <li>Être présent au point de rendez-vous à l'heure convenue.</li>
                <li>Prévenir le conducteur en cas d'annulation dans les meilleurs délais.</li>
                <li>Respecter les règles définies par le conducteur à bord.</li>
                <li>Ne pas emporter de bagages encombrants sans accord préalable.</li>
                <li>Se comporter avec courtoisie et respect envers le conducteur.</li>
                <li>Laisser un avis honnête après chaque trajet.</li>
            </ul>
        </div>

        <!-- CRÉDITS -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">💳</span>
                <h2>Système de crédits</h2>
            </div>
            <ul class="reglement-liste">
                <li>Les crédits sont la monnaie interne de la plateforme.</li>
                <li>Le passager paie en crédits à la réservation d'un trajet.</li>
                <li>Le conducteur reçoit ses crédits une fois le trajet terminé et validé.</li>
                <li>Eco Ride perçoit 2 crédits par trajet effectué pour assurer le fonctionnement de la plateforme.</li>
                <li>Les crédits ne sont pas remboursables en argent réel.</li>
                <li>En cas de litige, les crédits peuvent être temporairement bloqués le temps de l'instruction.</li>
            </ul>
        </div>

        <!-- AVIS -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">⭐</span>
                <h2>Avis et modération</h2>
            </div>
            <ul class="reglement-liste">
                <li>Chaque trajet terminé donne lieu à la possibilité de laisser un avis.</li>
                <li>Les avis doivent être honnêtes, respectueux et basés sur l'expérience réelle.</li>
                <li>Les avis injurieux, diffamatoires ou faux seront refusés.</li>
                <li>Tout avis est soumis à validation par un employé Eco Ride avant publication.</li>
                <li>Un trajet peut être signalé si quelque chose s'est mal passé — un employé prendra en charge le dossier.</li>
            </ul>
        </div>

        <!-- COMPORTEMENT -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">🤝</span>
                <h2>Comportement & respect</h2>
            </div>
            <ul class="reglement-liste">
                <li>Tout comportement discriminatoire, harcelant ou violent est strictement interdit.</li>
                <li>Les propos offensants ou irrespectueux envers un autre membre entraîneront une suspension du compte.</li>
                <li>Les informations personnelles des autres membres sont confidentielles et ne doivent pas être partagées.</li>
                <li>Eco Ride se réserve le droit de suspendre tout compte ne respectant pas ces règles.</li>
            </ul>
        </div>

        <!-- SANCTIONS -->
        <div class="reglement-bloc">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">⚠️</span>
                <h2>Sanctions</h2>
            </div>
            <ul class="reglement-liste">
                <li>Un avertissement peut être émis en cas de première infraction mineure.</li>
                <li>La suspension temporaire du compte peut être appliquée en cas de récidive.</li>
                <li>Une suspension définitive peut être prononcée en cas d'infraction grave.</li>
                <li>Toute décision de suspension peut faire l'objet d'un recours via notre page contact.</li>
            </ul>
        </div>

        <!-- CONTACT pleine largeur -->
        <div class="reglement-bloc reglement-bloc-full">
            <div class="reglement-bloc-header">
                <span class="reglement-icon">📩</span>
                <h2>Une question sur le règlement ?</h2>
            </div>
            <p class="reglement-contact-text">
                Si vous avez des questions, souhaitez signaler un comportement ou contester une décision, notre équipe est disponible via la page contact.
            </p>
            <a href="contacterecoride.php" class="reglement-btn-contact">Nous contacter</a>
        </div>

    </div>
</div>
</main>

<?php include('../COMPONENTS/COMP-footer.php'); ?>
</body>
</html>

