<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_method('POST');
require_admin();

$in = json_input();
$id = intval($in['id'] ?? 0);
if($id <= 0){ json_out(['error' => 'id inválido'], 400); }

$mysqli = db_connect();
$stmt = $mysqli->prepare("SELECT estado FROM castings WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if(!$row){ json_out(['error' => 'No encontrado'], 404); }

$nuevo = $row['estado'] === 'abierto' ? 'cerrado' : 'abierto';
$upd = $mysqli->prepare("UPDATE castings SET estado = ? WHERE id = ?");
$upd->bind_param('si', $nuevo, $id);
$upd->execute();

json_out(['ok' => true, 'estado' => $nuevo]);
