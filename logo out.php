<?php
// Inicia a sessão
session_start();

// Inclui a configuração do banco de dados
include("php/Config.php");

// Verifica se o usuário está logado
if (isset($_SESSION['produtor_id'])) {
    $userId = $_SESSION['produtor_id'];
    $updateTokenSql = "UPDATE produtor SET remember_token = NULL WHERE id = $userId";
    mysqli_query($conn, $updateTokenSql);
}

// Apaga o cookie de "manter-me conectado"
setcookie('remember_token', '', time() - 3600, "/", "", false, true);

// Destrói a sessão
session_unset();
session_destroy();

// Redireciona para a página inicial ou de login
header("Location: index.php"); // Substitua "index.php" pelo destino desejado.
exit();
?>
