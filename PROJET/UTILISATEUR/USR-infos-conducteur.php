<?php

include('../PHP/auth.php'); // Démarre la session et charge les fonctions
requireLogin(); // Redirige si l'utilisateur n'est pas connecté

session_start();
include('../PHP/connexion.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../UTILISATEUR/USR-inscription.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informations Conducteur</title>
<link rel="stylesheet" href="../CSS/style_global.css">
<link rel="stylesheet" href="../CSS/CSS UTILISATEUR/USR-infos-conducteur.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">

<style>
/* Message plein écran */
.success-screen {
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: #1f1f1f;
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  flex-direction: column;
  text-align: center;
  font-family: 'Quicksand', sans-serif;
}

.success-message h1 {
  font-size: 2rem;
  color: #4CAF50;
  margin: 0;
}

/* Confettis simples */
.confetti {
  position: absolute;
  width: 10px;
  height: 10px;
  background: #FFC107;
  animation: confetti-fall linear forwards;
  top: -10px;
  border-radius: 50%;
}
@keyframes confetti-fall {
  0% { transform: translateY(0) rotate(0deg); opacity:1; }
  100% { transform: translateY(100vh) rotate(360deg); opacity:0; }
}
</style>
</head>
<body>

<?php include('../COMPONENTS/COMP-header.html'); ?>
<main>
<?php include('../COMPONENTS/COMP-menu-mon-compte.html'); ?>

<div id="vehicles-container">
  <div class="driver-info-container">
    <h2>Véhicule numéro 1</h2>
    <section class="vehicle-form">
      <form action="../PHP/traitement-vehicule.php" method="POST">
        <label>Plaque d'immatriculation :</label>
        <input type="text" class="plate" name="plate" placeholder="AB-123-CD" required>

        <div class="form-section">
          <label>Date de première immatriculation :</label>
          <input type="date" class="date" name="date" required>
        </div>

        <div class="form-section">
          <label>Marque :</label>
          <select class="brand" name="marque" required>
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
          <select class="model" name="modele" required>
            <option value="">Sélectionner un modèle</option>
            <!-- Modèles selon marque -->
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
          <select class="color" name="color" required>
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
          <label>Carburant :</label>
          <select class="fuel" name="carburant" required>
            <option value="">Choisir un carburant</option>
            <option value="Essence">Essence</option>
            <option value="Diesel">Diesel</option>
            <option value="Electrique">Electrique</option>
            <option value="Hybride">Hybride</option>
          </select>
        </div>

        <div class="form-section">
          <label>Nombre de places :</label>
          <input type="number" class="seats" name="places" min="1" max="8" required>
        </div>

        <hr>

        <div class="form-section">
          <p>Animaux acceptés :</p>
          <label><input type="radio" name="pets" value="oui" required> Oui</label>
          <label><input type="radio" name="pets" value="non" required> Non</label>
        </div>

        <div class="form-section">
          <p>Fumeur :</p>
          <label><input type="radio" name="smoking" value="oui" required> Oui</label>
          <label><input type="radio" name="smoking" value="non" required> Non</label>
        </div>

        <div class="form-section">
          <label>Musique :</label>
          <select class="music" name="music" required>
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

<!-- Message plein écran pour validation -->
<div id="success-screen" class="success-screen">
  <div class="success-message">
    <h1>🎉 C’est bon ! Votre profil est entièrement complété 🎉</h1>
  </div>
</div>

<script>
// Confettis
function launchConfetti(count = 100) {
  const screen = document.getElementById('success-screen');
  for(let i=0;i<count;i++){
    const conf = document.createElement('div');
    conf.classList.add('confetti');
    conf.style.left = Math.random()*100 + 'vw';
    conf.style.background = `hsl(${Math.random()*360}, 70%, 50%)`;
    conf.style.animationDuration = 2 + Math.random()*2 + 's';
    screen.appendChild(conf);
    setTimeout(()=>conf.remove(),4000);
  }
}

// Formulaire : afficher message plein écran
document.querySelector('form').addEventListener('submit', e => {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);

  fetch(form.action, { method:'POST', body:data })
    .then(res=>res.text())
    .then(result=>{
      const screen = document.getElementById('success-screen');
      screen.style.display = 'flex';
      launchConfetti(150);
      // Redirection après 3 secondes
      setTimeout(()=>{window.location.href='../UTILISATEUR/USR-infos-perso.php';}, 3000);
    })
    .catch(err=>{
      alert('❌ Une erreur est survenue, réessayez.');
      console.error(err);
    });
});
</script>

<script src="../JS/USR-infos-conducteur.js"></script>
</body>
</html>
