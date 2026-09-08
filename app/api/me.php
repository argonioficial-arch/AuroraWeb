<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';
$token = $_COOKIE['aurora_admin_token'] ?? '';
json_out(['authenticated' => verify_admin_token($token)]);
