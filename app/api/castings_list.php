<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_method('GET');

$mysqli = db_connect();

if(isset($_GET['all'])){
  require __DIR__ . '/auth.php';
  require_admin();
  $res = $mysqli->query("SELECT * FROM castings ORDER BY fecha_creacion DESC");
} else {
  $res = $mysqli->query("SELECT id, personaje, descripcion, estado FROM castings WHERE estado = 'abierto' ORDER BY fecha_creacion DESC");
}

$rows = [];
while($r = $res->fetch_assoc()){ $rows[] = $r; }

json_out(['castings' => $rows]);
