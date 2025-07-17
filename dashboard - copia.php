  <?php
// views/dashboard.php
// Nota: NO usemos require '../config/db.php' porque ya está incluido en index.php

// Recuperar estadísticas de la BD
$totalStmt   = $pdo->query("SELECT COUNT(*) FROM unidad");
$total       = $totalStmt->fetchColumn();
$actStmt     = $pdo->query("SELECT COUNT(*) FROM unidad WHERE estado='activo'");
$activas     = $actStmt->fetchColumn();
$mantStmt    = $pdo->query("SELECT COUNT(*) FROM mantenimiento");
$mantenimientos = $mantStmt->fetchColumn();
$viajeStmt   = $pdo->prepare("SELECT COUNT(*) FROM viaje WHERE DATE(inicio)=CURDATE()");
$viajeStmt->execute();
$viajesHoy   = $viajeStmt->fetchColumn();


// 1) Total Unidades
$totalUnidades = $pdo
  ->query("SELECT COUNT(*) FROM unidad")
  ->fetchColumn();

// 2) Unidades Activas
$activas = $pdo
  ->query("SELECT COUNT(*) FROM unidad WHERE estado = 'activo'")
  ->fetchColumn();

// 3) Mantenimiento
$mantenimiento = $pdo
  ->query("SELECT COUNT(*) FROM mantenimiento")
  ->fetchColumn();

// 4) Viajes Hoy
$stmtViajes = $pdo->prepare("
  SELECT COUNT(*) 
  FROM viaje 
  WHERE DATE(inicio) = CURDATE()
");
$stmtViajes->execute();
$viajesHoy = $stmtViajes->fetchColumn();
?>

<div class="row g-3 mb-4">
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Total Unidades</strong><br>
        <h3><?= $totalUnidades ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Activas</strong><br>
        <h3><?= $activas ?></h3>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card card-summary">
      <div class="card-body text-center">
        <strong>Mantenimiento</strong><br>
        <h3><?= $mantenimiento ?></h3>
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

<h5>Seleccionar Ubicación en el Mapa</h5>
<!-- Contenedor para initDashboardMap() -->
<div id="mapa-dashboard" style="height: 400px; border:1px solid #ccc;"></div>
<p class="mt-2">Haz clic en el mapa para seleccionar ubicación.</p>
<button id="btn-create-terminal" class="btn btn-primary">Crear Terminal</button>
