<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Espace employé - Messagerie </title>
    <link rel="stylesheet" href="../CSS/ecoride_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun -->
<?php include('../COMPONENTS/headeremploye.html') ; ?>

<?php include('../COMPONENTS/menuemploye.html') ; ?>

<hr>

<main>
    <section class="reviews-moderation">
        <h2 id="title-reviews">Avis utilisateurs - Modération quotidienne</h2>

        <form class="toolbar-filters" method="GET" action="">
            <fieldset class="search-zone" role="search">
                <label for="q">Rechercher</label>
                <input type="search" id="q" placeholder="Auteur, trajet, commentaire,..." autocomplete="off">
            </fieldset>

            <fieldset class="filters-zone">
                <legend class="sr-only">Filtres</legend>

                <label for="filters-status">Statut</label>
                <select id="filters-status" name="status">
                    <option value="">Tous</option>
                    <option value="flagged">Signalé</option>
                    <option value="published">Publié</option>
                    <option value="archivec">Archivé</option>
                </select>

                <label for="filters-rate">Note</label>
                <select id="filters-rate" name="note">
                    <option value="">Toutes</option>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>

                <label for="debut-date">Du</label>
                <input type="date" id="debut-date" name="from">

                <label for="end-date">Au</label>
                <input type="date" id="end-date" name="to">

                <label for="tri">Trier par</label>
                <select id="tri" name="sort">
                    <option value="newest">Plus récent</option>
                    <option value="oldest">Plus ancien</option>
                    <option value="rating_desc">Note décroissante</option>
                    <option value="rating_asc">Note croissante</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="avisutilisateurs" class="btn-reset" role="button">Réinitialiser</a>



            </fieldset>
        </form>

        <form method="POST" action="moderation.php" class="reviews-list">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Auteur</th>
                        <th>Trajet</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="select[]" value="101"></td>
                        <td>101</td>
                        <td>2025-08-18</td>
                        <td>Nino</td>
                        <td>Paris - Rennes</td>
                        <td>5</td>
                        <td>Condcuteur ponctuel</td>
                        <td>Publié</td>
                    </tr>

                    <tr>
                        <td><input type="checkbox" name="select" value="102"></td>
                        <td>102</td>
                        <td>2025-08-23</td>
                        <td>Antoine</td>
                        <td>Bordeaux - Biarritz</td>
                        <td>2</td>
                        <td>J'ai eu peur'</td>
                        <td>Signalé</td>
                    </tr>
                </tbody>
            </table>
        </form>

        <div class="bulk-actions">
            <p>Actions sur la selection :</p>
            <button type="submit" name="action" value="archive">Archiver</button>
            <button type="submit" name="action" value="flag">Marquer comme signalé</button>
        </div>


    </section>
</main>

    <?php include('../COMPONENTS/footer.html') ?>

</body>
</html>