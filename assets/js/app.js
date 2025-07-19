// assets/js/app.js — carga vistas y arranca mapas (Actualizado: 2025-07-18 11:45 ART)

// Función principal para cargar la vista correspondiente
function loadView(view, params = '') {
  fetch(`views/${view}.php${params ? '?' + params : ''}`)
    .then(r => r.text())
    .then(html => {
      // Inyecta el HTML en el contenedor principal
      document.getElementById('main-content').innerHTML = html;
      // Llama al init específico según la vista
      if (view === 'dashboard')        initDashboardMap();
      if (view === 'mapa_terminales')  initMapaTerminales();
      if (view === 'create_terminal')  initCreateTerminalMap();
    })
    .catch(e => console.error('Error al cargar vista:', e));
}

// Inicializa el mapa en Dashboard (selección y creación)
function initDashboardMap() {
  const el = document.getElementById('mapa-dashboard');
  if (!el) return;

  const map = L.map(el).setView([-29.412, -66.851], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  let marker;
  map.on('click', e => {
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    window.selectedLatLng = e.latlng;
  });

  const btn = document.getElementById('btn-create-terminal');
  if (btn) btn.onclick = () => {
    if (!window.selectedLatLng) return alert('Selecciona una ubicación primero.');
    loadView('create_terminal', `lat=${window.selectedLatLng.lat}&lng=${window.selectedLatLng.lng}`);
  };
}

// Inicializa el mapa que muestra todas las terminales
function initMapaTerminales() {
  const el = document.getElementById('mapa-terminales');
  if (!el) return;

  const map = L.map(el).setView([-29.412, -66.851], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  const datos = JSON.parse(el.dataset.terminales || '[]');
  datos.forEach(t => {
    L.marker([t.lat, t.lng])
      .addTo(map)
      .bindPopup(`Terminal ID: ${t.id}`);
  });
}

// Inicializa el mini‑mapa de creación en create_terminal.php
function initCreateTerminalMap() {
  const el = document.getElementById('map-picker');
  if (!el) return;

  // Centrado por defecto o con lat/lng precargados en data-attributes
  const lat0 = parseFloat(el.dataset.lat) || -29.412;
  const lng0 = parseFloat(el.dataset.lng) || -66.851;

  const map = L.map(el).setView([lat0, lng0], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  let marker = (el.dataset.lat && el.dataset.lng)
    ? L.marker([lat0, lng0]).addTo(map)
    : null;

  map.on('click', function(e) {
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    document.querySelector('input[name="latitud"]').value  = e.latlng.lat.toFixed(7);
    document.querySelector('input[name="longitud"]').value = e.latlng.lng.toFixed(7);
  });
}
