<?php
// views/mapa_unidades.php — Mapa de Unidades
// Zona Horaria local

require __DIR__ . '/../config/db.php';

// Obtener todas las unidades con ubicación definida
define('PER_PAGE', 5);
$stmt = $pdo->query(
    "SELECT u.id_unidad AS id,
            u.codigo_qr AS codigo,
            u.estado AS estado,
            u.gps_lat AS lat,
            u.gps_lng AS lng,
            t.nombre AS terminal
     FROM unidad u
     LEFT JOIN terminales t ON u.terminal_id = t.id
     WHERE u.gps_lat IS NOT NULL AND u.gps_lng IS NOT NULL"
);
unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h5>Mapa de Unidades</h5>
<!-- Botón para crear unidad -->
<div class="d-flex mb-3">
  <button class="btn btn-success" onclick="loadView('create_unidad')">Crear Unidad</button>
</div>

<!-- Mapa con todas las unidades -->
<div
  id="mapa-unidades"
  style="height:400px; border:1px solid #ccc;"
  data-unidades='<?= json_encode($unidades, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'
></div>

<script>
(function(){
  const el = document.getElementById('mapa-unidades');
  if (!el) return;
  const data = JSON.parse(el.dataset.unidades || '[]');
  const map  = L.map(el).setView([-29.412, -66.851], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);
  data.forEach(u => {
    L.marker([u.lat, u.lng])
      .addTo(map)
      .bindPopup(
        `<strong>Código:</strong> ${u.codigo}<br>` +
        `<strong>Terminal:</strong> ${u.terminal || 'No asignada'}<br>` +
        `<strong>Estado:</strong> ${u.estado}`
      );
  });
})();
</script>
