<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_method('POST');
require_admin();

$in = json_input();
$personaje = clean($in['personaje'] ?? '');
$descripcion = clean($in['descripcion'] ?? '');

if($personaje === '' || $descripcion === ''){
  json_out(['error' => 'Faltan campos'], 400);
}

$mysqli = db_connect();
$stmt = $mysqli->prepare("INSERT INTO castings (personaje, descripcion) VALUES (?, ?)");
$stmt->bind_param('ss', $personaje, $descripcion);
$stmt->execute();

json_out(['ok' => true, 'id' => $mysqli->insert_id]);
