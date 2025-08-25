<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Espace employé - Aide </title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-aide.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    

<!-- Header commun -->
<?php include('../COMPONENTS/headeremploye.html') ; ?>
<div class="layout">

<?php include('../COMPONENTS/menuemploye.html') ; ?>

<section class="title_help">
    <h2>Formulaire de signalement</h2>

    <form action="" method="post" enctype="" novalidate>

    <div>
        <label for="emp_name">Nom et prénom <span>*</span></label>
        <input id="emp_name" name="employee_name" type="text" placeholder="Ex: Marie Durand" required>
    </div>

    <div>
        <label for="emp_email">Adresse e-mail professionnelle <span>*</span></label>
        <input id="emp_email" name="employee_email" type="email" placeholder="nom@ecoride.company" required>
    </div>

     <div>
        <label for="emp_id">ID employé<span>*</span></label>
        <input id="emp_id" name="employee_id" type="text" placeholder="EMP-1023" required>
    </div>

     <div>
        <label for="subject">Titre du problème <span>*</span></label>
        <input id="subject" name="subject" type="text" placeholder="Ex : erreur 500 lors de l'envoi du formulaire" required>
    </div>

     <div>
        <label for="description">Description détaillée <span>*</span></label>
        <textarea id="description" name="description" placeholder="Décris ici ce que tu as fait, ce que tu attendais et ce qui s'est produit..." required></textarea>
        <p>Indique les étapes pour reproduire le problème, le navigateur et l'heure approximative.</p>
    </div>

    <fieldset>
        <legend>Gravité</legend>
        <label><input type="radio" name="severity" value="low" checked>Mineur</label>
        <label><input type="radio" name="severity" value="medium">Moyenne</label>
        <label><input type="radio" name="severity" value="high">Critique</label>
        <p>Choisir "critique" si le site est inutilisable ou si des données sont en danger.</p>
    </fieldset>
    <fieldset>
        <legend>Pièce jointe</legend>
    
        <div>
            <label for="screenshot">Capture d'écran (optionnelle)</label>
            <input id="screenshot" name="screenshot" type="file" accept="image/*,application/pdf"/>
            <p>Formats acceptés : PNG, JPG, GIF ou PDF. Taille max recommandée : 5 Mo. </p>
        </div>
</fieldset>
        <div>
            <button type="submit">Envoyer le signalement</button>
        </div>

        <p>
            Après envoi, un numéro de ticket sera généré et un e-mail de confirmation te sera envoyé.
            <strong>Ne partage pas d"informations sensibles (mot de passe, token, données personnelles d'utilisateurs).</strong>
        </p>

        <p>Besoin d'aide immédiate ? Contacte le responsable technique à <a href="mailto:tech@ecoride.company">tech@ecoride.company</a>. </p>
    </form>

</section>
    </div>
</body>
</html>