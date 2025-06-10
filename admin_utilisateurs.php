<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte utilisateurs</title>
    <link rel="stylesheet" href="CSS/ecoride_style.css">
</head>
<body>
<?php include('COMPONENTS/headeremploye.html') ; ?>

<hr>

<main>
    <nav>
        <section>
            <h2>Tableau de bord</h2>
        </section>

        <section>
            <h2>Comptes</h2>
            <ul>
                <li>Employés</li>
                <li>Utilisateurs</li>
            </ul>
        </section>

        <section>
            <h2>Statistiques</h2>
            <ul>
                <li>Covoiturage quotidien</li>
                <li>Ratio</li>
                <li>Crédit</li>
                <li>Crédit auto / utilisateur auto</li>
            </ul>
        </section>
    </nav>

    <section>
        <h2>Compte Utilisateurs</h2>

        <form>
            <label for="recherche">Rechercher un utilisateur :</label>
            <input type="text" id="recherche" name="recherche" placeholder="Pseudo, mail, téléphone...">
            <button type="submit">Rechercher</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AlexUser</td>
                    <td>alex@exemple.com</td>
                    <td>06 11 22 33 44</td>
                    <td>
                        <button>Voir</button>
                        <button>Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>
    <?php include('COMPONENTS/footer.html') ?>
</body>
</html>