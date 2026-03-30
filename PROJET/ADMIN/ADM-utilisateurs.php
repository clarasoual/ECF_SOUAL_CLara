<?php
session_start();
include('../PHP/connexion.php'); // $bdd

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php'); 
    exit();
}

// Infos admin connecté
$stmt = $bdd->prepare("SELECT prenom, email FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// Récupérer tous les utilisateurs
$stmt_users = $bdd->prepare("SELECT * FROM utilisateurs ORDER BY id ASC");
$stmt_users->execute();
$utilisateurs = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Compte utilisateurs</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-utilisateurs.css">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>
<hr>
<main>
<?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

<section id="account-user">
    <h2>Compte Utilisateurs</h2>

    <form>
        <label for="search">Rechercher un utilisateur :</label>
        <input type="text" id="search" name="search" placeholder="Nom, mail...">
        <button type="submit">Rechercher</button>
    </form>

    <div class="table-responsive">
        <table class="table-users">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($utilisateurs as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['date_inscription']) ?></td>
                    <td>
                        <button class="btn-voir" data-id="<?= $user['id'] ?>">Voir</button>
                        <button class="btn-supprimer" data-id="<?= $user['id'] ?>">Supprimer</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <button data-popup="#popup-ajouter-employe">Ajouter un utilisateur</button>
</section>
</main>

<script src="../JS/ADM-utilisateurs.js"></script>
<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>