<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Création de votre compte - ECO RIDE </title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html') ; ?>
    <main>

        <!-- Formulaire d'inscription -->
        <div class="account-creation-container">
            <h1>Création de votre compte</h1>
            <p class="required-notes">* Champs obligatoires</p>

            <form action="#" method="POST" class="form-inscription">

                <fieldset class="gender">
                    <legend>Civilité *</legend>
                    <label><input type="radio" name="gender" value="monsieur" required> Monsieur</label>
                    <label><input type="radio" name="gender" value="madame" required> Madame</label>
                    <label><input type="radio" name="gender" value="autre" required> Autre</label>
                </fieldset>

                <label for="last-name">Nom * :</label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="first-name">Prénom * :</label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="birth-date">Date de naissance * :</label>
                <input type="date" id="birth-date" name="birth-date" placeholder="JJ/MM/AAAA" required>

                <label for="phone-number">Téléphone * :</label>
                <input type="tel" id="phone-number" name="phone-number" required>

                <label for="email">Adresse mail * :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe * :</label>
                <input type="password" id="password" name="password" required>
                <p class="conditions-password">
                    Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre etun caractère spécial.
                </p>

                <label for="confirm-password">Confirmer le mot de passe * :</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <label class="accept-conditions">
                    <input type="checkbox" name="accept-conditions" required>
                    J'accepte les <a href="#">conditions d'utilisation</a>
                </label>

                <button type="submit" class="btn-submit">Créer mon compte</button>
            </form>
        </div>
    </main>


    <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>

</body>
</html>

<!-- A modifier ici :
    - Civilité à aligner
    - Label à moderniser
    - Conditions mot de passe en plus petit
    - Coche condition d'utilisation à aligner

    A ajouter ici : 
    - Limite numéro tél
    - Champs obligatoire -->