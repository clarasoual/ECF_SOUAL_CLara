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
<?php include('../COMPONENTS/COMP-header-admin.html') ; ?>

<hr>

<main>

<?php include('../COMPONENTS/COMP-menu-admin.html') ; ?>


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

         <div>
            <button data-popup="#popup-ajouter-employe">Ajouter un utilisateur</button>

            <!-- POP UP AJOUTER EMPLOYÉ -->

            <div  id="popup-ajouter-employe" class="popup" style="display:none;">
                <form id="form-ajouter-employe">
                    <input type="text" placeholder="Nom">
                    <input type="text" placeholder="Prénom">
                    <input type="email" placeholder="Email">
                    <button type="submit">Enregistrer</button>
                    <button type="button" class="popup-close">X</button>
                </form>
            </div>

            <!-- FIN POP UP -->

            <!-- POP UP MODIFIER -->

            <div id="popup-modifier-employe" class="popup" style="display:none;">
                <form id="form-modifier-employe">
                    <input type="text" placeholder="Nom">
                    <input type="text" placeholder="Prénom">
                    <input type="email" placeholder="Email">
                    <button type="submit">Enregistrer</button>
                    <button type="button" class="popup-close">X</button>
                </form>
            </div>

            <!-- FIN POP UP -->

            <!-- voir ==> voir profil -->
    </section>
</main>

<script src="../JS/ADM-utilisateurs.js"></script>

    <!-- Footer commun -->
    <?php include('../COMPONENTS/COMP-footer.html'); ?>
</body>
</html>

<!-- A corriger ici :
 - CSS du ajouter
 - Voir ?
 - Tri Nom, etc
 - Recherche -->