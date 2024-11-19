<?php
session_start();
if (!isset($_SESSION['produtor_id'])) {
    header("Location: login.php"); // Redireciona para o login se não estiver autenticado
    exit();
}

// Conteúdo da página de dashboard para o produtor de eventos
echo "<h2>Bem-vindo ao Painel do Produtor</h2>";
echo "<a href='logout.php'>Logout</a>";
?>
