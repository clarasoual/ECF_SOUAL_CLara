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

// Récupérer tous les employés
$stmt_emp = $bdd->prepare("SELECT * FROM employes ORDER BY id ASC");
$stmt_emp->execute();
$employes = $stmt_emp->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Comptes Employés</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-utilisateurs.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header dynamique -->
<header class="header-employe">
    <h1 class="espace-employe">Espace Administrateur</h1>
    <div class="header-bottom">
        <div class="logo">
            <a href="ADM-utilisateurs.php" class="logo-link">
                <img src="../../IMAGES/logo.png" alt="Logo Eco Ride">
                <span class="logo-text">Eco Ride</span>
            </a>
        </div>
        <div class="user-profile">
            <div class="user-info">
                <p><span class="label-employe">Nom :</span> <?= htmlspecialchars($admin['prenom']) ?></p>
                <p><span class="label-employe">Email :</span> <?= htmlspecialchars($admin['email']) ?></p>
            </div>
            <div class="profil-container">
                <img src="../../IMAGES/default-avatar.jpg" alt="Photo de profil par défaut" class="photo-profil">
                <div class="menu-profil">
                    <a href="moncompte.php">Mon compte</a>
                    <a href="ADM-logout.php">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>
</header>

<hr>

<main>
    <!-- Menu admin -->
    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>
    <hr>

    <!-- Section principale : tableau des employés -->
    <section class="principal-menu-section">
        <h2>Comptes Employés</h2>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Service</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($employes as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['prenom']) ?></td> <!-- Remplacer par $emp['nom'] si tu as une colonne nom -->
                        <td><?= htmlspecialchars($emp['service']) ?></td>
                        <td><?= htmlspecialchars($emp['email'] ?? '') ?></td> <!-- Si tu ajoutes email plus tard -->
                        <td><?= htmlspecialchars($emp['date_embauche']) ?></td>
                        <td>
                            <button data-popup="#popup-modifier-employe">Modifier</button>
                            <button>Supprimer</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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

<script>
    const burgerBtn = document.getElementById('burger-btn');
    const menuAdmin = document.querySelector('.menu-admin');
    const overlay = document.getElementById('menu-overlay');

    burgerBtn?.addEventListener('click', () => {
        menuAdmin.classList.toggle('active');
        overlay.classList.toggle('active');
        burgerBtn.classList.toggle('active');
    });

    overlay?.addEventListener('click', () => {
        menuAdmin.classList.remove('active');
        overlay.classList.remove('active');
        burgerBtn.classList.remove('active');
    });
</script>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>