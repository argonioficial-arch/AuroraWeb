<?php
header('Content-Type: application/json; charset=utf-8');

function json_input(){
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function json_out($data, $code = 200){
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}

function require_method($method){
  if($_SERVER['REQUEST_METHOD'] !== $method){
    json_out(['error' => 'Método no permitido'], 405);
  }
}

function clean($v){
  return is_string($v) ? trim($v) : $v;
}
