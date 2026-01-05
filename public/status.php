<?php
$DATABASE_URL = getenv("DATABASE_URL");
$db = pg_connect($DATABASE_URL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'], $_POST['status'])) {
  $cliente_id = $_POST['cliente_id'];
  $status     = $_POST['status'];

  $query = "UPDATE clientes_pagbank SET status = $1 WHERE cliente_id = $2";
  pg_query_params($db, $query, [$status, $cliente_id]);
}
