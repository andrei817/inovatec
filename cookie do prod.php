<?php
include("php/Config.php");

session_start();

// Verificar se o usuário já está logado pela sessão
if (isset($_SESSION['user_id'])) {
    // O usuário já está logado, redireciona para a página de sucesso
    header("Location: ambiente.php");
    exit();
}

// Verificar se o cookie 'remember_token' existe
if (isset($_COOKIE['remember_token'])) {
    $remember_token = $_COOKIE['remember_token'];
    
    // Procurar o token no banco de dados
    $sql = "SELECT * FROM produtor WHERE remember_token = '$remember_token'";
    $result = mysqli_query($conn, $sql);
    $produtor = mysqli_fetch_assoc($result);
    
    if ($produtor) {
        // Token encontrado, logar o usuário automaticamente
        $_SESSION['user_id'] = $produtor['id'];
        $_SESSION['user_email'] = $produtor['email'];
        
        // Redireciona para a página de sucesso
        header("Location: ambiente.php");
        exit();
    }
}

// Se não tiver token ou usuário não encontrado, exibe o formulário de login
?>
