<?php
session_start();

// 1. Apaga os dados da sessão
$_SESSION = array();

// 2. Destrói a sessão no servidor
session_destroy();

// 3. Apaga os cookies de "Lembrar-me" do navegador
//    Para apagar um cookie, definimos sua data de expiração para o passado.
if (isset($_COOKIE['lembrar_admin_token'])) {
    setcookie('lembrar_admin_token', '', time() - 3600, "/");
}
if (isset($_COOKIE['lembrar_admin_usuario'])) {
    setcookie('lembrar_admin_usuario', '', time() - 3600, "/");
}

// 4. Redireciona para a página de login
header('Location: login-admin.php');
exit;
?>