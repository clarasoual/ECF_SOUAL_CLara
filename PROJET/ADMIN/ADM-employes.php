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
if (isset($_POST['add_prenom'], $_POST['add_email'], $_POST['add_service'], $_POST['add_password'])) {
    $mot_de_passe = password_hash($_POST['add_password'], PASSWORD_DEFAULT);
    $stmt_add = $bdd->prepare("INSERT INTO employes (prenom, email, service, mot_de_passe, date_embauche) VALUES (?, ?, ?, ?, ?)");
    $stmt_add->execute([
        $_POST['add_prenom'],
        $_POST['add_email'],
        $_POST['add_service'],
        $mot_de_passe,
        date('Y-m-d')
    ]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?toast=add");
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
    header("Location: " . $_SERVER['PHP_SELF'] . "?toast=edit");
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
    <link rel="stylesheet" href="../CSS/CSS ADMIN/ADM-employes.css">    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include('../COMPONENTS/COMP-header-admin.php'); ?>

    <!-- SELECT MOBILE (hors du main pour ne pas être dans le flex) -->
    <select class="menu-principal-select" onchange="window.location.href=this.value">
        <option value="">— Navigation —</option>
        <option value="ADM-employes.php">Employés</option>
        <option value="ADM-utilisateurs.php">Utilisateurs</option>
        <option value="ADM-statistiques.php">Statistiques</option>
        <option value="admin_logs.php">Logs</option>
    </select>

<main>
    <?php include('../COMPONENTS/COMP-menu-admin.html'); ?>
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
                <input type="password" name="add_password" placeholder="Mot de passe" required>
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

<?php if (isset($_GET['toast'])): ?>
    <?php if ($_GET['toast'] === 'suspend'): ?>
        <div id="toast-success" class="toast">Statut du compte mis à jour !</div>
    <?php elseif ($_GET['toast'] === 'add'): ?>
        <div id="toast-success" class="toast">Employé ajouté avec succès !</div>
    <?php elseif ($_GET['toast'] === 'edit'): ?>
        <div id="toast-success" class="toast">Employé modifié avec succès !</div>
    <?php endif; ?>
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
