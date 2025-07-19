<?php
// views/dashboard.php — Estadísticas y mapa de terminales
// Actualizado: 2025-07-18 11:45 (ART) Zona Horaria: America/Argentina/La_Rioja

require __DIR__ . '/../config/db.php';

// 1) Total de Terminales
$totalTerminales = $pdo
    ->query("SELECT COUNT(*) FROM terminales")
    ->fetchColumn();

// 2) Terminales Activas
$terminalesActivas = $pdo
    ->query("SELECT COUNT(*) FROM terminales WHERE activo = 1")
    ->fetchColumn();

// 3) Mantenimientos
$totalMantenimientos = $pdo
    ->query("SELECT COUNT(*) FROM mantenimiento")
    ->fetchColumn();

// 4) Viajes de Hoy
$stmtViajes = $pdo->prepare(
    "SELECT COUNT(*) FROM viaje WHERE DATE(inicio) = CURDATE()"
);
$stmtViajes->execute();
$viajesHoy = $stmtViajes->fetchColumn();

// Obtener datos de terminales para el mapa
$termStmt = $pdo->query(
    "SELECT id, nombre, latitud AS lat, longitud AS lng FROM terminales
     WHERE latitud IS NOT NULL AND longitud IS NOT NULL"
);\$terminals = $termStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row g-3 mb-4">
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Total Terminales</strong><br>
        <h3><?= $totalTerminales ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Activas</strong><br>
        <h3><?= $terminalesActivas ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Mantenimientos</strong><br>
        <h3><?= $totalMantenimientos ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Viajes Hoy</strong><br>
        <h3><?= $viajesHoy ?></h3>
      </div>
    </div>
  </div>
</div>

<hr>

<h5>Mapa de Terminales</h5>
<div id="mapa-dashboard" style="height:400px;border:1px solid #ccc;"
     data-terminals='<?= json_encode($terminals, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'>
</div>

<script>
// Después de inyectar la vista, app.js llamará initDashboardMap() para renderizar este mapa
</script>
