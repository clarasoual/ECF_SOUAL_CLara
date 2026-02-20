const vehiclesContainer = document.getElementById('vehicles-container');

// Filtrer les modèles selon la marque
function filterModels(form) {
  const brand = form.querySelector('.brand').value;
  const model = form.querySelector('.model');
  Array.from(model.options).forEach(opt => {
    opt.hidden = opt.dataset.marque && opt.dataset.marque !== brand;
  });
  if (model.selectedOptions.length && model.selectedOptions[0].hidden) model.value = "";
}

// Validation de la plaque
function validatePlate(plate) {
  return /^[A-Z]{2}-\d{3}-[A-Z]{2}$/.test(plate);
}

// Crée une carte véhicule
function createVehicleCard(data, index) {
  const card = document.createElement('div');
  card.className = 'vehicle-card';
  card.dataset.index = index;
  card.innerHTML = `
    <h2>Véhicule numéro ${index + 1}</h2>
    <p><strong>Plaque:</strong> ${data.plate}</p>
    <p><strong>Date:</strong> ${data.date}</p>
    <p><strong>Marque:</strong> ${data.brand}</p>
    <p><strong>Modèle:</strong> ${data.model}</p>
    <p><strong>Couleur:</strong> ${data.color}</p>
    <p><strong>Places:</strong> ${data.seats}</p>
    <p><strong>Animaux:</strong> ${data.pets || 'Non renseigné'}</p>
    <p><strong>Fumeur:</strong> ${data.smoking || 'Non renseigné'}</p>
    <p><strong>Musique:</strong> ${data.music || 'Non renseigné'}</p>
  `;
  return card;
}

// Remplacer formulaire par carte
function showVehicleCard(container, data, index) {
  container.innerHTML = '';
  container.appendChild(createVehicleCard(data, index));
}

// Enregistrer le formulaire
function saveForm(e) {
  e.preventDefault();
  const form = e.target;
  const containerDiv = form.closest('.driver-info-container');
  const index = Array.from(vehiclesContainer.children).indexOf(containerDiv);

  const data = {
    plate: form.querySelector('.plate').value.trim(),
    date: form.querySelector('.date').value,
    brand: form.querySelector('.brand').value,
    model: form.querySelector('.model').value,
    color: form.querySelector('.color').value,
    seats: form.querySelector('.seats').value,
    pets: form.querySelector(`input[name="pets${index}"]:checked`)?.value || '',
    smoking: form.querySelector(`input[name="smoking${index}"]:checked`)?.value || '',
    music: form.querySelector('.music').value
  };

  // Validation simple
  const errors = [];
  if (!data.plate) errors.push("Plaque obligatoire");
  else if (!validatePlate(data.plate)) errors.push("Format invalide (AB-123-CD)");
  if (!data.date) errors.push("Date obligatoire");
  if (!data.brand) errors.push("Marque obligatoire");
  if (!data.model) errors.push("Modèle obligatoire");
  if (!data.color) errors.push("Couleur obligatoire");
  if (!data.seats) errors.push("Nombre de places obligatoire");
  if (errors.length) return alert(errors.join("\n"));

  // Sauvegarde dans localStorage
  const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
  dataList[index] = data;
  localStorage.setItem('driverInfoList', JSON.stringify(dataList));

  showVehicleCard(containerDiv, data, index);
}

// Préremplir formulaire
function prefillForm(form, index) {
  const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
  const data = dataList[index] || {};
  form.querySelector('.plate').value = data.plate || "";
  form.querySelector('.date').value = data.date || "";
  form.querySelector('.brand').value = data.brand || "";
  filterModels(form);
  form.querySelector('.model').value = data.model || "";
  form.querySelector('.color').value = data.color || "";
  form.querySelector('.seats').value = data.seats || "";
  form.querySelector('.music').value = data.music || "";
  if (data.pets) form.querySelector(`input[name="pets${index}"][value="${data.pets}"]`)?.checked = true;
  if (data.smoking) form.querySelector(`input[name="smoking${index}"][value="${data.smoking}"]`)?.checked = true;
}

// Setup formulaire
function setupForm(form, index) {
  // Mettre des noms uniques aux radios
  form.querySelectorAll('input[type="radio"]').forEach(input => {
    if (input.name === "pets") input.name = `pets${index}`;
    if (input.name === "smoking") input.name = `smoking${index}`;
  });

  // Filtrage marque -> modèle
  form.querySelector('.brand').addEventListener('change', () => filterModels(form));

  // Enregistrer
  form.addEventListener('submit', saveForm);

  // Pré-remplissage
  prefillForm(form, index);
}

// Initialisation des formulaires existants
document.querySelectorAll('.driver-info-container form').forEach((form, i) => setupForm(form, i));

// Ajouter un véhicule
document.getElementById('addVehicleBtn').addEventListener('click', () => {
  const template = document.querySelector('.driver-info-container');
  const clone = template.cloneNode(true);
  const index = vehiclesContainer.children.length;

  // Reset form
  clone.querySelectorAll('input, select').forEach(el => {
    if (el.type === 'radio') el.checked = false;
    else el.value = "";
  });

  clone.querySelector('h2').textContent = `Véhicule numéro ${index + 1}`;
  vehiclesContainer.appendChild(clone);

  const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
  dataList.push({});
  localStorage.setItem('driverInfoList', JSON.stringify(dataList));

  setupForm(clone.querySelector('form'), index);
});

// Supprimer un véhicule
document.getElementById('removeVehicleBtn').addEventListener('click', () => {
  const dataList = JSON.parse(localStorage.getItem('driverInfoList') || '[]');
  if (dataList.length === 0) return alert("Aucun véhicule à supprimer !");
  const number = prompt(`Quel véhicule supprimer ? (1 à ${dataList.length})`);
  const index = parseInt(number, 10) - 1;
  if (index >= 0 && index < dataList.length) {
    vehiclesContainer.children[index].remove();
    dataList.splice(index, 1);
    localStorage.setItem('driverInfoList', JSON.stringify(dataList));

    // Re-numérotation et re-setup des radios
    Array.from(vehiclesContainer.children).forEach((child, i) => {
      const h2 = child.querySelector('h2');
      if (h2) h2.textContent = `Véhicule numéro ${i + 1}`;
      const form = child.querySelector('form');
      if (form) setupForm(form, i);
    });
  } else alert("Numéro invalide !");
});
