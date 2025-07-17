<?php
// views/create_terminal.php — Formulario y guardado en tabla terminales con modal de confirmación
require __DIR__ . '/../config/db.php';

$showSuccess = false;
$showError   = false;
$errorMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanitizar datos
    $nombre    = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $latitud   = $_POST['latitud'] ?? '';
    $longitud  = $_POST['longitud'] ?? '';
    $activo    = isset($_POST['activo']) ? 1 : 0;

    // Validación básica
    if ($nombre === '' || $latitud === '' || $longitud === '') {
        $errorMsg = 'Por favor completá todos los campos obligatorios.';
        $showError = true;
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO terminales (nombre, direccion, latitud, longitud, activo) VALUES (:nombre, :direccion, :latitud, :longitud, :activo)"
            );
            $stmt->execute([
                ':nombre'    => $nombre,
                ':direccion' => $direccion,
                ':latitud'   => $latitud,
                ':longitud'  => $longitud,
                ':activo'    => $activo
            ]);
            $showSuccess = true;
        } catch (PDOException $e) {
            $errorMsg = 'Error al guardar: ' . $e->getMessage();
            $showError = true;
        }
    }
}

// Prellenar lat y lng desde GET
$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';
?>

<h5>Crear Terminal</h5>
<form id="createTerminalForm" method="post"
      action="views/create_terminal.php?lat=<?= urlencode($lat) ?>&lng=<?= urlencode($lng) ?>"
      class="mt-3">
  <div class="mb-3">
    <label class="form-label">Nombre *</label>
    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($direccion ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Latitud *</label>
    <input type="text" name="latitud" class="form-control" value="<?= htmlspecialchars($lat) ?>" readonly required>
  </div>
  <div class="mb-3">
    <label class="form-label">Longitud *</label>
    <input type="text" name="longitud" class="form-control" value="<?= htmlspecialchars($lng) ?>" readonly required>
  </div>
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= isset($activo) && $activo ? 'checked' : '' ?>>
    <label class="form-check-label" for="activo">Activo</label>
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<!-- Modal Éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">¡Listo!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        Terminal creada correctamente.
      </div>
    </div>
  </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center text-danger">
        <?= htmlspecialchars($errorMsg) ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    <?php if ($showSuccess): ?>
      var m = new bootstrap.Modal(document.getElementById('successModal'));
      m.show();
    <?php elseif ($showError): ?>
      var m = new bootstrap.Modal(document.getElementById('errorModal'));
      m.show();
    <?php endif; ?>
  });
</script>
