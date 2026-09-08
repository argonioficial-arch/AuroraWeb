<?php
// Autenticación sin estado (token firmado en una cookie), para que funcione
// aunque Wasmer Edge sirva tu app desde varias instancias distintas.

function admin_secret(){
  $s = getenv('ADMIN_TOKEN_SECRET');
  return $s ?: 'cambia-este-secreto';
}

function create_admin_token(){
  $payload = json_encode(['u' => getenv('ADMIN_USER'), 'exp' => time() + 60 * 60 * 8]); // 8 horas
  $payload_b64 = base64_encode($payload);
  $sig = hash_hmac('sha256', $payload_b64, admin_secret());
  return $payload_b64 . '.' . $sig;
}

function verify_admin_token($token){
  if(!$token || strpos($token, '.') === false) return false;
  list($payload_b64, $sig) = explode('.', $token, 2);
  $expected = hash_hmac('sha256', $payload_b64, admin_secret());
  if(!hash_equals($expected, $sig)) return false;
  $payload = json_decode(base64_decode($payload_b64), true);
  if(!$payload || !isset($payload['exp']) || $payload['exp'] < time()) return false;
  return true;
}

function require_admin(){
  $token = $_COOKIE['aurora_admin_token'] ?? '';
  if(!verify_admin_token($token)){
    json_out(['error' => 'No autorizado'], 401);
  }
}
