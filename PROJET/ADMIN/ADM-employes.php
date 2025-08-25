<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte employés</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-employe.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun employés -->
<?php include('../COMPONENTS/headeradmin.html') ; ?>

<hr>

<main>

<?php include('../COMPONENTS/menuadmin.html') ; ?>

    <!-- Section principale : tableau des employés -->
    <section class="principal-menu-section">

        <!-- Tableau des employés -->
        <h2>Comptes Employés</h2>

        <table>
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date d'inscription</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Martin</td>
                    <td>Claire</td>
                    <td>claire.martin@exemple.com</td>
                    <td>01/03/2023</td>
                    <td>
                        <button>Modifier</button>
                        <button>Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            <button>Ajouter un employé</button>
        </div>
    </section>
</main>

<script src="JS/ecoride_js.js"></script>
<!-- Footer commun -->
<?php include('../COMPONENTS/footer.html'); ?>

</body>
</html>

<!-- A corriger ici :
 - PB de ease sur les href
 - Mettre les éléments de personas.txt
 - Chemin JS -->