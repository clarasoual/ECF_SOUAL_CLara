<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informations Conducteur</title>
<link rel="stylesheet" href="../CSS/style_global.css">
<link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-infos-conducteur.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>
<main>
<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<div id="vehicles-container">
  <div class="driver-info-container">
    <h2>Véhicule numéro 1</h2>
    <section class="vehicle-form">
      <form>
        <label>Plaque d'immatriculation :</label>
        <input type="text" class="plate" name="plate" placeholder="AB-123-CD">

        <div class="form-section">
          <label>Date de première immatriculation :</label>
          <input type="date" class="date" name="date">
        </div>

        <div class="form-section">
          <label>Marque :</label>
          <select class="brand" name="brand">
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
          </select>
        </div>

        <div class="form-section">
          <label>Modèle :</label>
          <select class="model" name="model">
            <option value="">Sélectionner un modèle</option>
            <option value="208" data-marque="Peugeot">208</option>
            <option value="308" data-marque="Peugeot">308</option>
            <option value="3008" data-marque="Peugeot">3008</option>
            <option value="Clio" data-marque="Renault">Clio</option>
            <option value="Captur" data-marque="Renault">Captur</option>
            <option value="Mégane" data-marque="Renault">Mégane</option>
            <option value="C3" data-marque="Citroën">C3</option>
            <option value="C4" data-marque="Citroën">C4</option>
            <option value="C5 Aircross" data-marque="Citroën">C5 Aircross</option>
            <option value="Yaris" data-marque="Toyota">Yaris</option>
            <option value="Corolla" data-marque="Toyota">Corolla</option>
            <option value="RAV4" data-marque="Toyota">RAV4</option>
            <option value="Model 3" data-marque="Tesla">Model 3</option>
            <option value="Model Y" data-marque="Tesla">Model Y</option>
            <option value="Model S" data-marque="Tesla">Model S</option>
            <option value="500" data-marque="Fiat">500</option>
            <option value="Panda" data-marque="Fiat">Panda</option>
            <option value="Tipo" data-marque="Fiat">Tipo</option>
            <option value="Focus" data-marque="Ford">Focus</option>
            <option value="Fiesta" data-marque="Ford">Fiesta</option>
            <option value="Puma" data-marque="Ford">Puma</option>
            <option value="A3" data-marque="Audi">A3</option>
            <option value="Q3" data-marque="Audi">Q3</option>
            <option value="A1" data-marque="Audi">A1</option>
            <option value="Golf" data-marque="Volkswagen">Golf</option>
            <option value="Polo" data-marque="Volkswagen">Polo</option>
            <option value="Tiguan" data-marque="Volkswagen">Tiguan</option>
            <option value="Ceed" data-marque="Kia">Ceed</option>
            <option value="Sportage" data-marque="Kia">Sportage</option>
            <option value="Picanto" data-marque="Kia">Picanto</option>
            <option value="Corsa" data-marque="Opel">Corsa</option>
            <option value="Astra" data-marque="Opel">Astra</option>
            <option value="Mokka" data-marque="Opel">Mokka</option>
            <option value="Série 1" data-marque="BMW">Série 1</option>
            <option value="Série 3" data-marque="BMW">Série 3</option>
            <option value="X1" data-marque="BMW">X1</option>
          </select>
        </div>

        <div class="form-section">
          <label>Couleur :</label>
          <select class="color" name="color">
            <option value="">Choisir une couleur</option>
            <option value="Noir">Noir</option>
            <option value="Bleu">Bleu</option>
            <option value="Blanc">Blanc</option>
            <option value="Rouge">Rouge</option>
            <option value="Gris">Gris</option>
            <option value="Orange">Orange</option>
          </select>
        </div>

        <div class="form-section">
          <label>Nombre de places :</label>
          <input type="number" class="seats" name="seats" min="1" max="8">
        </div>

        <hr>

        <div class="form-section">
          <p>Animaux acceptés :</p>
          <label><input type="radio" name="pets" value="oui">Oui</label>
          <label><input type="radio" name="pets" value="non">Non</label>
        </div>

        <div class="form-section">
          <p>Fumeur :</p>
          <label><input type="radio" name="smoking" value="oui">Oui</label>
          <label><input type="radio" name="smoking" value="non">Non</label>
        </div>

        <div class="form-section">
          <label>Musique :</label>
          <select class="music" name="music">
            <option value="">Choisir un style</option>
            <option value="none">Pas de musique</option>
            <option value="classic">Classique</option>
            <option value="pop">Pop</option>
            <option value="rock">Rock</option>
            <option value="jazz">Jazz</option>
          </select>
        </div>

        <button type="submit">Enregistrer</button>
      </form>
    </section>
  </div>
</div>

<div class="vehicle-buttons">
  <button type="button" id="addVehicleBtn">Ajouter un véhicule</button>
  <button type="button" id="removeVehicleBtn">Supprimer un véhicule</button>
</div>

</main>
<?php include('../COMPONENTS/COMP-footer.html'); ?>
<script src="../JS/USR-infos-conducteur.js"></script>
</body>
</html>
