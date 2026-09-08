<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_method('POST');
require_admin();

$in = json_input();
$id = intval($in['id'] ?? 0);
$estado = clean($in['estado'] ?? '');

if($id <= 0 || !in_array($estado, ['pendiente', 'aceptado', 'rechazado'], true)){
  json_out(['error' => 'Datos inválidos'], 400);
}

$mysqli = db_connect();
$stmt = $mysqli->prepare("UPDATE casting_postulaciones SET estado = ? WHERE id = ?");
$stmt->bind_param('si', $estado, $id);
$stmt->execute();

json_out(['ok' => true]);
