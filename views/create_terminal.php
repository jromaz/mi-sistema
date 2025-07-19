<?php
// views/create_terminal.php — Formulario con inserción funcional

require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura y sanea
    $nombre    = trim($_POST['nombre']    ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $latitud   = trim($_POST['latitud']   ?? '');
    $longitud  = trim($_POST['longitud']  ?? '');
    $activo    = isset($_POST['activo']) && $_POST['activo'] === '1' ? 1 : 0;

    // 2) Validación de campos obligatorios
    if ($nombre === '' || $direccion === '' || $latitud === '' || $longitud === '') {
        echo '<script>alert("Por favor completa todos los campos obligatorios."); history.back();</script>';
        exit;
    }

    // 3) Inserción en la tabla terminales
    try {
        $sql = "INSERT INTO terminales
                  (nombre, latitud, longitud, direccion, activo)
                VALUES
                  (:nombre, :lat, :lng, :direccion, :activo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'    => $nombre,
            ':lat'       => $latitud,
            ':lng'       => $longitud,
            ':direccion' => $direccion,
            ':activo'    => $activo,
        ]);

        // 4) Redirigir de vuelta al mapa de terminales
        header('Location: ../index.php?view=mapa_terminales');
        exit;
    } catch (PDOException $e) {
        echo '<script>alert("Error al guardar: '. addslashes($e->getMessage()) .'"); history.back();</script>';
        exit;
    }
}

// 5) Precarga lat/lng desde GET para el mapa picker
$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';
?>
<h5>Crear Terminal</h5>

<!-- Mini‑mapa para elegir ubicación -->
<div id="map-picker"
     data-lat="<?= htmlspecialchars($lat) ?>"
     data-lng="<?= htmlspecialchars($lng) ?>"
     style="height:300px;border:1px solid #ccc;margin-bottom:15px;">
</div>
<p>Haz clic en el mapa para seleccionar la ubicación.</p>

<form method="post"
      action="views/create_terminal.php?lat=<?= urlencode($lat) ?>&lng=<?= urlencode($lng) ?>"
      class="mt-3">
  <div class="mb-3">
    <label class="form-label">Nombre *</label>
    <input type="text" name="nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Dirección *</label>
    <input type="text" name="direccion" class="form-control" required>
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
  <div class="mb-3">
    <label class="form-label">Activo *</label><br>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="activo" value="1" checked>
      <label class="form-check-label">Sí</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="activo" value="0">
      <label class="form-check-label">No</label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>
