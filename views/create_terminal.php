<?php
// views/create_terminal.php — Formulario con picker de ubicación
require __DIR__ . '/../config/db.php';

// Si llegamos vía POST, guardamos y volvemos a Mapa Terminales
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']    ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $latitud   = $_POST['latitud']        ?? '';
    $longitud  = $_POST['longitud']       ?? '';
    $activo    = isset($_POST['activo']) ? 1 : 0;

    if ($nombre === '' || $latitud === '' || $longitud === '') {
        echo '<script>alert("Completa todos los campos obligatorios."); history.back();</script>';
        exit;
    }

    try {
        $stmt = $pdo->prepare("
          INSERT INTO terminales (nombre, direccion, latitud, longitud, activo)
          VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nombre, $direccion, $latitud, $longitud, $activo]);
        // Redirigimos de vuelta al listado de terminales (mapa_terminales)
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        echo '<script>alert("Error al guardar: '. addslashes($e->getMessage()) .'"); history.back();</script>';
        exit;
    }
}

// Si no es POST, preparamos lat y lng vacíos o desde GET
$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';
?>
<h5>Crear Terminal</h5>

<!-- 1) Mini‑mapa para elegir ubicación -->
<div id="map-picker" style="height: 300px; border:1px solid #ccc; margin-bottom:15px;"></div>
<p>Haz clic en el mapa para seleccionar latitud/longitud.</p>

<!-- 2) Formulario -->
<form method="post" class="mt-3">
  <div class="mb-3">
    <label class="form-label">Nombre *</label>
    <input type="text" name="nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control">
  </div>
  <div class="row">
    <div class="col mb-3">
      <label class="form-label">Latitud *</label>
      <input type="text" name="latitud" class="form-control" 
             value="<?= htmlspecialchars($lat) ?>" readonly required>
    </div>
    <div class="col mb-3">
      <label class="form-label">Longitud *</label>
      <input type="text" name="longitud" class="form-control" 
             value="<?= htmlspecialchars($lng) ?>" readonly required>
    </div>
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
    <label class="form-check-label" for="activo">Activo</label>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<!-- 3) Script para Leaflet picker -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Crear el mapa en el div #map-picker
  const map = L.map('map-picker').setView([-29.412, -66.851], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  let marker;
  map.on('click', e => {
    // Coloca/actualiza marcador
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    // Rellena los inputs del formulario
    document.querySelector('input[name="latitud"]').value  = e.latlng.lat.toFixed(7);
    document.querySelector('input[name="longitud"]').value = e.latlng.lng.toFixed(7);
  });
});
</script>

