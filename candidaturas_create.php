<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_method('POST');

$positions = [
  'guionista' => 'Guionista',
  'editor' => 'Editor/a',
  'camara' => 'Operador/a de Cámara',
  'vestuario' => 'Vestuarista / Maquillador/a',
  'diseno-produccion' => 'Diseñador/a de Producción',
  'director-arte' => 'Director/a de Arte',
  'director-produccion' => 'Director/a de Producción',
  'productor-ejecutivo' => 'Productor/a Ejecutivo/a',
];

$in = json_input();
$puesto = clean($in['puesto'] ?? '');
if(!isset($positions[$puesto])){
  json_out(['error' => 'Puesto no válido'], 400);
}

$nombre = clean($in['nombre'] ?? '');
$edad = intval($in['edad'] ?? 0);
$roblox = clean($in['roblox_user'] ?? '');
$discord = clean($in['discord_user'] ?? '');
$portfolio = clean($in['portfolio'] ?? '');
$experiencia = clean($in['experiencia'] ?? '');
$disponibilidad = clean($in['disponibilidad'] ?? '');
$motivacion = clean($in['motivacion'] ?? '');

if($nombre === '' || $edad <= 0 || $roblox === '' || $discord === '' || $experiencia === '' || $disponibilidad === '' || $motivacion === ''){
  json_out(['error' => 'Faltan campos obligatorios'], 400);
}

$puesto_nombre = $positions[$puesto];
$mysqli = db_connect();
$stmt = $mysqli->prepare(
  "INSERT INTO candidaturas (puesto, puesto_nombre, nombre, edad, roblox_user, discord_user, portfolio, experiencia, disponibilidad, motivacion)
   VALUES (?,?,?,?,?,?,?,?,?,?)"
);
$stmt->bind_param('sssissssss', $puesto, $puesto_nombre, $nombre, $edad, $roblox, $discord, $portfolio, $experiencia, $disponibilidad, $motivacion);

if(!$stmt->execute()){
  json_out(['error' => 'No se pudo guardar la candidatura'], 500);
}

json_out(['ok' => true, 'id' => $mysqli->insert_id]);
