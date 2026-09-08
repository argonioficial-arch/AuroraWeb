<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_method('POST');

$in = json_input();
$casting_id = intval($in['casting_id'] ?? 0);
$nombre = clean($in['nombre'] ?? '');
$edad = intval($in['edad'] ?? 0);
$roblox = clean($in['roblox_user'] ?? '');
$discord = clean($in['discord_user'] ?? '');
$cinta = clean($in['cinta_url'] ?? '');
$experiencia = clean($in['experiencia'] ?? '');
$motivacion = clean($in['motivacion'] ?? '');

if($casting_id <= 0 || $nombre === '' || $edad <= 0 || $roblox === '' || $discord === '' || $experiencia === '' || $motivacion === ''){
  json_out(['error' => 'Faltan campos obligatorios'], 400);
}

$mysqli = db_connect();

$check = $mysqli->prepare("SELECT estado FROM castings WHERE id = ?");
$check->bind_param('i', $casting_id);
$check->execute();
$row = $check->get_result()->fetch_assoc();
if(!$row || $row['estado'] !== 'abierto'){
  json_out(['error' => 'Este casting ya no está disponible'], 400);
}

$stmt = $mysqli->prepare(
  "INSERT INTO casting_postulaciones (casting_id, nombre, edad, roblox_user, discord_user, cinta_url, experiencia, motivacion)
   VALUES (?,?,?,?,?,?,?,?)"
);
$stmt->bind_param('isisssss', $casting_id, $nombre, $edad, $roblox, $discord, $cinta, $experiencia, $motivacion);

if(!$stmt->execute()){
  json_out(['error' => 'No se pudo guardar la postulación'], 500);
}

json_out(['ok' => true, 'id' => $mysqli->insert_id]);
