<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposer un trajet</title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/header.html') ; ?>

    <!-- Formulaire trajet -->
    <section class="trip-step1">
        <h2 class="step-title">Votre trajet - Etape 1 sur 2</h2>
        <p class="required-note">Champs obligatoires</p>

        <table class="trip-table">
            <tr>
            <td class="trip-info">
                <h3 class="trip-subtitle">D'où partons-nous ?</h3>

                <label for="departure">Adresse de départ</label><br>
                <input type="text" id="departure" name="departure" placeholder="Adresse de départ"><br>

                <label for="arret1">Arrêt n°1 (optionnel)</label><br>
                <input type="text" id="step1" name="step1" placeholder="Ajouter un arrêt"><br><br>

                <button type="button">+ Ajouter un arrêt</button><br><br>

                <label for="vehicle-used">Véhicule utilisé</label><br>
                <select id="vehicle-used" name="vehicle-used">
                    <option value="">-- Sélectionnez un véhicule --</option>
                    <option value="veh1">Clio grise</option>
                    <option value="veh2">Kangoo blanc</option>
                </select><br><br>

                <label for="date">Date de départ</label><br>
                <input type="date" id="date" name="date"><br><br>

                <label for="time">Heure de départ</label><br>
                <input type="time" id="heure" name="time"><br><br>
            </td>

            <td class="trip-info-destination">
                <h3 class="trip-subtitle">Où allons-nous ?</h3>

                <label for="arrival">Adresse d'arrivée</label><br>
                <input type="text" id="arrival" name="arrival" placeholder="Adresse d'arrivée"><br><br>

                <label for="places">Nombre de places passagers disponibles</label><br>
                <input type="number" id="places" name="places" min="1" max="8"><br><br>

                <label for="commentaire">Autres précisions (optionnel)</label><br>
                <textarea id="commentaire" name="commentaire" rows="4" cols="40" placeholder="Ex : passage par autoroute, coffre petit, ..."></textarea><br><br>

                <p class="credit-infos">Ce trajet vous fera gagner <strong>  5 crédits</strong> par passager une fois effectué dans de bonns conditions.</p>
                <button type="submit" class="btn-submit">Valider</button>
            </td>   
            </tr>
        </table>
    </section>

    <script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html'); ?>
</body>
</html>

<!-- A modifier ici : 
- Enlever le hover vert
- Espace entre label et input
- Styliser d'ou partons nous etc
- Styliser titre
- Limite nombre de passagers
- Champs obligatoire à mettre

A ajouter : 
- Résumé de la proposition de trajet 