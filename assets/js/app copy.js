// app.js — carga vistas y arranca mapas

function loadView(view, params = '') {
  fetch(`views/${view}.php${params ? '?' + params : ''}`)
    .then(r => r.text())
    .then(html => {
      document.getElementById('main-content').innerHTML = html;
      if (view === 'dashboard')      initDashboardMap();
      if (view === 'mapa_terminales') initMapaTerminales();
    })
    .catch(e => console.error('Error al cargar vista:', e));
}

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
    loadView('create_terminal',
      `lat=${window.selectedLatLng.lat}&lng=${window.selectedLatLng.lng}`);
  };
}

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
