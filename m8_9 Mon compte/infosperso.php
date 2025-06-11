<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mon compte </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<?php include('COMPONENTS/menu-profil.html'); ?>


<div>
    <main class="profile-content">
        <section class="profil-header">
            <img src="" alt="Photo de profil" class="profil-photo">
            <h2>Pseudo Utilisateur</h2>
            <button>Modifier mes informations</button>
        </section>

        <section class="profile-verification">
            <h3>Vérifier le profil</h3>
            <ul>
                <li>Ajouter une carte d'identité</li>
                <li>Confirmer l'adresse mail</li>
                <li>Confirmer le numéro de téléphone</li>
            </ul>
        </section>

        <section class="user-role">
            <h3>Vous</h3>
            <label for="role-passenger"><input type="radio" name="role" value="passenger"> Passager</label><br>
            <label for="role-driver"><input type="radio" name="role" value="driver"> Conducteur</label><br>
            <label for="role-both"><input type="radio" name="role" value="both"> Passager-conducteur</label><br>

            <div class="alert-message">
                Merci de completer vos informations conducteur pour activer ce rôle !
            </div>
        </section>

        <section class="bio">
            <h3>Biographie</h3>
            <textarea rows="5" cols="40" placeholder="Parle un peu de toi..."></textarea>
        </section>

        <section class="credit-balance">
            <h3>Crédit</h3>
            <p>Solde : 10 crédits</p>
            <p>Besoin de crédit ? <a href="credit.html">Contacte Ecp Ride</a></p>
        </section>

    </main>
</div>
    <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>