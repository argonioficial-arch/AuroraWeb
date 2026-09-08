<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
require_method('POST');

$in = json_input();
$usuario = clean($in['usuario'] ?? '');
$password = $in['password'] ?? '';

$adminUser = getenv('ADMIN_USER') ?: 'argoni';
$adminPass = getenv('ADMIN_PASS') ?: '';

if($adminPass !== '' && hash_equals($adminUser, $usuario) && hash_equals($adminPass, $password)){
  $token = create_admin_token();
  setcookie('aurora_admin_token', $token, [
    'expires'  => time() + 60 * 60 * 8,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Strict',
    'secure'   => true,
  ]);
  json_out(['ok' => true]);
} else {
  json_out(['error' => 'Usuario o contraseña incorrectos'], 401);
}
