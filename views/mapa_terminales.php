<?php
// views/mapa_terminales.php — Mapa, creación, búsqueda y listado paginado
require __DIR__ . '/../config/db.php';

// Parámetros de búsqueda y paginación
$search = trim($_GET['search'] ?? '');
$page   = max(1, intval($_GET['page'] ?? 1));
$perPage = 5;
$offset  = ($page - 1) * $perPage;

// Construir cláusula WHERE
$where = "WHERE latitud IS NOT NULL AND longitud IS NOT NULL";
$params = [];
if ($search !== '') {
    $where .= " AND (nombre LIKE :search OR direccion LIKE :search)";
    $params[':search'] = "%{$search}%";
}

// Contar total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM terminales $where");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$pages = ceil($total / $perPage);

// Obtener filas actuales
$sql = "SELECT id, nombre, direccion, latitud AS lat, longitud AS lng, activo
        FROM terminales
        $where
        ORDER BY id DESC
        LIMIT :offset,:perPage";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) { $stmt->bindValue($k, $v, PDO::PARAM_STR); }
$stmt->bindValue(':offset',   $offset,   PDO::PARAM_INT);
$stmt->bindValue(':perPage',  $perPage,  PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h5>Mapa de Terminales</h5>

<div class="d-flex mb-2">
  <button id="btn-add-terminal" class="btn btn-success me-3">Crear Terminal</button>

  <div class="input-group w-50">
    <input id="searchInput" type="text" class="form-control" 
           placeholder="Buscar por nombre o dirección" 
           value="<?= htmlspecialchars($search) ?>">
    <button id="searchBtn" class="btn btn-primary">Buscar</button>
  </div>
</div>

<div
  id="mapa-terminales"
  style="height: 400px; border:1px solid #ccc;"
  data-terminales='<?= json_encode($rows, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'
></div>

<h5 class="mt-4">Listado de Terminales</h5>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Dirección</th>
      <th>Latitud</th>
      <th>Longitud</th>
      <th>Activo</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t['nombre']) ?></td>
      <td><?= htmlspecialchars($t['direccion']) ?></td>
      <td><?= $t['lat'] ?></td>
      <td><?= $t['lng'] ?></td>
      <td><?= $t['activo'] ? 'Sí' : 'No' ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<nav>
  <ul class="pagination">
    <?php for ($p = 1; $p <= $pages; $p++): ?>
      <li class="page-item <?= $p === $page ? 'active' : '' ?>">
        <a class="page-link" href="#"
           onclick="loadView('mapa_terminales','search='+encodeURIComponent(document.getElementById('searchInput').value)+'&page=<?= $p ?>')">
          <?= $p ?>
        </a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>

<script>
// Esperamos a que la vista esté inyectada
(function(){
  const el = document.getElementById('mapa-terminales');
  if (!el) return;

  // Datos actuales para el mapa
  const terminals = JSON.parse(el.dataset.terminales || '[]');

  // Inicializar mapa
  const map = L.map(el).setView([-29.412, -66.851], 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Dibujar marcadores
  terminals.forEach(t => {
    L.marker([t.lat, t.lng])
      .addTo(map)
      .bindPopup(`ID ${t.id}: ${t.nombre}`);
  });

  // Modo creación desactivado hasta pulsar el botón
  let createMode = false;
  const btn = document.getElementById('btn-add-terminal');
  btn.onclick = () => {
    createMode = true;
    alert('Modo creación activo: haz clic en el mapa para elegir ubicación');
  };

  // Al hacer click en el mapa
  map.on('click', e => {
    if (!createMode) return;
    const {lat, lng} = e.latlng;
    // Vamos a la vista de creación con lat/lng
    loadView('create_terminal', `lat=${lat}&lng=${lng}`);
  });

  // Búsqueda por nombre/dirección
  document.getElementById('searchBtn').onclick = () => {
    const q = document.getElementById('searchInput').value;
    loadView('mapa_terminales', 'search=' + encodeURIComponent(q));
  };
})();
</script>

