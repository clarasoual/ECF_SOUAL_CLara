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
    <link rel="stylesheet" href="../CSS/CSS EMPLOYE/EMP-litiges.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../COMPONENTS/COMP-header-employe.php'); ?>

<select class="menu-principal-select" onchange="window.location.href=this.value">
    <option value="">— Navigation —</option>
    <option value="EMP-gestion-avis.php">Avis à valider</option>
    <option value="EMP-litiges.php">Covoiturages signalés</option>
    <option value="EMP-demandes-credits.php">Demandes de crédits</option>
</select>

<main>
    <?php include('../COMPONENTS/COMP-menu-employe.html'); ?>

    <section class="reviews-moderation">
        <h2>Covoiturages signalés</h2>

        <?php if (empty($litiges)): ?>
            <p>Aucun litige. ✅</p>
        <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N° trajet</th>
                        <th>Trajet</th>
                        <th>Date</th>
                        <th>Passager</th>
                        <th>Conducteur</th>
                        <th>Statut</th>
                        <th>Détails</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($litiges as $l): ?>
                    <tr data-id="<?= $l['id_signalement'] ?>"
                        data-note="<?= htmlspecialchars($l['note_employe'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-motif="<?= htmlspecialchars($l['motif'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        data-email-passager="<?= htmlspecialchars($l['email_passager'], ENT_QUOTES, 'UTF-8') ?>"
                        data-email-conducteur="<?= htmlspecialchars($l['email_conducteur'], ENT_QUOTES, 'UTF-8') ?>">
                        <td><?= $l['id_trajet'] ?></td>
                        <td><?= htmlspecialchars($l['depart']) ?> → <?= htmlspecialchars($l['arrivee']) ?></td>
                        <td><?= date('d/m/Y', strtotime($l['date_depart'])) ?> à <?= htmlspecialchars($l['heure_depart']) ?></td>
                        <td><?= htmlspecialchars($l['prenom_passager'] . ' ' . $l['nom_passager']) ?></td>
                        <td><?= htmlspecialchars($l['prenom_conducteur'] . ' ' . $l['nom_conducteur']) ?></td>

                        <td class="statut-cell">
                            <?= $l['statut_signalement'] === 'en_cours' ? '⏳ En cours' : '✅ Traité' ?>
                        </td>

                        <td>
                            <button type="button" class="btn-attente btn-ouvrir-details" data-id="<?= $l['id_signalement'] ?>">
                                🔍 Voir détails
                            </button>
                        </td>

                        <td class="actions-cell">
                            <?php if ($l['statut_signalement'] === 'en_cours'): ?>
                                <button type="button" class="btn-valider" data-id="<?= $l['id_signalement'] ?>" data-action="debloquer">
                                    ✅ Verser les crédits au conducteur
                                </button>
                                <button type="button" class="btn-refuser" data-id="<?= $l['id_signalement'] ?>" data-action="bloquer">
                                    ❌ Bloquer les crédits
                                </button>
                                <button type="button" class="btn-suspendre" data-id="<?= $l['id_signalement'] ?>" data-action="bloquer_suspendre">
                                    🚫 Bloquer + Suspendre le conducteur
                                </button>
                            <?php elseif ($l['statut_signalement'] === 'resolu_credits_verses'): ?>
                                <span class="action-effectuee action-verte">✅ Crédits versés au conducteur</span>
                            <?php else: ?>
                                <span class="action-effectuee action-rouge">❌ Crédits bloqués</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>
</main>

<div id="modal-details" class="modal-litige">
    <div class="modal-litige-content">
        <div class="modal-litige-header">
            <h3>🔍 Détails du litige</h3>
            <button type="button" id="btn-fermer-details" class="btn-close">✕</button>
        </div>
        <div class="modal-litige-body">
            <div class="detail-row">
                <span class="detail-label">Mail passager</span>
                <a id="detail-email-passager" href="#" class="detail-value"></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">Mail conducteur</span>
                <a id="detail-email-conducteur" href="#" class="detail-value"></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">Motif</span>
                <span id="detail-motif" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Note employé</span>
                <span id="detail-note" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Ajouter / modifier la note</span>
                <textarea id="textarea-note" placeholder="Votre analyse, contacts pris, décision..."></textarea>
            </div>
        </div>
        <div class="modal-litige-actions">
            <button type="button" id="btn-fermer-details-bas" class="btn-refuser">Fermer</button>
            <button type="button" id="btn-sauver-note" class="btn-valider">Enregistrer la note</button>
        </div>
    </div>
</div>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>

<script>
let idSignalementActif = null;

document.querySelectorAll('.btn-ouvrir-details').forEach(btn => {
    btn.addEventListener('click', () => {
        idSignalementActif = btn.dataset.id;
        const ligne = btn.closest('tr');
        document.getElementById('detail-email-passager').textContent = ligne.dataset.emailPassager;
        document.getElementById('detail-email-passager').href = 'mailto:' + ligne.dataset.emailPassager;
        document.getElementById('detail-email-conducteur').textContent = ligne.dataset.emailConducteur;
        document.getElementById('detail-email-conducteur').href = 'mailto:' + ligne.dataset.emailConducteur;
        document.getElementById('detail-motif').textContent = ligne.dataset.motif || '—';
        document.getElementById('detail-note').textContent = ligne.dataset.note || '—';
        document.getElementById('textarea-note').value = ligne.dataset.note || '';
        document.getElementById('modal-details').classList.add('active');
    });
});

['btn-fermer-details', 'btn-fermer-details-bas'].forEach(id => {
    document.getElementById(id).addEventListener('click', () => {
        document.getElementById('modal-details').classList.remove('active');
    });
});

document.getElementById('modal-details').addEventListener('click', (e) => {
    if (e.target === document.getElementById('modal-details'))
        document.getElementById('modal-details').classList.remove('active');
});

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
                if (ligne) ligne.dataset.note = note;
                document.getElementById('detail-note').textContent = note || '—';
                document.getElementById('modal-details').classList.remove('active');
                afficherToast('✅ Note enregistrée !');
            } else {
                afficherToast('❌ Erreur lors de l\'enregistrement.', 'error');
            }
        })
        .catch(() => afficherToast('❌ Erreur réseau.', 'error'));
});

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

document.querySelector('tbody')?.addEventListener('click', (e) => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;

    const action = btn.dataset.action;
    const id     = btn.dataset.id;
    const ligne  = btn.closest('tr');
    const note   = ligne.dataset.note || '';

    const confirmMessages = {
        'debloquer':        'Confirmer le versement des crédits au conducteur ?',
        'bloquer':          'Confirmer le blocage des crédits ?',
        'bloquer_suspendre':'Confirmer le blocage des crédits ET la suspension du conducteur ? Cette action est irréversible.'
    };

    if (!confirm(confirmMessages[action])) return;

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
                ligne.querySelector('.statut-cell').textContent = '✅ Traité';
                const actionClasses = {
                    'debloquer':        'action-verte',
                    'bloquer':          'action-rouge',
                    'bloquer_suspendre':'action-rouge'
                };
                ligne.querySelector('.actions-cell').innerHTML =
                    `<span class="action-effectuee ${actionClasses[action]}">${data.statut_affiche}</span>`;
                afficherToast('Action effectuée avec succès.');
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