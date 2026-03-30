<?php
session_start();
include('../PHP/connexion.php'); // $bdd

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

// Récupérer les infos de l'admin connecté
$stmt = $bdd->prepare("SELECT prenom, email FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// ====== AJOUTER EMPLOYÉ ======
if (isset($_POST['add_prenom'], $_POST['add_email'], $_POST['add_service'])) {
    $stmt_add = $bdd->prepare("INSERT INTO employes (prenom, email, service, date_embauche) VALUES (?, ?, ?, ?)");
    $stmt_add->execute([
        $_POST['add_prenom'],
        $_POST['add_email'],
        $_POST['add_service'],
        date('Y-m-d') // date du jour
    ]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ====== MODIFIER EMPLOYÉ ======
if (isset($_POST['edit_emp_id'])) {
    $stmt_update = $bdd->prepare("UPDATE employes SET prenom = ?, email = ?, service = ? WHERE id = ?");
    $stmt_update->execute([
        $_POST['edit_prenom'],
        $_POST['edit_email'],
        $_POST['edit_service'],
        $_POST['edit_emp_id']
    ]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ====== SUPPRIMER EMPLOYÉ ======
if (isset($_POST['delete_emp_id'])) {
    $stmt_delete = $bdd->prepare("DELETE FROM employes WHERE id = ?");
    $stmt_delete->execute([$_POST['delete_emp_id']]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ====== RÉCUPÉRER TOUS LES EMPLOYÉS ======
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
            <a href="ADM-employes.php" class="logo-link">
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
    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>
    <hr>

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
                    <tr data-id="<?= $emp['id'] ?>">
                        <td><?= htmlspecialchars($emp['prenom']) ?></td>
                        <td><?= htmlspecialchars($emp['service'] ?? '') ?></td>
                        <td><?= htmlspecialchars($emp['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($emp['date_embauche']) ?></td>
                        <td>
                            <button class="btn-modifier">Modifier</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_emp_id" value="<?= $emp['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button class="btn-open-popup" data-popup="#popup-ajouter-employe">Ajouter un employé</button>

        <!-- POP UP AJOUTER -->
        <div id="popup-ajouter-employe" class="popup" style="display:none;">
            <form method="POST">
                <input type="text" name="add_prenom" placeholder="Prénom" required>
                <input type="email" name="add_email" placeholder="Email" required>
                <input type="text" name="add_service" placeholder="Service" required>
                <button type="submit">Enregistrer</button>
                <button type="button" class="popup-close">X</button>
            </form>
        </div>

        <!-- POP UP MODIFIER -->
        <div id="popup-modifier-employe" class="popup" style="display:none;">
            <form method="POST">
                <input type="hidden" name="edit_emp_id" id="edit_emp_id">
                <input type="text" name="edit_prenom" id="edit_prenom" placeholder="Prénom" required>
                <input type="email" name="edit_email" id="edit_email" placeholder="Email" required>
                <input type="text" name="edit_service" id="edit_service" placeholder="Service" required>
                <button type="submit">Enregistrer</button>
                <button type="button" class="popup-close">X</button>
            </form>
        </div>
    </section>
</main>

<script>
    // Ouvrir popup modifier
    document.querySelectorAll('.btn-modifier').forEach(btn => {
        btn.addEventListener('click', () => {
            const tr = btn.closest('tr');
            document.getElementById('edit_emp_id').value = tr.dataset.id;
            document.getElementById('edit_prenom').value = tr.children[0].textContent;
            document.getElementById('edit_service').value = tr.children[1].textContent;
            document.getElementById('edit_email').value = tr.children[2].textContent;
            document.getElementById('popup-modifier-employe').style.display = 'block';
        });
    });

    // Fermer popups
    document.querySelectorAll('.popup-close').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.popup').style.display = 'none');
    });

    // Popup ajouter
    document.querySelectorAll('.btn-open-popup').forEach(btn => {
        const popup = document.querySelector(btn.dataset.popup);
        btn.addEventListener('click', () => popup.style.display = 'block');
    });
</script>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>