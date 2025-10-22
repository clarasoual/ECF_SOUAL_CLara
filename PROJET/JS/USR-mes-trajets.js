// Sélection des onglets
const tabs = document.querySelectorAll('.trips-tab a');
const sections = {
  upcoming: document.getElementById('upcoming'),
  ongoing: document.getElementById('ongoing'),
  past: document.getElementById('past')
};

// Fonction pour afficher la section correspondant à l'onglet
function showSection(event) {
  if (event) event.preventDefault(); // empêche le # dans l'URL

  const targetId = event ? this.getAttribute('href').substring(1) : 'upcoming';

  // Masquer toutes les sections
  Object.values(sections).forEach(sec => sec.style.display = 'none');

  // Afficher la section ciblée
  sections[targetId].style.display = 'block';

  // Gestion classe active
  tabs.forEach(tab => tab.classList.remove('active'));
  const activeTab = Array.from(tabs).find(tab => tab.getAttribute('href').substring(1) === targetId);
  if (activeTab) activeTab.classList.add('active');
}

// Attacher l'événement à chaque onglet
tabs.forEach(tab => tab.addEventListener('click', showSection));

// --- Initialisation au chargement : afficher "À venir" ---
showSection();
