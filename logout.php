<?php
require __DIR__ . '/helpers.php';
require_method('POST');
setcookie('aurora_admin_token', '', ['expires' => time() - 3600, 'path' => '/']);
json_out(['ok' => true]);
