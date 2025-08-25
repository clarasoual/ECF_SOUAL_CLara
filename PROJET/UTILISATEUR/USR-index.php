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
    <!-- Header commun -->
    <?php include('../COMPONENTS/header.html') ; ?>

    <!-- Section accueil avec image et slogan -->
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
        <form action ="recherche.php" method ="get">
            <div class = "search-container">

                <!-- Champ de départ -->
                <div class="form-group">
                <label for="departure">Je pars de ...</label>
                <input type ="text" id="departure" name="departure" placeholder="Ville de départ" required>
                </div>

                <!-- Champ de destination -->
                <div class="form-group">
                <label for="destination">Je vais à ...</label>
                <input type ="text" id="destination" name="destination" placeholder="Ville d'arrivée" required>
                </div>

                <!-- Champ de date -->
                <div class="form-group">
                <label for ="date">Date</label>
                <input type ="date" id="date" name="date" required>
                </div>

                <!-- Champ du nombre de passagers -->
                <div class="form-group">
                <label for="passenger">Passagers</label>
                <input type="number" id="passenger" name="passenger" min="1" value="1" required>
                </div>

                <!-- Bouton recherche avec icône -->
                <button type="submit" class="search-btn">
                    <img src="../../IMAGES/logo recherche.png" alt="Rechercher"  class="search-icon">
                </button>
            </div>
        </form>
    </section> 

    <!-- Présentation du fondateur (peut-être à déplacer) -->
    <section class="founder-section">
        <div class="founder-container">
        <img src="../../IMAGES/portrait jose.png" alt="Photo de José Marceau" class="founder-photo">
        <div class="founder-bio">
            <h2>José Marceau</h2>
            <p>
                Originaire d’Annecy, José Marceau a fondé Eco Ride en 2022 avec une conviction forte : rendre les modes de transport durables plus visibles, accessibles et attractifs.
                Après plusieurs années à travailler dans le secteur associatif et environnemental, il constate que de nombreuses initiatives locales peinent à se faire connaître, malgré leur impact positif. C’est ainsi qu’est née Eco Ride : une plateforme dédiée aux mobilités douces, au service de celles et ceux qui souhaitent se déplacer autrement, à leur échelle.
                José croit en un changement progressif, porté par l’information, la confiance et des solutions concrètes. À travers Eco Ride, il souhaite créer un lien entre les citoyens, les acteurs locaux et les alternatives de transport, dans un esprit d’ouverture, de simplicité et de respect de l’environnement.
            </p>
        </div>
        </div>

    </section>

    <!-- Explication du fonctionnement -->
    <section class="how-it-works responsive-section">
        <h2>Comment ça marche ?</h2>
        <ol>
            <li>Recherchez votre trajet</li>
            <li>Choisissez votre conducteur</li>
            <li>Réservez et covoiturez !</li>
        </ol>
    </section>

    <!-- Témoignages des utilisateurs -->
    <section class="testimonials responsive-section">
        <h2>Ils ont voyagé avec Eco Ride</h2>

        <article class="testimonial">
            <p>Superbe expérience, conducteur sympa. Je recommande à 100 % !</p>
            <strong>- Nina R.</strong>
        </article>

        <article class="testimonial">
            <p>Nino est très accueillant et prudent. Pratique et écologique, j'utilise Eco Ride toutes les semaines.</p>
            <strong>- Théo K.</strong>
        </article>
    </section>

    <!-- Foire aux questions -->
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

        <!-- Rajouter un lien vers la FAQ complète -->
    </section>

    <!-- Appel à l'action pour créer un compte -->
    <section class="cta-section responsive-section">
        <h2>Prêt.e à partager la route ?</h2>
        <a href="inscription.php" class="cta-btn">Créer un compte</a>
    </section>

<!-- Footer commun -->
<?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A faire
 - Loupe du formulaire à aligner
 - Logo plus lisible, à refaire
 - Comment ça marche à améliorer
 - Aérer le footer
 - Recadrer la photo
 - Responsive !!
 - Relier la photo de profil a l'espace mon compte OU a la connexion/inscription
 - Relier la FAQ à la page dédiée
 - Relier et créer mentions légales
 - Relier et créer Réglement de la plateforme
 - Relier et créer contact
 - Relier et créer les réseaux sociaux
 - Créer newsletter ? 
 - Peut être faire une section à propos pour José
 - Mettre à la place une description de Eco Ride, en incluant le comment ça marche ?
 - Faire un composant pour le formulaire
 -->


