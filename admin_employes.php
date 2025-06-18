<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte employés</title>
    <link rel="stylesheet" href="ecoride_style.css">
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
                <li class="menu-item">Covoiturage</li>
                <li class="menu-item">Ratio</li>
                <li class="menu-item">Crédit</li>
                <li class="menu-item">Crédit auto / utilisateur auto</li>
            </ul>
        </section>
    </nav>

    <section class="menu-section">
        <h2>Compte Employés</h2>

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
    <?php include('COMPONENTS/footer.html') ?>
</body>
</html>