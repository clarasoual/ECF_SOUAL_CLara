<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations Conducteur</title>
    <link rel="stylesheet" href="../../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../../COMPONENTS/header.html') ; ?>

<?php include('../../COMPONENTS/menumyaccount.html'); ?>

<!-- Formulaire de déclaratio  d'un véhicule -->
<main class="driver-info-container">
    <h2>Véhicule numéro 1</h2>
    <section class="vehicle-form">
        <form action="#" method="post">
            <label for="plate">Plaque d'immatriculation :</label><br>
            <input type="text" id="plate" name="plate" placeholder="AB-123-CD"><br><br>

            <label for="date">Date de première immatriculation :</label><br>
            <input type="date" id="date" name="date"><br><br>

            <!-- Selection marque du véhicule ; à mettre à jour-->
            <label for="brand">Marque :</label><br>
            <select id="brand" name="brand">
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

            <!-- Selection modèle du véhicule (lié à la marque) -->
            <label for="model">Modèle :</label><br>
            <select id="model" name="model">
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

            <!-- Couleur du véhicule -->
            <label for="color">Couleur :</label>
            <select id="color" name="color">
                <option value="">Choisir une couleur :</option>
                <option value="Noir">Noir</option>
                <option value="Bleu">Bleu</option>
                <option value="Blanc">Blanc</option>
                <option value="Rouge">Rouge</option>
                <option value="Gris">Gris</option>
                <option value="Orange">Orange</option>

            </select><br><br>

            <!-- Nombre de passagers -->
            <label for="seats">Nombre de places passagers :</label><br>
            <input type="number" id="seats" name="seats" min="1" max="8"><br><br>

            <hr>

            <!-- Préférences conducteur -->
            <h3>Vos préférences</h3>

            <p>Animaux acceptés :</p>
            <label><input type="radio" name="pets" value="oui">Oui</label>
            <label><input type="radio" name="pets" value="non">Non</label><br><br>

            <p>Fumeur :</p>
            <label><input type="radio" name="smoking" value="oui">Oui</label>
            <label><input type="radio" name="smoking" value="non">Non</label><br><br>

            <label for="music">Musique :</label><br>
            <select id="music" name="music">
                <option value="">Choisir un style</option>
                <option value="none">Pas de musique</option>
                <option value="classic">Classique</option>
                <option value="pop">Pop</option>
                <option value="rock">Rock</option>
                <option value="jazz">Jazz</option>
            </select><br><br>

            <button type="submit">Enregistrer</button>
        </form>
    </section>
</main>

 <script src="JS/ecoride_js.js"></script>

 <!-- Footer commun -->
 <?php include('../../COMPONENTS/footer.html'); ?>

</body>
</html>