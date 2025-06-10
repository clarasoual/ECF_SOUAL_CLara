<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Création de votre compte - ECO RIDE </title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

    <main>
        <div class="creation-compte-container">
            <h1>Création de votre comtpe</h1>
            <p class="note-obligatoire">* Champs obligatoires</p>

            <form action="#" method="POST" class="form-inscription">

                <fieldset class="civilite">
                    <legend>Civilité *</legend>
                    <label><input type="radio" name="civilite" value="monsieur" required> Monsieur</label>
                    <label><input type="radio" name="civilite" value="madame" required> Madame</label>
                    <label><input type="radio" name="civilite" value="autre" required> Autre</label>
                </fieldset>

                <label for="nom">Nom * :</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom * :</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="naissance">Date de naissance * :</label>
                <input type="date" id="naissance" name="naissance" placeholder="JJ/MM/AAAA" required>

                <label for="telephone">Téléphone * :</label>
                <input type="tel" id="telephone" name="telephone" required>

                <label for="email">Adresse mail * :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe * :</label>
                <input type="password" id="password" name="password" required>
                <p class="conditions-mdp">
                    Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre etun caractère spécial.
                </p>

                <label for="confirm-password">Confirmer le mot de passe * :</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <label class="accept-conditions">
                    <input type="checkbox" name="accept-conditions" required>
                    J'accepte les <a href="#">conditions d'utilisation</a>
                </label>

                <button type="submit" class="btn-valider">Créer mon compte</button>
            </form>
        </div>
    </main>


    <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>