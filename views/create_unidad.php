<?php
require __DIR__ . '/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->prepare("
    INSERT INTO unidad
      (codigo_qr, id_terminal, estado, sim_imei, gps_lat, gps_lng, fecha_ultima_actualizacion)
    VALUES (?, ?, ?, ?, NULL, NULL, NOW())
  ")->execute([
    $_POST['codigo_qr'],
    $_POST['id_terminal'],
    $_POST['estado'],
    $_POST['sim_imei']
  ]);
  echo '<div class="alert alert-success">Unidad creada</div>';
  include 'unidades.php'; // recarga el listado actualizado
  exit;
}
// Cargamos terminales para el selector
$terms = $pdo->query("SELECT id, nombre FROM terminales")->fetchAll();
?>
<h5>Crear Unidad</h5>
<form method="post" class="mt-3">
  <div class="mb-3">
    <label class="form-label">QR</label>
    <input name="codigo_qr" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Terminal</label>
    <select name="id_terminal" class="form-select" required>
      <?php foreach($terms as $t): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
      <option value="activo">activo</option>
      <option value="inactivo">inactivo</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">SIM/IMEI</label>
    <input name="sim_imei" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Guardar</button>
</form>
