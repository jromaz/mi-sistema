<?php
// views/create_terminal.php - Formulario y guardado para la tabla "terminales"
require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos datos del formulario
    $nombre    = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $latitud   = $_POST['latitud'];
    $longitud  = $_POST['longitud'];
    $activo    = isset($_POST['activo']) ? 1 : 0;

    // Insertamos en la tabla "terminales"
    $stmt = $pdo->prepare(
        "INSERT INTO terminales (nombre, direccion, latitud, longitud, activo)
         VALUES (:nombre, :direccion, :latitud, :longitud, :activo)"
    );
    $stmt->execute([
        ':nombre'    => $nombre,
        ':direccion' => $direccion,
        ':latitud'   => $latitud,
        ':longitud'  => $longitud,
        ':activo'    => $activo
    ]);

    echo '<div class="alert alert-success">Terminal creada correctamente.</div>';
    exit;
}

// Si no es POST, prellenamos latitud y longitud desde GET
$latitud = $_GET['latitud '];
$longitud = $_GET['longitud'];
//$latitud  = isset($_GET['latitud'])  ? $_GET['latitud']  : '';
//$longitud = isset($_GET['longitud']) ? $_GET['longitud'] : '';
?>

<h5>Crear Terminal</h5>
<form method="post" class="mt-3">
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control" required>
  </div>
  <div class="mb-3"><label>Latitud</label><input type="text" name="latitud" class="form-control" value="<?= $latitud ?>" readonly></div>
  <div class="mb-3"><label>Longitud</label><input type="text" name="Longitud" class="form-control" value="<?= $longitud ?>" readonly></div>
  

  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
    <label class="form-check-label" for="activo">Activo</label>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>
