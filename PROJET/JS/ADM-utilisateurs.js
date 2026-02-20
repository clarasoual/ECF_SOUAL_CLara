/* ---------------------- POP UP OUVRIR-FERMER ---------------------- */
const popButtons = document.querySelectorAll('[data-popup]');
popButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const popup = document.querySelector(btn.dataset.popup);
        if (popup) popup.style.display = 'block';
    });
});

const closeButtons = document.querySelectorAll('.popup-close');
closeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const popup = btn.closest('.popup');
        if (popup) popup.style.display = 'none';
    });
});

/* ---------------------- AJOUTER UN UTILISATEUR ---------------------- */
const addForm = document.querySelector('#form-ajouter-employe');
const userTable = document.querySelector('table tbody');

if (addForm && userTable) {
    addForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const nom = addForm.querySelector('input[placeholder="Nom"]').value.trim();
        const prenom = addForm.querySelector('input[placeholder="Prénom"]').value.trim();
        const email = addForm.querySelector('input[placeholder="Email"]').value.trim();

        if (!nom || !prenom || !email) {
            alert("Veuillez remplir tous les champs.");
            return;
        }

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${nom}</td>
            <td>${prenom}</td>
            <td>${email}</td>
            <td>
                <button data-popup="#popup-modifier-employe">Modifier</button>
                <button class="btn-supprimer">Supprimer</button>
            </td>
        `;
        userTable.appendChild(newRow);

        addForm.reset();
        addForm.closest('.popup').style.display = 'none';
        showMessage("Utilisateur ajouté !");
        sortTable();
    });
}

/* ---------------------- SUPPRIMER ---------------------- */
if (userTable) {
    userTable.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-supprimer')) {
            const confirmation = confirm("Voulez-vous vraiment supprimer cet utilisateur ?");
            if (confirmation) {
                e.target.closest('tr').remove();
                showMessage("Utilisateur supprimé !");
            }
        }

        if (e.target.textContent === 'Modifier') {
            openModifyPopup(e.target);
        }
    });
}

/* ---------------------- MODIFIER ---------------------- */
const modifyPopup = document.querySelector('#popup-modifier-employe');
const modifyForm = document.querySelector('#form-modifier-employe');
let currentRow = null;

function openModifyPopup(btn) {
    const row = btn.closest('tr');
    currentRow = row;

    const cells = row.querySelectorAll('td');
    const inputs = modifyForm.querySelectorAll('input');

    // Remplir les champs dans l’ordre
    inputs.forEach((input, i) => {
        input.value = cells[i]?.textContent?.trim() || "";
    });

    modifyPopup.style.display = 'block';
}

if (modifyForm) {
    modifyForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!currentRow) return;

        const inputs = modifyForm.querySelectorAll('input');
        const values = Array.from(inputs).map(input => input.value.trim());
        const cells = currentRow.querySelectorAll('td');

        for (let i = 0; i < Math.min(cells.length - 1, values.length); i++) {
            cells[i].textContent = values[i];
        }

        modifyPopup.style.display = 'none';
        showMessage("Utilisateur modifié !");
        sortTable();
        currentRow = null;
    });
}

/* ---------------------- TRI ALPHABÉTIQUE ---------------------- */
function sortTable() {
    const rows = Array.from(userTable.querySelectorAll('tr'));
    rows.sort((a, b) => {
        const nameA = a.children[0].textContent.toLowerCase();
        const nameB = b.children[0].textContent.toLowerCase();
        return nameA.localeCompare(nameB);
    });
    rows.forEach(row => userTable.appendChild(row));
}

/* ---------------------- MESSAGE D’ACTION ---------------------- */
function showMessage(message) {
    const msg = document.createElement('div');
    msg.className = 'message visible';
    msg.textContent = message;
    document.body.appendChild(msg);
    setTimeout(() => msg.remove(), 2000);
}

