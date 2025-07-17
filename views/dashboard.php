<?php
// views/dashboard.php
require __DIR__ . '/../config/db.php';  // define $pdo

// Consultas estadísticas
$totalStmt     = $pdo->query("SELECT COUNT(*) FROM unidad");
$total         = $totalStmt->fetchColumn();
$actStmt       = $pdo->query("SELECT COUNT(*) FROM unidad WHERE estado='activo'");
$activas       = $actStmt->fetchColumn();
$mantStmt      = $pdo->query("SELECT COUNT(*) FROM mantenimiento");
$mantenimientos = $mantStmt->fetchColumn();
$viajeStmt     = $pdo->prepare("SELECT COUNT(*) FROM viaje WHERE DATE(inicio)=CURDATE()");
$viajeStmt->execute();
$viajesHoy     = $viajeStmt->fetchColumn();
?>
<div class="row g-3 mb-4">
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Total Unidades</strong><br><h3><?= $total ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Activas</strong><br><h3><?= $activas ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Mantenimiento</strong><br><h3><?= $mantenimientos ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Viajes Hoy</strong><br><h3><?= $viajesHoy ?></h3>
      </div>
    </div>
  </div>
</div>
<hr>

<h5>Seleccionar Ubicación en el Mapa</h5>
<div id="mapa-dashboard" style="height: 400px; border:1px solid #ccc;"></div>
<p class="mt-2">Haz clic para colocar la terminal.</p>
<button id="btn-create-terminal" class="btn btn-primary">Crear Terminal</button>
