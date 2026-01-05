<?php
session_start();
$DATABASE_URL = getenv("DATABASE_URL");
$db = pg_connect($DATABASE_URL);

$cliente_id = $_SESSION['cliente_id'] ?? null;

if ($db && $cliente_id) {
  $result = pg_query_params($db, "SELECT status FROM clientes_pagbank WHERE cliente_id = $1", [$cliente_id]);
  $row = pg_fetch_assoc($result);
  echo $row['status'];
} else {
  echo 'erro';
}
