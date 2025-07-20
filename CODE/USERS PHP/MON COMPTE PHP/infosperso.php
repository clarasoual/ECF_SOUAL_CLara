<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte - Informations personnelles </title>
    <link rel="stylesheet" href="../../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<!-- Menu latéral -->
<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<!-- Contenu principal -->
<div>
    <main class="profile-content">

        <!-- Section en-tête du profil -->
        <section class="profil-header">
            <img src="" alt="Photo de profil" class="profil-photo">
            <h2>Pseudo Utilisateur</h2>
            <button>Modifier mes informations</button>
        </section>

        <!-- Section vérification du profil -->
        <section class="profile-verification">
            <h3>Vérifier le profil</h3>
            <ul>
                <li>Ajouter une carte d'identité</li>
                <li>Confirmer l'adresse mail</li>
                <li>Confirmer le numéro de téléphone</li>
            </ul>
        </section>

        <!-- Section choix du rôle du choix utilisateur -->
        <section class="user-role">
            <h3>Vous</h3>
            <label for="role-passenger"><input type="radio" name="role" value="passenger"> Passager</label><br>
            <label for="role-driver"><input type="radio" name="role" value="driver"> Conducteur</label><br>
            <label for="role-both"><input type="radio" name="role" value="both"> Passager-conducteur</label><br>

            <div class="alert-message">
                Merci de completer vos informations conducteur pour activer ce rôle !
            </div>
        </section>

        <!-- Section biographie -->
        <section class="bio">
            <h3>Biographie</h3>
            <textarea rows="5" cols="40" placeholder="Parle un peu de toi..."></textarea>
        </section>

        <!-- Section solde crédit -->
        <section class="credit-balance">
            <h3>Crédit</h3>
            <p>Solde : 10 crédits</p>
            <p>Besoin de crédit ? <a href="credit.html">Contacte Ecp Ride</a></p>
        </section>

    </main>
</div>
    <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../../COMPONENTS/footer.html'); ?>
</body>
</html>