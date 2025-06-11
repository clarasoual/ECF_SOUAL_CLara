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
    <nav class="menu-principal">
        <section class="menu-section">
            <h2>Tableau de bord</h2>
        </section>

        <section class="menu-section">
            <h2>Comptes</h2>
            <ul class="menu-li">
                <li class="menu-item">Employés</li>
                <li class="menu-item">Utilisateurs</li>
            </ul>
        </section>

        <section class="menu-section">
            <h2>Statistiques</h2>
            <ul class="menu-li">
                <li class="menu-item">Covoiturage quotidien</li>
                <li class="menu-item">Ratio</li>
                <li class="menu-item">Crédit</li>
                <li class="menu-item">Crédit auto / utilisateur auto</li>
            </ul>
        </section>
    </nav>

    <section id="account-user">
        <h2>Compte Utilisateurs</h2>

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
    <?php include('COMPONENTS/footer.html') ?>
</body>
</html>