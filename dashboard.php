<?php
// views/dashboard.php
// $pdo ya viene de index.php

// Estadísticas
$totalUnidades       = $pdo->query("SELECT COUNT(*) FROM terminales")->fetchColumn();
$activos             = $pdo->query("SELECT COUNT(*) FROM terminales WHERE activo=1")->fetchColumn();
$totalMantenimientos = $pdo->query("SELECT COUNT(*) FROM mantenimiento")->fetchColumn();
$stmtViajes          = $pdo->prepare("SELECT COUNT(*) FROM viaje WHERE DATE(inicio)=CURDATE()");
$stmtViajes->execute();
$viajesHoy           = $stmtViajes->fetchColumn();

// Datos de terminales para el mapa
$termStmt = $pdo->query("SELECT id, nombre, latitud, longitud FROM terminales");
$terminals = $termStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row g-3 mb-4">
  <div class="col"><div class="card card-summary"><div class="card-body text-center"><strong>Total Terminales</strong><h3><?= $totalUnidades ?></h3></div></div></div>
  <div class="col"><div class="card card-summary"><div class="card-body text-center"><strong>Activas</strong><h3><?= $activos ?></h3></div></div></div>
  <div class="col"><div class="card card-summary"><div class="card-body text-center"><strong>Mantenimiento</strong><h3><?= $totalMantenimientos ?></h3></div></div></div>
  <div class="col"><div class="card card-summary"><div class="card-body text-center"><strong>Viajes Hoy</strong><h3><?= $viajesHoy ?></h3></div></div></div>
</div>
<hr>

<h5>Mapa de Terminales</h5>
<div 
  id="mapa-dashboard" 
  style="height:400px;border:1px solid #ccc;" 
  data-terminales='<?= json_encode($terminals, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'
></div>
