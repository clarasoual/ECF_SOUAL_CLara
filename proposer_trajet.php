<?php include('COMPONENTS/header.html') ; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposer un trajet</title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>

    <section>
        <h2>Votre trajet - Etape 1 sur 2</h2>
        <p>Champs obligatoires</p>

        <table>
            <tr>
            <td>
                <h3>D'où partons-nous ?</h3>

                <label for="depart">Adresse de départ</label><br>
                <input type="text" id="depart" name="depart" placeholder="Adresse de départ"><br>

                <label for="arret1">Arrêt n°1 (optionnel)</label><br>
                <input type="text" id="arret1" name="arret1" placeholder="Ajouter un arrêt"><br><br>

                <button type="button">+ Ajouter un arrêt</button><br><br>

                <label for="vehicule">Véhicule utilisé</label><br>
                <select id="vehicule" name="vehicule">
                    <option value="">-- Sélectionnez un véhicule --</option>
                    <option value="veh1">Clio grise</option>
                    <option value="veh2">Kangoo blanc</option>
                </select><br><br>

                <label for="date">Date de départ</label><br>
                <input type="date" id="date" name="date"><br><br>

                <label for="heure">Heure de départ</label><br>
                <input type="time" id="heure" name="heure"><br><br>
            </td>

            <td>
                <h3>Où allons-nous ?</h3>

                <label for="arrivee">Adresse d'arrivée</label><br>
                <input type="text" id="arrivee" name="arrivee" placeholder="Adresse d'arrivée"><br><br>

                <label for="places">Nombre de passagers disponibles</label><br>
                <input type="number" id="places" name="places" min="1" max="8"><br><br>

                <label for="commentaire">Autres précisions (optionnel)</label><br>
                <textarea id="commentaire" name="commentaire" rows="4" cols="40" placeholder="Ex : passage par autoroute, coffre petit, ..."></textarea><br><br>

                <p>Ce trajet vous fera gagner <strong>  5 crédits</strong> par passager une fois effectué dans de bonns conditions.</p>
                <button type="submit">Valider</button>
            </td>   
            </tr>
        </table>
    </section>

    <script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html'); ?>
</body>
</html>