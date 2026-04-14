<?php
session_start();
include('../PHP/connexion.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: ADM-login.php');
    exit();
}

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
        date('Y-m-d')
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

// ====== SUSPENDRE / RÉACTIVER EMPLOYÉ ======
if (isset($_POST['suspend_emp_id'])) {
    $stmt_suspend = $bdd->prepare("UPDATE employes SET suspendu = ? WHERE id = ?");
    $stmt_suspend->execute([$_POST['suspend_value'], $_POST['suspend_emp_id']]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?toast=suspend");
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
    <style>
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
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .toast.hide { opacity: 0; }
        .badge-suspendu {
            background-color: #e74c3c;
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 6px;
        }
    </style>
</head>
<body>

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
                        <th>Date d'embauche</th>
                        <th>Statut</th>
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
                            <?php if ($emp['suspendu']): ?>
                                <span class="badge-suspendu">Suspendu</span>
                            <?php else: ?>
                                Actif
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-modifier">Modifier</button>

                            <!-- Suspendre / Réactiver -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="suspend_emp_id" value="<?= $emp['id'] ?>">
                                <input type="hidden" name="suspend_value" value="<?= $emp['suspendu'] ? 0 : 1 ?>">
                                <button type="submit">
                                    <?= $emp['suspendu'] ? '✅ Réactiver' : '🚫 Suspendre' ?>
                                </button>
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

<?php if (isset($_GET['toast']) && $_GET['toast'] === 'suspend'): ?>
    <div id="toast-success" class="toast">Statut du compte mis à jour !</div>
<?php endif; ?>

<script>
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

    document.querySelectorAll('.popup-close').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.popup').style.display = 'none');
    });

    document.querySelectorAll('.btn-open-popup').forEach(btn => {
        const popup = document.querySelector(btn.dataset.popup);
        btn.addEventListener('click', () => popup.style.display = 'block');
    });
</script>

<?php include('../COMPONENTS/COMP-footer-adm-emp.php'); ?>
</body>
</html>