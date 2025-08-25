<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte utilisateurs</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
        <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-utilisateurs.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun employés -->
<?php include('../COMPONENTS/headeradmin.html') ; ?>

<hr>

<main>

<?php include('../COMPONENTS/menuadmin.html') ; ?>


    <!-- Section principale : tableau des employés -->
    <section id="account-user">
        <h2>Compte Utilisateurs</h2>

        <!-- Section de recherche des utilisateurs -->
        <form>
            <label for="search">Rechercher un utilisateur :</label>
            <input type="text" id="search" name="search" placeholder="Pseudo, mail, téléphone...">
            <button type="submit">Rechercher</button>
        </form>

        <table class="table-users">
            <thead>
                <tr>
                    <th scope="col">Pseudo</th>
                    <th scope="col">Email</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AlexUser</td>
                    <td>alex@exemple.com</td>
                    <td>06 11 22 33 44</td>
                    <td>
                        <button class="btn-voir">Voir</button>
                        <button class="btn-supprimer">Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/footer.html') ; ?>
</body>
</html>

<!-- A corriger ici :
 - Mettre un ease sur le tableau des utilisateurs
 - Revoir CSS des titres
 - Mettre les utilisateurs de personas.txt
 - Mettre le menu de gauche en composant php 
 - Chemin JS -->