<?php
// unidades.php - CRUD Unidades
require '../config/db.php';
// Eliminar
if(isset($_GET['delete'])){
  $pdo->prepare("DELETE FROM unidad WHERE id_unidad=?")->execute([$_GET['delete']]);
  echo '<div class="alert alert-info">Unidad eliminada.</div>';
}
// Actualizar
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id_unidad'])){
  $stmt = $pdo->prepare("UPDATE unidad SET codigo_qr=?, estado=?, sim_imei=? WHERE id_unidad=?");
  $stmt->execute([$_POST['codigo_qr'], $_POST['estado'], $_POST['sim_imei'], $_POST['id_unidad']]);
  echo '<div class="alert alert-success">Unidad actualizada.</div>';
}
// Listar
$unidades = $pdo->query("SELECT * FROM unidad")->fetchAll();
?>
<h5>Gestión de Unidades</h5>
<table class="table"><thead><tr><th>ID</th><th>QR</th><th>Estado</th><th>SIM</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($unidades as $u): ?>
<tr>
  <td><?= $u['id_unidad'] ?></td>
  <td><?= htmlspecialchars($u['codigo_qr']) ?></td>
  <td><?= $u['estado'] ?></td>
  <td><?= htmlspecialchars($u['sim_imei']) ?></td>
  <td>
    <a href="#" onclick="editUnidad(<?= $u['id_unidad'] ?>)" class="btn btn-sm btn-warning">Editar</a>
    <a href="views/unidades.php?delete=<?= $u['id_unidad'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody></table>
<script>
// Función de edición simple (puedes mejorar con modal)
function editUnidad(id){
  var row = document.querySelector('tr td:first-child:contains('+id+')').parentNode;
  var qr = row.cells[1].innerText;
  var estado = row.cells[2].innerText;
  var sim = row.cells[3].innerText;
  var form = `
    <h5>Editar Unidad ${id}</h5>
    <form method="post">
      <input type="hidden" name="id_unidad" value="${id}">
      <div class="mb-3"><label>QR</label><input class="form-control" name="codigo_qr" value="${qr}"></div>
      <div class="mb-3"><label>Estado</label>
        <select class="form-select" name="estado">
          <option ${estado=='activo'?'selected':''} value="activo">activo</option>
          <option ${estado=='inactivo'?'selected':''} value="inactivo">inactivo</option>
        </select>
      </div>
      <div class="mb-3"><label>SIM</label><input class="form-control" name="sim_imei" value="${sim}"></div>
      <button class="btn btn-primary">Guardar</button>
    </form>`;
  document.getElementById('main-content').innerHTML = form;
}
</script>