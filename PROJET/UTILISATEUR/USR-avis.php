<?php
include('../PHP/auth.php'); // Démarre la session et charge les fonctions
requireLogin(); // Redirige si l'utilisateur n'est pas connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte - Avis</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-avis.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/COMP-header.html'); ?>

<main>
    <!-- Menu latéral -->
    <div class="menu-column">
        <?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>
    </div>

    <!-- Contenu principal -->
    <div class="content-column">
        <section class="reviews-section">
            <h2>Mes avis</h2>

            <!-- Container pour les boutons et le tri -->
            <div class="btn-container"></div>

            <!-- Avis reçus -->
            <div id="reviews-received">
                <h3>Avis reçus</h3>

                <div class="review">
                    <p><strong>Date :</strong> 12/05/2025</p>
                    <p><strong>Trajet :</strong> Toulouse - Bordeaux</p>
                    <p><strong>Rôle :</strong> Conducteur</p>
                    <p><strong>Note :</strong> 4/5</p>
                    <p><strong>Commentaire :</strong> Trajet très agréable, merci.</p>
                </div>

                <div class="review">
                    <p><strong>Date :</strong> 28/04/2025</p>
                    <p><strong>Trajet :</strong> Nantes - Rennes</p>
                    <p><strong>Rôle :</strong> Passager</p>
                    <p><strong>Note :</strong> 4/5</p>
                    <p><strong>Commentaire :</strong> Très ponctuelle et sympa !</p>
                </div>
            </div>

            <!-- Avis donnés -->
            <div id="reviews-given">
                <h3>Avis donnés</h3>

                <div class="review">
                    <p><strong>Date :</strong> 19/04/2025</p>
                    <p><strong>Trajet :</strong> Lyon - Grenoble</p>
                    <p><strong>Rôle :</strong> Passager</p>
                    <p><strong>Note :</strong> 3/5</p>
                    <p><strong>Commentaire :</strong> Chauffeur correct, un peu en retard.</p>
                </div>

                <div class="review">
                    <p><strong>Date :</strong> 03/04/2025</p>
                    <p><strong>Trajet :</strong> Lille - Paris</p>
                    <p><strong>Rôle :</strong> Conducteur</p>
                    <p><strong>Note :</strong> 5/5</p>
                    <p><strong>Commentaire :</strong> Très bon passager, respectueux et à l'heure.</p>
                </div>
            </div>

            <!-- Diagramme des notes -->
            <div class="chart-container"></div>
        </section>
    </div>
</main>

<!-- Footer commun -->
<?php include('../COMPONENTS/COMP-footer.html'); ?>
<script src="../JS/USR-avis.js"></script>
</body>
</html>