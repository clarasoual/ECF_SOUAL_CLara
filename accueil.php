<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Accueil </title>
    <link rel="stylesheet" href="ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include('COMPONENTS/header.html') ; ?>
    <section class="hero">
        <div class="hero-image">
            <img src="images/pexels-cottonbro-5329298.jpg" alt ="Image libre de droit covoiturage">
            <div class="slogan">
                <h1>Ensemble, roulons vers un futur plus vert</h1>
            </div>
        </div>
    </section>

    <section class="search-section">
        <form action ="recherche.php" method ="get">
            <div class = "search-container">
                <div class="form-group">
                <label for="departure">Je pars de ...</label>
                <input type ="text" id="departure" name="departure" placeholder="Ville de départ" required>
                </div>

                <div class="form-group">
                <label for="destination">Je vais à ...</label>
                <input type ="text" id="destination" name="destination" placeholder="Ville d'arrivée" required>
                </div>

                <div class="form-group">
                <label for ="date">Date</label>
                <input type ="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                <label for="passenger">Passagers</label>
                <input type="number" id="passenger" name="passenger" min="1" value="1" required>
                </div>
                
                <button type="submit" class="search-btn">
                    <img src="IMAGES/logo recherche.png" alt="Rechercher"  class="search-icon">
                </button>
            </div>
        </form>
    </section> 
    <section class="founder-section">
        <div class="founder-container">
        <img src="IMAGES/portrait niels.png" alt="Niels Marceau" class="founder-photo">
        <div class="founder-bio">
            <h2>Niels Marceau</h2>
            <p>
                Originaire d’Annecy, Niels Marceau a fondé Eco Ride en 2022 avec une conviction forte : rendre les modes de transport durables plus visibles, accessibles et attractifs.
                Après plusieurs années à travailler dans le secteur associatif et environnemental, il constate que de nombreuses initiatives locales peinent à se faire connaître, malgré leur impact positif. C’est ainsi qu’est née Eco Ride : une plateforme dédiée aux mobilités douces, au service de celles et ceux qui souhaitent se déplacer autrement, à leur échelle.
                Niels croit en un changement progressif, porté par l’information, la confiance et des solutions concrètes. À travers Eco Ride, il souhaite créer un lien entre les citoyens, les acteurs locaux et les alternatives de transport, dans un esprit d’ouverture, de simplicité et de respect de l’environnement.
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

    <section class="testimonials responsive-section">
        <h2>Ils ont voyagé avec Eco Ride</h2>

        <article class="testimonials">
            <p>Superbe experience, condcuteur sympa. Je reccomande à 100% !</p>
            <strong>- Julie  M.</strong>
        </article>

        <article class="testimonials">
            <p>Pratique et écologique, j'utilise Eco Ride toutes les semaines.</p>
            <strong>- Théo K.</strong>
        </article>
    </section>

    <section class="faq responsive-section">
        <h2>Questions fréquentes</h2>
        <details>
            <summary>Comment reserver un trajet ?</summary>
            <p>Utilisez le formulaire de recherche, puis cliquez sur "Reserver".</p>
        </details>
        <details>
            <summary>Dois-je créer un compte ?</summary>
            <p>Oui, pour réserver ou proposer un trajet, un compte est nécéssaire</p>
        </details>
    </section>

    <section class="cta-section responsive-section">
        <h2>Prêt.e à partager la route ?</h2>
        <a href="inscription.php" class="cta-btn">Créer un compte</a>
    </section>

   
<script src="JS/ecoride_js.js"></script> 
<?php include('COMPONENTS/footer.html'); ?>
</body>
</html>


