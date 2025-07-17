<?php
require __DIR__ . '/../config/db.php';

// Borrar
if (isset($_GET['delete'])) {
  $pdo->prepare("DELETE FROM unidad WHERE id_unidad=?")
      ->execute([ $_GET['delete'] ]);
  echo '<div class="alert alert-info">Unidad eliminada</div>';
}

// Listar unidades y mostrar nombre de la terminal relacionada
$unidades = $pdo->query("
  SELECT u.id_unidad, u.codigo_qr, u.estado, u.sim_imei, t.nombre AS terminal
  FROM unidad u
  LEFT JOIN terminales t ON u.id_terminal = t.id
")->fetchAll();
?>
<h5>Gestión de Unidades</h5>
<button class="btn btn-primary mb-3" onclick="loadView('create_unidad')">
  Nueva Unidad
</button>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>QR</th><th>Terminal</th><th>Estado</th><th>SIM</th><th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($unidades as $u): ?>
      <tr>
        <td><?= $u['id_unidad'] ?></td>
        <td><?= htmlspecialchars($u['codigo_qr']) ?></td>
        <td><?= htmlspecialchars($u['terminal']) ?></td>
        <td><?= $u['estado'] ?></td>
        <td><?= htmlspecialchars($u['sim_imei']) ?></td>
        <td>
          <button class="btn btn-sm btn-warning"
                  onclick="loadView('edit_unidad','id=<?= $u['id_unidad'] ?>')">
            Editar
          </button>
          <button class="btn btn-sm btn-danger"
                  onclick="loadView('unidades','delete=<?= $u['id_unidad'] ?>')">
            Borrar
          </button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
