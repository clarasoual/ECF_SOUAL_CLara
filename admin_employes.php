<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte employés</title>
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
                <li>Covoiturage</li>
                <li>Ratio</li>
                <li>Crédit</li>
                <li>Crédit auto / utilisateur auto</li>
            </ul>
        </section>
    </nav>

    <section>
        <h2>Compte Employés</h2>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
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