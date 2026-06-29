<?php
include __DIR__ . '/../PHP/connexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            <img src="/IMAGES/photoheader.jpg" alt="Image libre de droit covoiturage">
            <div class="slogan">
                <h1>Ensemble, roulons vers un futur plus vert</h1>
            </div>
        </div>
    </section>

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
                    <img src="/IMAGES/logo recherche.png" alt="Rechercher" class="search-icon">
                </button>
            </div>
        </form>

        <div class="search-mobile-wrapper">
            <p class="search-mobile-accroche">Bienvenue sur EcoRide 🌿<br>Vous avez un trajet en tête ?</p>
            <a href="USR-recherche_trajet.php" class="search-mobile-btn">
                Trouver un covoiturage →
            </a>
        </div>
    </section>

    <section class="founder-section">
        <div class="founder-container">
            <img src="/IMAGES/portrait jose.png" alt="Photo de José Marceau" class="founder-photo">
            <div class="founder-bio">
                <h2>José Marceau</h2>
                <p class="bio-short">
                    Originaire d'Annecy, José Marceau a fondé Eco Ride en 2022 avec une conviction forte : rendre les modes de transport durables plus visibles, accessibles et attractifs.
                </p>
                <p class="bio-full">
                    Après plusieurs années à travailler dans le secteur associatif et environnemental, il constate que de nombreuses initiatives locales peinent à se faire connaître, malgré leur impact positif. C'est ainsi qu'est née Eco Ride : une plateforme dédiée aux mobilités douces, au service de celles et ceux qui souhaitent se déplacer autrement, à leur échelle.
                    José croit en un changement progressif, porté par l'information, la confiance et des solutions concrètes. À travers Eco Ride, il souhaite créer un lien entre les citoyens, les acteurs locaux et les alternatives de transport, dans un esprit d'ouverture, de simplicité et de respect de l'environnement.
                </p>
                <button class="btn-voir-plus" id="btn-voir-plus">Voir plus ▾</button>
            </div>
        </div>
    </section>

    <section class="how-it-works responsive-section">
        <h2>Comment ça marche ?</h2>
        <div class="how-columns">
            <div class="how-col">
                <h3>🧳 En tant que passager</h3>
                <ol>
                    <li><strong>🔍 Recherchez</strong> — entrez votre trajet et votre date</li>
                    <li><strong>🧑 Choisissez</strong> — consultez les profils et avis des conducteurs</li>
                    <li><strong>✅ Réservez</strong> — payez en crédits en un clic</li>
                    <li><strong>🌿 Voyagez</strong> — validez et laissez un avis</li>
                </ol>
            </div>
            <div class="how-col">
                <h3>🚗 En tant que conducteur</h3>
                <ol>
                    <li><strong>📝 Proposez</strong> — créez votre trajet en 2 étapes</li>
                    <li><strong>👥 Accueillez</strong> — les passagers réservent automatiquement</li>
                    <li><strong>🚦 Démarrez</strong> — lancez le trajet depuis votre espace</li>
                    <li><strong>💳 Gagnez</strong> — recevez vos crédits à l'arrivée</li>
                </ol>
            </div>
        </div>
    </section>

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
            <p>Recherchez un trajet, consultez les détails et cliquez sur "Réserver". Les crédits sont débités automatiquement.</p>
        </details>
        <details>
            <summary>Comment fonctionnent les crédits ?</summary>
            <p>Les crédits sont la monnaie d'EcoRide. Vous pouvez en obtenir depuis votre espace personnel. 2 crédits sont retenus par la plateforme à chaque trajet.</p>
        </details>
        <details>
            <summary>Que se passe-t-il si le conducteur annule ?</summary>
            <p>Vous êtes notifié par e-mail et vos crédits sont remboursés automatiquement.</p>
        </details>
        <p style="margin-top: 1rem; font-family: 'Quicksand', sans-serif;">
            D'autres questions ? <a href="USR-faq.php">Consultez notre FAQ complète →</a>
        </p>
    </section>

    <section class="cta-section responsive-section">
        <h2>Prêt.e à partager la route ?</h2>
        <a href="USR-inscription.php" class="cta-btn">Créer un compte</a>
    </section>

    <script>
    const btnVoirPlus = document.getElementById('btn-voir-plus');
    const bioFull     = document.querySelector('.bio-full');
    btnVoirPlus?.addEventListener('click', () => {
        bioFull.classList.toggle('visible');
        btnVoirPlus.textContent = bioFull.classList.contains('visible') ? 'Voir moins ▴' : 'Voir plus ▾';
    });
    </script>

    <script src="../JS/USR-index.js"></script>
    <?php include __DIR__ . '/../COMPONENTS/COMP-footer.php'; ?>
</body>
</html>
