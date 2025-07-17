<?php
require __DIR__ . '/../config/db.php';
$id = $_GET['id'] ?? 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->prepare("
    UPDATE unidad 
      SET codigo_qr=?, id_terminal=?, estado=?, sim_imei=?
    WHERE id_unidad=?
  ")->execute([
    $_POST['codigo_qr'],
    $_POST['id_terminal'],
    $_POST['estado'],
    $_POST['sim_imei'],
    $id
  ]);
  echo '<div class="alert alert-success">Unidad actualizada</div>';
  include 'unidades.php';
  exit;
}
$unit  = $pdo->prepare("SELECT * FROM unidad WHERE id_unidad=?");
$unit->execute([$id]); $unit = $unit->fetch();
$terms = $pdo->query("SELECT id, nombre FROM terminales")->fetchAll();
?>
<h5>Editar Unidad #<?= $id ?></h5>
<form method="post" class="mt-3">
  <div class="mb-3">
    <label class="form-label">QR</label>
    <input name="codigo_qr" class="form-control" value="<?= htmlspecialchars($unit['codigo_qr']) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Terminal</label>
    <select name="id_terminal" class="form-select">
      <?php foreach($terms as $t): ?>
        <option value="<?= $t['id'] ?>" <?= $t['id']==$unit['id_terminal']?'selected':'' ?>>
          <?= htmlspecialchars($t['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
      <option value="activo"   <?= $unit['estado']=='activo'   ?'selected':'' ?>>activo</option>
      <option value="inactivo" <?= $unit['estado']=='inactivo' ?'selected':'' ?>>inactivo</option>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">SIM/IMEI</label>
    <input name="sim_imei" class="form-control" value="<?= htmlspecialchars($unit['sim_imei']) ?>">
  </div>
  <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
