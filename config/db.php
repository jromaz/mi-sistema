<?php
// config/db.php — Conexión PDO a bicicletas_la_rioja

$host    = 'localhost';
$db      = 'bicicletas_la_rioja';  // <- Nombre de tu BD nueva
$user    = 'root';
$pass    = '';                     // <- Ajusta si tienes password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Si falla la conexión, detiene y muestra error
    echo 'ERROR de conexión: ' . htmlspecialchars($e->getMessage());
    exit;
}
