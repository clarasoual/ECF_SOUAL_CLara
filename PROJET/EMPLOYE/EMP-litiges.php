<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

$stmt = $bdd->prepare("
    SELECT 
        s.id AS id_signalement,
        s.motif,
        s.statut AS statut_signalement,
        s.note_employe,
        t.id AS id_trajet,
        t.depart,
        t.arrivee,
        t.date_depart,
        t.heure_depart,
        u_passager.prenom AS prenom_passager,
        u_passager.nom AS nom_passager,
        u_passager.email AS email_passager,
        u_conducteur.prenom AS prenom_conducteur,
        u_conducteur.nom AS nom_conducteur,
        u_conducteur.email AS email_conducteur
    FROM signalements s
    JOIN trajets t ON t.id = s.id_trajet
    JOIN utilisateurs u_passager ON u_passager.id = s.id_utilisateur
    JOIN utilisateurs u_conducteur ON u_conducteur.id = t.id_conducteur
    ORDER BY s.date_creation DESC
");
$stmt->execute();
$litiges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace employé - Litiges</title>
    <link rel="stylesheet" href="../CSS/style_global.css">
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-gestion-avis.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .modal-note {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-note.active { display: flex; }
        .modal-note-content {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            width: 500px;
            max-width: 90%;
        }
        .modal-note-content h3 { margin-top: 0; }
        .modal-note-content textarea {
            width: 100%;
            height: 150px;
            margin: 1rem 0;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: 'Quicksand', sans-serif;
            resize: vertical;
            box-sizing: border-box;
        }
        .modal-note-actions { display: flex; gap: 1rem; justify-content: flex-end; }
    </style>
</head>
<body>

<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<main>
    <?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section class="reviews-moderation">
        <h2>Covoiturages signalés</h2>

        <?php if (empty($litiges)): ?>
            <p>Aucun litige. ✅</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>N° trajet</th>
                    <th>Trajet</th>
                    <th>Date</th>
                    <th>Passager</th>
                    <th>Mail passager</th>
                    <th>Conducteur</th>
                    <th>Mail conducteur</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Note employé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($litiges as $l): ?>
                <tr data-id="<?= $l['id_signalement'] ?>"
                    data-note="<?= htmlspecialchars($l['note_employe'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <td><?= $l['id_trajet'] ?></td>
                    <td><?= htmlspecialchars($l['depart']) ?> → <?= htmlspecialchars($l['arrivee']) ?></td>
                    <td><?= date('d/m/Y', strtotime($l['date_depart'])) ?> à <?= htmlspecialchars($l['heure_depart']) ?></td>
                    <td><?= htmlspecialchars($l['prenom_passager'] . ' ' . $l['nom_passager']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($l['email_passager']) ?>"><?= htmlspecialchars($l['email_passager']) ?></a></td>
                    <td><?= htmlspecialchars($l['prenom_conducteur'] . ' ' . $l['nom_conducteur']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($l['email_conducteur']) ?>"><?= htmlspecialchars($l['email_conducteur']) ?></a></td>
                    <td><?= htmlspecialchars($l['motif'] ?? '—') ?></td>
                    <td class="statut-cell">
                        <?php if ($l['statut_signalement'] === 'en_cours'): ?>
                            ⏳ En cours
                        <?php elseif ($l['statut_signalement'] === 'resolu_credits_verses'): ?>
                            ✅ Crédits versés
                        <?php else: ?>
                            ❌ Crédits bloqués
                        <?php endif; ?>
                    </td>
                    <td class="note-cell">
                        <button type="button" class="btn-attente btn-ouvrir-note"
                                data-id="<?= $l['id_signalement'] ?>">
                            <?= !empty($l['note_employe']) ? '✏️ Voir la note' : '✏️ Ajouter une note' ?>
                        </button>
                    </td>
                    <td>
                        <?php if ($l['statut_signalement'] === 'en_cours'): ?>
                            <button type="button" class="btn-valider"
                                    data-id="<?= $l['id_signalement'] ?>"
                                    data-action="debloquer">
                                ✅ Débloquer crédits
                            </button>
                            <button type="button" class="btn-refuser"
                                    data-id="<?= $l['id_signalement'] ?>"
                                    data-action="bloquer">
                                ❌ Bloquer crédits
                            </button>
                        <?php else: ?>
                            <span>Traité</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>
</main>

<!-- Modale note employé -->
<div id="modal-note" class="modal-note">
    <div class="modal-note-content">
        <h3>✏️ Note de l'employé</h3>
        <textarea id="textarea-note" placeholder="Décrivez ici votre analyse du litige, les contacts pris, la décision..."></textarea>
        <div class="modal-note-actions">
            <button type="button" id="btn-fermer-note" class="btn-refuser">Fermer</button>
            <button type="button" id="btn-sauver-note" class="btn-valider">Enregistrer</button>
        </div>
    </div>
</div>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>

<script>
let idSignalementActif = null;

// Ouvrir la modale avec la note existante
document.querySelectorAll('.btn-ouvrir-note').forEach(btn => {
    btn.addEventListener('click', () => {
        idSignalementActif = btn.dataset.id;
        const ligne = btn.closest('tr');
        document.getElementById('textarea-note').value = ligne.dataset.note || '';
        document.getElementById('modal-note').classList.add('active');
    });
});

// Fermer la modale
document.getElementById('btn-fermer-note').addEventListener('click', () => {
    document.getElementById('modal-note').classList.remove('active');
});

document.getElementById('modal-note').addEventListener('click', (e) => {
    if (e.target === document.getElementById('modal-note')) {
        document.getElementById('modal-note').classList.remove('active');
    }
});

// Enregistrer la note en BDD via Fetch
document.getElementById('btn-sauver-note').addEventListener('click', () => {
    const note = document.getElementById('textarea-note').value.trim();
    if (!idSignalementActif) return;

    const formData = new FormData();
    formData.append('id_signalement', idSignalementActif);
    formData.append('note_employe', note);

    fetch('../PHP/sauver-note-litige.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const ligne = document.querySelector(`tr[data-id="${idSignalementActif}"]`);
                if (ligne) {
                    ligne.dataset.note = note;
                    const btnNote = ligne.querySelector('.btn-ouvrir-note');
                    if (btnNote) btnNote.textContent = note ? '✏️ Voir la note' : '✏️ Ajouter une note';
                }
                document.getElementById('modal-note').classList.remove('active');
                afficherToast('✅ Note enregistrée !');
            } else {
                afficherToast('❌ Erreur lors de l\'enregistrement.', 'error');
            }
        })
        .catch(() => afficherToast('❌ Erreur réseau.', 'error'));
});

// Toast
function afficherToast(message, type = 'success') {
    const ancien = document.getElementById('toast-litige');
    if (ancien) ancien.remove();
    const toast = document.createElement('div');
    toast.id = 'toast-litige';
    toast.textContent = message;
    toast.style.cssText = `position:fixed;bottom:20px;right:20px;background:${type === 'success' ? '#4BB543' : '#e74c3c'};color:white;padding:12px 20px;border-radius:8px;z-index:9999;font-family:'Quicksand',sans-serif;`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Actions débloquer / bloquer
document.querySelector('tbody')?.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;

    const action = btn.dataset.action;
    const id     = btn.dataset.id;
    const ligne  = btn.closest('tr');
    const note   = ligne.dataset.note || '';

    const confirmMsg = action === 'debloquer'
        ? 'Confirmer le déblocage des crédits au chauffeur ?'
        : 'Confirmer le blocage définitif des crédits ?';

    if (!confirm(confirmMsg)) return;

    btn.disabled = true;
    btn.style.opacity = '0.5';

    const formData = new FormData();
    formData.append('id_signalement', id);
    formData.append('action', action);
    formData.append('note_employe', note);

    fetch('../PHP/traiter-litige.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                ligne.querySelector('.statut-cell').textContent = action === 'debloquer' ? '✅ Crédits versés' : '❌ Crédits bloqués';
                ligne.querySelector('.note-cell').innerHTML     = note || '—';
                ligne.querySelector('td:last-child').innerHTML  = '<span>Traité</span>';
                afficherToast(action === 'debloquer' ? '✅ Crédits débloqués et versés au chauffeur !' : '❌ Crédits bloqués définitivement.');
            } else {
                afficherToast('❌ Erreur : ' + (data.message || 'réessayez.'), 'error');
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        })
        .catch(() => {
            afficherToast('❌ Erreur réseau.', 'error');
            btn.disabled = false;
            btn.style.opacity = '1';
        });
});
</script>
</body>
</html>