// Sélection des onglets et des sections
const tabs = document.querySelectorAll('.trips-tab a');
const sections = {
  upcoming: document.getElementById('upcoming'),
  ongoing: document.getElementById('ongoing'),
  past: document.getElementById('past')
};

// Fonction pour afficher la section correspondante
function showSection(event) {
  if (event) {
    event.preventDefault(); // empêche le # dans l'URL pour les onglets
  }

  const targetId = event ? this.getAttribute('href').substring(1) : 'upcoming';

  // Masquer toutes les sections
  Object.values(sections).forEach(sec => sec.style.display = 'none');

  // Afficher la section ciblée
  if (sections[targetId]) {
    sections[targetId].style.display = 'block';
  }

  // Gestion de la classe active sur les onglets
  tabs.forEach(tab => tab.classList.remove('active'));
  const activeTab = Array.from(tabs).find(tab => tab.getAttribute('href').substring(1) === targetId);
  if (activeTab) activeTab.classList.add('active');
}

// Attacher l'événement à chaque onglet
tabs.forEach(tab => tab.addEventListener('click', showSection));

// Afficher "À venir" par défaut au chargement
showSection();