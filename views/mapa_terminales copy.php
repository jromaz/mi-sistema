<?php
// views/mapa_terminales.php
require __DIR__ . '/../config/db.php';

$data = $pdo->query("
  SELECT id_unidad AS id, gps_lat AS lat, gps_lng AS lng
  FROM unidad
  WHERE gps_lat IS NOT NULL AND gps_lng IS NOT NULL
")->fetchAll(PDO::FETCH_ASSOC);
?>
<h5>Mapa de Terminales</h5>
<div
  id="mapa-terminales"
  style="height: 500px; border:1px solid #ccc;"
  data-terminales='<?= json_encode($data, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'
></div>
