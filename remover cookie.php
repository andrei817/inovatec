<?php
session_start();
include("php/Config.php");

// Destruir a sessão
session_unset();
session_destroy();

// Remover o cookie 'remember_token'
setcookie('remember_token', '', time() - 3600, "/"); // Definir tempo passado para destruir o cookie

// Redireciona para a página de login
header("Location: index.php");
exit();
?>
