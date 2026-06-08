<?php
require_once('../PHP/auth.php');
requireEmploye();
require_once('../PHP/connexion.php');

// --- Litiges passager→conducteur (existants) ---
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
    -- Exclure les signalements faits par le conducteur sur ses passagers
    -- On les distingue : le conducteur du trajet ≠ auteur = passager
    -- Ici on garde uniquement ceux où id_utilisateur est un PASSAGER du trajet
    JOIN trajets_passagers tp ON tp.id_trajet = t.id AND tp.id_passager = s.id_utilisateur
    ORDER BY s.date_creation DESC
");
$stmt->execute();
$litiges = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Signalements conducteur→passager ---
// id_utilisateur dans signalements = passager signalé
// Le conducteur est retrouvé via t.id_conducteur
// On distingue ces signalements en vérifiant que id_utilisateur N'EST PAS passager du trajet
// (car quand un passager signale, id_utilisateur = passager ; quand conducteur signale, id_utilisateur = passager signalé)
// La distinction propre : on utilise une colonne source OU on vérifie si l'auteur est le conducteur
// Ici on récupère tous les signalements où id_utilisateur est passager (tp existe)
// et on les répartit selon si un avis conducteur existe pour cette paire

// En pratique : on récupère les signalements liés à un passager (via trajets_passagers)
// et on récupère le conducteur depuis trajets
$stmtSignalCP = $bdd->prepare("
    SELECT
        s.id AS id_signalement,
        s.motif,
        s.statut AS statut_signalement,
        s.note_employe,
        s.date_creation,
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
    -- Signalement vient du conducteur sur le passager :
    -- il existe un avis de id_conducteur vers id_passager pour ce trajet
    JOIN avis a ON a.id_trajet = s.id_trajet
                AND a.id_auteur = t.id_conducteur
                AND a.id_destinataire = s.id_utilisateur
    ORDER BY s.date_creation DESC
");
$stmtSignalCP->execute();
$signalementsCP = $stmtSignalCP->fetchAll(PDO::FETCH_ASSOC);
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

    <div class="litiges-container">

        <!-- =============================================
             SECTION 1 : Litiges passager → conducteur
             ============================================= -->
        <section class="reviews-moderation">
            <h2>Covoiturages signalés <span class="section-sous-titre">— Passager signale un conducteur</span></h2>

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

        <!-- =============================================
             SECTION 2 : Signalements conducteur → passager
             ============================================= -->
        <section class="reviews-moderation signalements-cp">
            <h2>Signalements passagers <span class="section-sous-titre">— Conducteur signale un passager</span></h2>

            <?php if (empty($signalementsCP)): ?>
                <p>Aucun signalement conducteur → passager. ✅</p>
            <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>N° trajet</th>
                            <th>Trajet</th>
                            <th>Date</th>
                            <th>Conducteur</th>
                            <th>Passager signalé</th>
                            <th>Statut</th>
                            <th>Détails</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signalementsCP as $s): ?>
                        <tr class="row-cp"
                            data-id="<?= $s['id_signalement'] ?>"
                            data-note="<?= htmlspecialchars($s['note_employe'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-motif="<?= htmlspecialchars($s['motif'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-email-passager="<?= htmlspecialchars($s['email_passager'], ENT_QUOTES, 'UTF-8') ?>"
                            data-email-conducteur="<?= htmlspecialchars($s['email_conducteur'], ENT_QUOTES, 'UTF-8') ?>"
                            data-label-plaignant="Conducteur"
                            data-label-vise="Passager signalé">
                            <td><?= $s['id_trajet'] ?></td>
                            <td><?= htmlspecialchars($s['depart']) ?> → <?= htmlspecialchars($s['arrivee']) ?></td>
                            <td><?= date('d/m/Y', strtotime($s['date_depart'])) ?> à <?= htmlspecialchars($s['heure_depart']) ?></td>
                            <td><?= htmlspecialchars($s['prenom_conducteur'] . ' ' . $s['nom_conducteur']) ?></td>
                            <td><?= htmlspecialchars($s['prenom_passager'] . ' ' . $s['nom_passager']) ?></td>
                            <td class="statut-cell">
                                <?= $s['statut_signalement'] === 'en_cours' ? '⏳ En cours' : '✅ Traité' ?>
                            </td>
                            <td>
                                <button type="button" class="btn-attente btn-ouvrir-details-cp" data-id="<?= $s['id_signalement'] ?>">
                                    🔍 Voir détails
                                </button>
                            </td>
                            <td class="actions-cell">
                                <?php if ($s['statut_signalement'] === 'en_cours'): ?>
                                    <button type="button" class="btn-valider btn-cp-action"
                                        data-id="<?= $s['id_signalement'] ?>"
                                        data-action="traite">
                                        ✅ Marquer comme traité
                                    </button>
                                    <button type="button" class="btn-suspendre btn-cp-action"
                                        data-id="<?= $s['id_signalement'] ?>"
                                        data-action="suspendre_passager">
                                        🚫 Suspendre le passager
                                    </button>
                                <?php else: ?>
                                    <span class="action-effectuee action-verte">✅ Traité</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>

    </div><!-- /.litiges-container -->
</main>

<!-- Modale détails litiges passager→conducteur -->
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

<!-- Modale détails signalements conducteur→passager -->
<div id="modal-details-cp" class="modal-litige">
    <div class="modal-litige-content">
        <div class="modal-litige-header">
            <h3>🔍 Détails du signalement</h3>
            <button type="button" id="btn-fermer-details-cp" class="btn-close">✕</button>
        </div>
        <div class="modal-litige-body">
            <div class="detail-row">
                <span class="detail-label">Conducteur (auteur du signalement)</span>
                <a id="cp-detail-email-conducteur" href="#" class="detail-value"></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">Passager signalé</span>
                <a id="cp-detail-email-passager" href="#" class="detail-value"></a>
            </div>
            <div class="detail-row">
                <span class="detail-label">Motif</span>
                <span id="cp-detail-motif" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Note employé</span>
                <span id="cp-detail-note" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Ajouter / modifier la note</span>
                <textarea id="cp-textarea-note" placeholder="Votre analyse, contacts pris, décision..."></textarea>
            </div>
        </div>
        <div class="modal-litige-actions">
            <button type="button" id="btn-fermer-details-cp-bas" class="btn-refuser">Fermer</button>
            <button type="button" id="btn-sauver-note-cp" class="btn-valider">Enregistrer la note</button>
        </div>
    </div>
</div>

<?php include('../COMPONENTS/COMP-footer-employe.php'); ?>

<script>
/* ===================================================
   MODALE LITIGES passager→conducteur
   =================================================== */
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

document.getElementById('modal-details').addEventListener('click', e => {
    if (e.target === document.getElementById('modal-details'))
        document.getElementById('modal-details').classList.remove('active');
});

document.getElementById('btn-sauver-note').addEventListener('click', () => {
    sauverNote(idSignalementActif, 'textarea-note', 'detail-note', 'modal-details');
});

/* ===================================================
   MODALE SIGNALEMENTS conducteur→passager
   =================================================== */
let idSignalementCPActif = null;

document.querySelectorAll('.btn-ouvrir-details-cp').forEach(btn => {
    btn.addEventListener('click', () => {
        idSignalementCPActif = btn.dataset.id;
        const ligne = btn.closest('tr');
        document.getElementById('cp-detail-email-conducteur').textContent = ligne.dataset.emailConducteur;
        document.getElementById('cp-detail-email-conducteur').href = 'mailto:' + ligne.dataset.emailConducteur;
        document.getElementById('cp-detail-email-passager').textContent = ligne.dataset.emailPassager;
        document.getElementById('cp-detail-email-passager').href = 'mailto:' + ligne.dataset.emailPassager;
        document.getElementById('cp-detail-motif').textContent = ligne.dataset.motif || '—';
        document.getElementById('cp-detail-note').textContent = ligne.dataset.note || '—';
        document.getElementById('cp-textarea-note').value = ligne.dataset.note || '';
        document.getElementById('modal-details-cp').classList.add('active');
    });
});

['btn-fermer-details-cp', 'btn-fermer-details-cp-bas'].forEach(id => {
    document.getElementById(id).addEventListener('click', () => {
        document.getElementById('modal-details-cp').classList.remove('active');
    });
});

document.getElementById('modal-details-cp').addEventListener('click', e => {
    if (e.target === document.getElementById('modal-details-cp'))
        document.getElementById('modal-details-cp').classList.remove('active');
});

document.getElementById('btn-sauver-note-cp').addEventListener('click', () => {
    sauverNote(idSignalementCPActif, 'cp-textarea-note', 'cp-detail-note', 'modal-details-cp');
});

/* ===================================================
   FONCTION COMMUNE : sauvegarder une note employé
   =================================================== */
function sauverNote(id, textareaId, detailId, modalId) {
    const note = document.getElementById(textareaId).value.trim();
    if (!id) return;
    const formData = new FormData();
    formData.append('id_signalement', id);
    formData.append('note_employe', note);
    fetch('../PHP/sauver-note-litige.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const ligne = document.querySelector(`tr[data-id="${id}"]`);
                if (ligne) ligne.dataset.note = note;
                document.getElementById(detailId).textContent = note || '—';
                document.getElementById(modalId).classList.remove('active');
                afficherToast('✅ Note enregistrée !');
            } else {
                afficherToast('❌ Erreur lors de l\'enregistrement.', 'error');
            }
        })
        .catch(() => afficherToast('❌ Erreur réseau.', 'error'));
}

/* ===================================================
   ACTIONS litiges passager→conducteur
   =================================================== */
document.querySelector('.reviews-moderation:not(.signalements-cp) tbody')
    ?.addEventListener('click', e => {
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
    btn.disabled = true; btn.style.opacity = '0.5';

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
                    'debloquer': 'action-verte',
                    'bloquer': 'action-rouge',
                    'bloquer_suspendre': 'action-rouge'
                };
                ligne.querySelector('.actions-cell').innerHTML =
                    `<span class="action-effectuee ${actionClasses[action]}">${data.statut_affiche}</span>`;
                afficherToast('Action effectuée avec succès.');
            } else {
                afficherToast('❌ Erreur : ' + (data.message || 'réessayez.'), 'error');
                btn.disabled = false; btn.style.opacity = '1';
            }
        })
        .catch(() => {
            afficherToast('❌ Erreur réseau.', 'error');
            btn.disabled = false; btn.style.opacity = '1';
        });
});

/* ===================================================
   ACTIONS signalements conducteur→passager
   =================================================== */
document.querySelector('.signalements-cp tbody')?.addEventListener('click', e => {
    const btn = e.target.closest('button.btn-cp-action');
    if (!btn) return;

    const action = btn.dataset.action;
    const id     = btn.dataset.id;
    const ligne  = btn.closest('tr');

    const confirmMessages = {
        'traite':            'Marquer ce signalement comme traité ?',
        'suspendre_passager':'Suspendre ce passager ? Cette action est irréversible.'
    };

    if (!confirm(confirmMessages[action])) return;
    btn.disabled = true; btn.style.opacity = '0.5';

    const formData = new FormData();
    formData.append('id_signalement', id);
    formData.append('action', action);

    fetch('../PHP/traiter-signalement-passager.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                ligne.querySelector('.statut-cell').textContent = '✅ Traité';
                ligne.querySelector('.actions-cell').innerHTML =
                    `<span class="action-effectuee action-verte">✅ ${data.statut_affiche}</span>`;
                afficherToast('Action effectuée avec succès.');
            } else {
                afficherToast('❌ Erreur : ' + (data.message || 'réessayez.'), 'error');
                btn.disabled = false; btn.style.opacity = '1';
            }
        })
        .catch(() => {
            afficherToast('❌ Erreur réseau.', 'error');
            btn.disabled = false; btn.style.opacity = '1';
        });
});

/* ===================================================
   TOAST
   =================================================== */
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
</script>
</body>
</html>