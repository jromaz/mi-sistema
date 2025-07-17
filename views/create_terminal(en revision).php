<?php
// views/create_terminal.php — Formulario y guardado para la tabla "terminales"
require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos datos
    $nombre    = $_POST['nombre']    ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $latitud   = $_POST['latitud']   ?? '';
    $longitud  = $_POST['longitud']  ?? '';
    $activo    = isset($_POST['activo']) ? 1 : 0;

    try {
        // Insertamos en terminales
        $stmt = $pdo->prepare("
            INSERT INTO terminales
               (nombre, direccion, latitud, longitud, activo)
            VALUES
               (:nombre, :direccion, :latitud, :longitud, :activo)
        ");
        $stmt->execute([
            ':nombre'    => $nombre,
            ':direccion' => $direccion,
            ':latitud'   => $latitud,
            ':longitud'  => $longitud,
            ':activo'    => $activo,
        ]);

        // Volvemos al dashboard principal
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">'
           . 'Error al guardar terminal: '
           . htmlspecialchars($e->getMessage())
           . '</div>';
    }
}

// Si no es POST, tomamos lat y lng de la URL
$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';
?>
<h5>Crear Terminal</h5>
<form 
  method="post" 
  action="views/create_terminal.php?lat=<?= urlencode($lat) ?>&lng=<?= urlencode($lng) ?>" 
  class="mt-3"
>
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Latitud</label>
    <input 
      type="text" 
      name="latitud" 
      class="form-control" 
      value="<?= htmlspecialchars($lat) ?>" 
      readonly
    >
  </div>
  <div class="mb-3">
    <label class="form-label">Longitud</label>
    <input 
      type="text" 
      name="longitud" 
      class="form-control" 
      value="<?= htmlspecialchars($lng) ?>" 
      readonly
    >
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
    <label class="form-check-label" for="activo">Activo</label>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>
