<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Détails trajet </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
    <title>Détails du trajet</title>
</head>
<body>

<?php include('COMPONENTS/header.html') ; ?>

    <div class="page-header">
        <div class="date-title">
            <h1>Samedi 12 mai 2025</h1>
        </div>
        <div class="reservation-btn">
            <button type="button" class="btn-reservation">
                Demande de réservation
            </button>
        </div>
    </div>

    <section class="plan-section">
        <div class="plan-town">
            <h2>Plan schématique du trajet</h2>
            <p>Ville de départ : Bordeaux</p>
            <p>Ville d'arrivée : Périgueux</p>
            <p>Arrêt 1 : Libourne</p>
            <p>Arrêt 2 : Saint Astier</p>
            <img src="" alt="Plan du trajet">
        </div>
        <div class="btn-duration">
            <button type="button">
                Demander la durée max. au chauffeur
            </button>
        </div>
    </section>

    <section class="driver">
    <h2>Conducteur</h2>
    <div class="driver-infos">
        <img src="" alt="Photo de Thierry">
        <div>
            <p><strong>Pseudo :</strong>Thierry</p>
            <p><strong>Nombre de trajets :</strong>124</p>
            <p><strong>Note moyenne :</strong>4.7/5</p>
        </div>
    </div>
    <div class="preferences">
        <h3>Préférences</h3>
        <ul>
            <li>Non fumeur</li>
            <li>Animaux acceptés</li>
        </ul>
    </div>

    <div class="vehicle">
        <h3>Véhicule</h3>
        <p><strong>Marque</strong>Renault</p>
        <p><strong>Modèle :</strong>Clio</p>
        <p><strong>Couleur :</strong>Bleu</p>
        <p><strong>Système :</strong>Essence</p>
    </div>
    </section>

    <section class="passenger">
        <h2>Passagers</h2>

        <article class="passenger">
            <img src="" alt="Photo de Claire">
            <div>
                <p><strong>Pseudo :</strong>Claire</p>
                <p><em>De l'arrêt 1 à Périgueux</em></p>
            </div>
        </article>

        <article class="passenger">
            <img src="" alt="Photo de Marc">
            <div>
                <p><strong>Pseudo :</strong>Marc</p>
                <p><em>De l'arrêt Bordeaux à Périgueux</em></p>
            </div>
        </article>
    </section>

    <section class="driver-reviews">
        <h2>Ce que les passagers pensent de Thierry</h2>

        <article class="reviews">
            <div class="author-review">
                <img src="" alt="Photo de sophie">
                <p><strong>Sophie</strong></p>
                <p><strong>Note :</strong>5 / 5</p>
            </div>
            <h3>Très bon conducteur</h3>
            <p>Thierry est toujours ponctuel et très sympa, je reccomande !</p>
        </article>

        <article class="review">
            <div class="author-review">
                <img src="" alt="Photo de Lucas">
                <p><strong>Lucas</strong></p>
                <p><strong>Note :</strong>4.6 / 5</p>
            </div>
            <h3>Conduite agréable</h3>
            <p>Trajet confortable, Thierry est très respectueux et à l'écoute de ses passagers.</p>
        </article>
    </section>

   
    <script src="JS/ecoride_js.js"></script>
<?php include('COMPONENTS/footer.html'); ?>
</body>
</html>