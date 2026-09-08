<?php
// Todas las credenciales vienen de variables de entorno definidas en app.yaml,
// nunca hardcodeadas aquí.

function db_connect(){
  $host = getenv('DB_HOST');
  $port = (int)(getenv('DB_PORT') ?: 3306);
  $user = getenv('DB_USER');
  $pass = getenv('DB_PASS');
  $name = getenv('DB_NAME');

  $mysqli = mysqli_init();
  // La mayoría de bases de datos MySQL gestionadas (PlanetScale incluida)
  // requieren conexión por SSL.
  mysqli_options($mysqli, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
  mysqli_ssl_set($mysqli, null, null, null, null, null);

  $ok = @mysqli_real_connect($mysqli, $host, $user, $pass, $name, $port, null, MYSQLI_CLIENT_SSL);
  if(!$ok){
    json_out(['error' => 'No se pudo conectar a la base de datos.'], 500);
  }
  mysqli_set_charset($mysqli, 'utf8mb4');
  return $mysqli;
}
