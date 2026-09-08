<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_method('GET');
require_admin();

$estado = $_GET['estado'] ?? 'todos';
$puesto = $_GET['puesto'] ?? 'todos';

$mysqli = db_connect();
$sql = "SELECT * FROM candidaturas WHERE 1=1";
$types = '';
$params = [];

if($estado !== 'todos'){ $sql .= " AND estado = ?"; $types .= 's'; $params[] = $estado; }
if($puesto !== 'todos'){ $sql .= " AND puesto = ?"; $types .= 's'; $params[] = $puesto; }
$sql .= " ORDER BY fecha_creacion DESC";

$stmt = $mysqli->prepare($sql);
if($types !== ''){
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while($r = $res->fetch_assoc()){ $rows[] = $r; }

json_out(['candidaturas' => $rows]);
