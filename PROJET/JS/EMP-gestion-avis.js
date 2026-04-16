document.addEventListener('DOMContentLoaded', () => {

    // ────────────────────────────────────────
    // Toast
    // ────────────────────────────────────────

    function afficherToast(message, type = 'success') {
        const ancien = document.getElementById('toast-dynamique');
        if (ancien) ancien.remove();

        const toast = document.createElement('div');
        toast.id = 'toast-dynamique';
        toast.textContent = message;
        toast.style.position     = 'fixed';
        toast.style.bottom       = '20px';
        toast.style.right        = '20px';
        toast.style.background   = type === 'success' ? '#4BB543' : '#e74c3c';
        toast.style.color        = 'white';
        toast.style.padding      = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.fontFamily   = 'Quicksand, sans-serif';
        toast.style.zIndex       = 9999;
        toast.style.transition   = 'opacity 0.5s';
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    // ────────────────────────────────────────
    // Intercepter les clics sur les boutons d'action
    // ────────────────────────────────────────

    document.querySelector('tbody')?.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;

        e.preventDefault();

        const action  = btn.dataset.action;
        const avisId  = btn.dataset.avisId;
        const ligne   = btn.closest('tr');

        // Confirmation pour suppression
        if (action === 'supprimer') {
            if (!confirm('Supprimer définitivement cet avis ?')) return;
        }

        // Désactiver le bouton pendant le fetch
        btn.disabled = true;
        btn.style.opacity = '0.5';

        const formData = new FormData();
        formData.append('avis_id', avisId);
        formData.append('action', action);

        fetch('../PHP/traiter-avis.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Faire disparaître la ligne avec animation
                ligne.style.transition = 'opacity 0.4s';
                ligne.style.opacity    = '0';
                setTimeout(() => {
                    ligne.remove();

                    // Si plus aucune ligne, afficher message
                    const tbody = document.querySelector('tbody');
                    if (tbody && tbody.querySelectorAll('tr').length === 0) {
                        const table = document.querySelector('table');
                        if (table) {
                            table.insertAdjacentHTML('afterend', '<p>Aucun avis dans cette catégorie.</p>');
                            table.remove();
                        }
                    }
                }, 400);

                const messages = {
                    'valider'            : '✅ Avis validé !',
                    'refuser'            : '❌ Avis refusé.',
                    'remettre_en_attente': '🔄 Avis remis en attente.',
                    'supprimer'          : '🗑️ Avis supprimé.'
                };
                afficherToast(messages[action] || '✅ Action effectuée.');

            } else {
                afficherToast('❌ Erreur : ' + (data.message || 'réessayez.'), 'error');
                btn.disabled      = false;
                btn.style.opacity = '1';
            }
        })
        .catch(() => {
            afficherToast('❌ Erreur réseau, réessayez.', 'error');
            btn.disabled      = false;
            btn.style.opacity = '1';
        });
    });

});