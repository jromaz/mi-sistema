// assets/js/app.js — carga dinámica de vistas y mapas

function loadView(view, params = '') {
  fetch(`views/${view}.php${params ? '?' + params : ''}`)
    .then(res => res.text())
    .then(html => {
      // Inyecta el HTML devuelto en el contenedor principal
      document.getElementById('main-content').innerHTML = html;

      // Inicializaciones específicas por vista:
      if (view === 'dashboard')       initDashboardMap();
      else if (view === 'mapa_terminales') initMapaTerminales();
      // NO hace falta init para 'unidades', 'create_unidad' o 'edit_unidad'
    })
    .catch(err => console.error('Error al cargar vista:', err));
}

// ... tus funciones initDashboardMap e initMapaTerminales aquí ...
