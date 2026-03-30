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


// ====== SUPPRIMER UN UTILISATEUR ======
if (isset($_POST['delete_user_id'])) {

    $stmt_delete = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt_delete->execute([$_POST['delete_user_id']]);

    // Redirection avec toast
    header("Location: " . $_SERVER['PHP_SELF'] . "?toast=delete");
    exit();
}


// ====== RÉCUPÉRER UTILISATEURS ======
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

<style>

/* ===== Toast ===== */

.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #4BB543;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    opacity: 1;
    transform: translateY(0);
    transition: all 0.3s ease;
    z-index: 1000;
}

.toast.hide {
    opacity: 0;
    transform: translateY(20px);
}

</style>

</head>

<body>

<?php include('../COMPONENTS/COMP-header-admin.php'); ?>

<hr>

<main>

<?php include('../COMPONENTS/COMP-menu-admin.html'); ?>

<section id="account-user">

<h2>Compte Utilisateurs</h2>

<form onsubmit="return false;">
    <label for="search">Rechercher un utilisateur :</label>
    <input type="text" id="search" name="search" placeholder="Nom, mail...">
    <button type="button">Rechercher</button>
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

<button><a href="ADM-user-profil.php?id=<?= $user['id'] ?>" class="btn-voir">Voir</a></button>

<form method="POST"
style="display:inline;"
onsubmit="return confirm('Vous êtes sûr de vouloir supprimer cet utilisateur ?')">

<input type="hidden"
name="delete_user_id"
value="<?= $user['id'] ?>">

<button type="submit">
Supprimer
</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>
<p id="no-results" style="display:none; text-align:center; margin-top:15px;">
    Aucun utilisateur trouvé.
</p>
</div>

</section>

</main>



<!-- ===== TOAST HTML ===== -->

<?php if (isset($_GET['toast']) && $_GET['toast'] === 'delete'): ?>

<div id="toast-success" class="toast">
Utilisateur supprimé avec succès !
</div>

<?php endif; ?>



<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>


<script src="../JS/USR-toast.js"></script>
<script>
    document.getElementById('search').addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.table-users tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const prenom = row.cells[0].textContent.toLowerCase();
            const nom = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();

            if (prenom.includes(query) || nom.includes(query) || email.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('no-results').style.display = visibleCount === 0 ? 'block' : 'none';
    });
</script>
</body>
</html>