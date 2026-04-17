<?php
include __DIR__ . '/../PHP/connexion.php';

// Récupérer les 2 derniers avis validés pour les témoignages
$stmt = $bdd->prepare("
    SELECT a.commentaire, a.note, u.prenom, u.nom
    FROM avis a
    JOIN utilisateurs u ON u.id = a.id_auteur
    WHERE a.statut = 'valide'
      AND a.commentaire IS NOT NULL
      AND a.commentaire != ''
    ORDER BY a.date_creation DESC
    LIMIT 2
");
$stmt->execute();
$temoignages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-index.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../COMPONENTS/COMP-header.php'; ?>

    <section class="hero">
        <div class="hero-image">
            <img src="../../IMAGES/photoheader.jpg" alt="Image libre de droit covoiturage">
            <div class="slogan">
                <h1>Ensemble, roulons vers un futur plus vert</h1>
            </div>
        </div>
    </section>

    <!-- Formulaire de recherche -->
    <section class="search-section">
        <form action="USR-recherche_trajet.php" method="get" novalidate>
            <div class="search-container">
                <div class="form-group">
                    <label for="departure">Je pars de ...</label>
                    <input type="text" id="departure" name="departure" placeholder="Ville de départ">
                </div>
                <div class="form-group">
                    <label for="destination">Je vais à ...</label>
                    <input type="text" id="destination" name="destination" placeholder="Ville d'arrivée">
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date">
                </div>
                <div class="form-group">
                    <label for="passenger">Passagers</label>
                    <input type="number" id="passenger" name="passenger" min="1" max="8" value="1">
                </div>
                <button type="submit" class="search-btn">
                    <img src="../../IMAGES/logo recherche.png" alt="Rechercher" class="search-icon">
                </button>
            </div>
        </form>
    </section>

    <section class="founder-section">
        <div class="founder-container">
            <img src="../../IMAGES/portrait jose.png" alt="Photo de José Marceau" class="founder-photo">
            <div class="founder-bio">
                <h2>José Marceau</h2>
                <p>
                    Originaire d'Annecy, José Marceau a fondé Eco Ride en 2022 avec une conviction forte : rendre les modes de transport durables plus visibles, accessibles et attractifs.
                    Après plusieurs années à travailler dans le secteur associatif et environnemental, il constate que de nombreuses initiatives locales peinent à se faire connaître, malgré leur impact positif. C'est ainsi qu'est née Eco Ride : une plateforme dédiée aux mobilités douces, au service de celles et ceux qui souhaitent se déplacer autrement, à leur échelle.
                    José croit en un changement progressif, porté par l'information, la confiance et des solutions concrètes. À travers Eco Ride, il souhaite créer un lien entre les citoyens, les acteurs locaux et les alternatives de transport, dans un esprit d'ouverture, de simplicité et de respect de l'environnement.
                </p>
            </div>
        </div>
    </section>

    <section class="how-it-works responsive-section">
        <h2>Comment ça marche ?</h2>
        <ol>
            <li>Recherchez votre trajet</li>
            <li>Choisissez votre conducteur</li>
            <li>Réservez et covoiturez !</li>
        </ol>
    </section>

    <!-- Témoignages dynamiques depuis la BDD -->
    <section class="testimonials responsive-section">
        <h2>Ils ont voyagé avec Eco Ride</h2>
        <?php if (!empty($temoignages)): ?>
            <?php foreach ($temoignages as $t): ?>
                <article class="testimonial">
                    <p><?= htmlspecialchars($t['commentaire'], ENT_QUOTES, 'UTF-8') ?></p>
                    <strong>- <?= htmlspecialchars($t['prenom'] . ' ' . substr($t['nom'], 0, 1) . '.', ENT_QUOTES, 'UTF-8') ?></strong>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <article class="testimonial">
                <p>Soyez le premier à partager votre expérience EcoRide !</p>
            </article>
        <?php endif; ?>
    </section>

    <section class="faq responsive-section">
        <h2>Questions fréquentes</h2>
        <details>
            <summary>Comment réserver un trajet ?</summary>
            <p>Utilisez le formulaire de recherche, puis cliquez sur "Réserver".</p>
        </details>
        <details>
            <summary>Dois-je créer un compte ?</summary>
            <p>Oui, pour réserver ou proposer un trajet, un compte est nécessaire.</p>
        </details>
    </section>

    <section class="cta-section responsive-section">
        <h2>Prêt.e à partager la route ?</h2>
        <a href="USR-inscription.php" class="cta-btn">Créer un compte</a>
    </section>

    <script src="../JS/USR-index.js"></script>

    <?php include __DIR__ . '/../COMPONENTS/COMP-footer.php'; ?>
</body>
</html>