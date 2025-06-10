<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations Conducteur</title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/header.html') ; ?>

<main class="infos-conducteur-container">
    <h2>Véhicule numéro 1</h2>
    <section class="form-vehicule">
        <form action="#" method="post">
            <label for="plaque">Plaque d'immatriculation :</label><br>
            <input type="text" id="plaque" name="plaque" placeholder="AB-123-CD"><br><br>

            <label for="date">Date de première immatriculation :</label><br>
            <input type="date" id="date" name="date"><br><br>

            <label for="marque">Marque :</label><br>
            <select id="marque" name="marque">
                <option value="">Choisir une marque</option>
                <option value="Peugeot">Peugeot</option>
                <option value="Renault">Renault</option>
                <option value="Citroën">Citroën</option>
                <option value="Toyota">Toyota</option>
                <option value="Tesla">Tesla</option>
                <option value="Fiat">Fiat</option>
                <option value="Ford">Ford</option>
                <option value="Audi">Audi</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Kia">Kia</option>
                <option value="Opel">Opel</option>
                <option value="BMW">BMW</option>

            </select><br><br>

            <label for="modele">Modèle :</label><br>
            <select id="modele" name="modele">
                <option value="208" data-marque="Peugeot">208</option>
                <option value="308" data-marque="Peugeot">308</option>
                <option value="3008" data-marque="Peugeot">3008</option>

                <!-- Renault -->
                <option value="Clio" data-marque="Renault">Clio</option>
                <option value="Captur" data-marque="Renault">Captur</option>
                <option value="Mégane" data-marque="Renault">Mégane</option>

                <!-- Citroën -->
                <option value="C3" data-marque="Citroën">C3</option>
                <option value="C4" data-marque="Citroën">C4</option>
                <option value="C5 Aircross" data-marque="Citroën">C5 Aircross</option>

                <!-- Toyota -->
                <option value="Yaris" data-marque="Toyota">Yaris</option>
                <option value="Corolla" data-marque="Toyota">Corolla</option>
                <option value="RAV4" data-marque="Toyota">RAV4</option>

                <!-- Tesla -->
                <option value="Model 3" data-marque="Tesla">Model 3</option>
                <option value="Model Y" data-marque="Tesla">Model Y</option>
                <option value="Model S" data-marque="Tesla">Model S</option>

                <!-- Fiat -->
                <option value="500" data-marque="Fiat">500</option>
                <option value="Panda" data-marque="Fiat">Panda</option>
                <option value="Tipo" data-marque="Fiat">Tipo</option>

                <!-- Ford -->
                <option value="Focus" data-marque="Ford">Focus</option>
                <option value="Fiesta" data-marque="Ford">Fiesta</option>
                <option value="Puma" data-marque="Ford">Puma</option>

                <!-- Audi -->
                <option value="A3" data-marque="Audi">A3</option>
                <option value="Q3" data-marque="Audi">Q3</option>
                <option value="A1" data-marque="Audi">A1</option>

                <!-- Volkswagen -->
                <option value="Golf" data-marque="Volkswagen">Golf</option>
                <option value="Polo" data-marque="Volkswagen">Polo</option>
                <option value="Tiguan" data-marque="Volkswagen">Tiguan</option>

                <!-- Kia -->
                <option value="Ceed" data-marque="Kia">Ceed</option>
                <option value="Sportage" data-marque="Kia">Sportage</option>
                <option value="Picanto" data-marque="Kia">Picanto</option>

                <!-- Opel -->
                <option value="Corsa" data-marque="Opel">Corsa</option>
                <option value="Astra" data-marque="Opel">Astra</option>
                <option value="Mokka" data-marque="Opel">Mokka</option>

                <!-- BMW -->
                <option value="Série 1" data-marque="BMW">Série 1</option>
                <option value="Série 3" data-marque="BMW">Série 3</option>
                <option value="X1" data-marque="BMW">X1</option>
            </select><br><br>

            <label for="couleur">Couleur :</label>
            <select id="couleur" name="couleur">
                <option value="">Choisir une couleur :</option>
                <option value="Noir">Noir</option>
                <option value="Bleu">Bleu</option>
                <option value="Blanc">Blanc</option>
                <option value="Rouge">Rouge</option>
                <option value="Gris">Gris</option>
                <option value="Orange">Orange</option>

            </select><br><br>

            <label for="places">Nombre de places passagers :</label><br>
            <input type="number" id="places" name="places" min="1" max="8"><br><br>

            <hr>

            <h3>Vos préférences</h3>

            <p>Animaux acceptés :</p>
            <label><input type="radio" name="animaux" value="oui">Oui</label>
            <label><input type="radio" name="animaux" value="non">Non</label><br><br>

            <p>Fumeur :</p>
            <label><input type="radio" name="fumeur" value="oui">Oui</label>
            <label><input type="radio" name="fumeur" value="non">Non</label><br><br>

            <label for="musique">Musique :</label><br>
            <select id="musique" name="musique">
                <option value="">Choisir un style</option>
                <option value="Aucune">Pas de musique</option>
                <option value="Classique">Classique</option>
                <option value="Pop">Pop</option>
                <option value="Rock">Rock</option>
                <option value="Jazz">Jazz</option>
            </select><br><br>

            <button type="submit">Enregistrer</button>
        </form>
    </section>
</main>

 <script src="JS/ecoride_js.js"></script>
 <?php include('COMPONENTS/footer.html'); ?>

</body>
</html>