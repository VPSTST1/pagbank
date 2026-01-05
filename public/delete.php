<?php
$DATABASE_URL = getenv("DATABASE_URL");
$db = pg_connect($DATABASE_URL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['cliente_id'])) {
    $cliente_id = trim($_POST['cliente_id']);
    $result = pg_query_params($db, "DELETE FROM clientes_pagbank WHERE cliente_id = $1", [$cliente_id]);
    if (pg_affected_rows($result) > 0) {
      echo "✅ Cliente $cliente_id excluído.";
    } else {
      echo "⚠ Nenhum cliente foi excluído. Verifique o ID.";
    }

  } elseif (isset($_POST['apagar_tudo'])) {
    $res = pg_query($db, "DELETE FROM clientes_pagbank");
    if ($res) {
      echo "🗑 Todos os registros foram apagados.";
    } else {
      echo "❌ Erro ao apagar todos os registros.";
    }

  } else {
    echo "❌ Requisição inválida.";
  }
} else {
  echo "❌ Método inválido.";
}
?>
