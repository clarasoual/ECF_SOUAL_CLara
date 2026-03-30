<?php
session_start();
include('../PHP/connexion.php'); // $bdd

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php'); // redirige vers login si pas connecté
    exit();
}

// Récupérer les infos de l'admin connecté
$stmt = $bdd->prepare("SELECT prenom, email FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte employés</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-employe.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/CSS MOBILE/ADM-employe-mobile.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header commun employés -->
<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<!-- Bouton Burger pour mobile/tablette -->
<button class="burger-btn" id="burger-btn">
    <span class="burger-line"></span>
    <span class="burger-line"></span>
    <span class="burger-line"></span>
</button>

<!-- Overlay derrière le menu burger -->
<div class="menu-overlay" id="menu-overlay"></div>

<!-- Menu Admin -->
<?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

<hr>

<main>
    <!-- Section principale : tableau des employés -->
    <section class="principal-menu-section">
        <h2>Comptes Employés</h2>

        <div class="table-responsive">
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
                            <button data-popup="#popup-modifier-employe">Modifier</button>
                            <button>Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bouton pour ajouter un employé -->
        <button data-popup="#popup-ajouter-employe">Ajouter un employé</button>

        <!-- POP UP AJOUTER EMPLOYÉ -->
        <div id="popup-ajouter-employe" class="popup" style="display:none;">
            <form id="form-ajouter-employe">
                <input type="text" placeholder="Nom">
                <input type="text" placeholder="Prénom">
                <input type="email" placeholder="Email">
                <button type="submit">Enregistrer</button>
                <button type="button" class="popup-close">X</button>
            </form>
        </div>

        <!-- POP UP MODIFIER EMPLOYÉ -->
        <div id="popup-modifier-employe" class="popup" style="display:none;">
            <form id="form-modifier-employe">
                <input type="text" placeholder="Nom">
                <input type="text" placeholder="Prénom">
                <input type="email" placeholder="Email">
                <button type="submit">Enregistrer</button>
                <button type="button" class="popup-close">X</button>
            </form>
        </div>
    </section>
</main>

<!-- Footer commun -->
<?php include('../COMPONENTS/COMP-footer.html'); ?>

<script>
    const burgerBtn = document.getElementById('burger-btn');
    const menuAdmin = document.querySelector('.menu-admin');
    const overlay = document.getElementById('menu-overlay');

    burgerBtn.addEventListener('click', () => {
        menuAdmin.classList.toggle('active');
        overlay.classList.toggle('active');
        burgerBtn.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        menuAdmin.classList.remove('active');
        overlay.classList.remove('active');
        burgerBtn.classList.remove('active');
    });
</script>
<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>
