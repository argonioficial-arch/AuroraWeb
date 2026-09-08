<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_method('GET');
require_admin();

$casting_id = $_GET['casting_id'] ?? 'todos';
$estado = $_GET['estado'] ?? 'todos';

$mysqli = db_connect();
$sql = "SELECT cp.*, c.personaje FROM casting_postulaciones cp
        JOIN castings c ON c.id = cp.casting_id WHERE 1=1";
$types = '';
$params = [];

if($casting_id !== 'todos'){ $sql .= " AND cp.casting_id = ?"; $types .= 'i'; $params[] = intval($casting_id); }
if($estado !== 'todos'){ $sql .= " AND cp.estado = ?"; $types .= 's'; $params[] = $estado; }
$sql .= " ORDER BY cp.fecha_creacion DESC";

$stmt = $mysqli->prepare($sql);
if($types !== ''){
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while($r = $res->fetch_assoc()){ $rows[] = $r; }

json_out(['postulaciones' => $rows]);
