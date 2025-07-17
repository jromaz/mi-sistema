<?php
// index.php
require 'config/db.php';  // conexión PDO
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reb Rioja en Bici - Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- Estilos Santander -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 d-none d-md-block sidebar p-3">
        <h5 class="text-white">Reb Rioja en Bici</h5>
        <ul class="nav flex-column mt-4">
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="loadView('dashboard')">Dashboard</a>
          </li>
          <!-- … tus otras entradas de menú … -->
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="loadView('mapa_terminales')">Mapa Terminales</a>
          </li>
        </ul>
      </nav>

      <!-- Main content -->
      <main class="col-md-10 ms-sm-auto px-md-4">
        <div id="main-content" class="mt-4">
          <?php include 'views/dashboard.php'; ?>
        </div>
      </main>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="assets/js/app.js"></script>
</body>
</html>
