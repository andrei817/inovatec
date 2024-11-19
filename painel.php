<?php
session_start();

// Verifica se o produtor está logado
if (!isset($_SESSION['produtor_id'])) {
    header("Location: test-login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Produtor</title>
</head>
<body>
    <h2>Bem-vindo ao Painel do Produtor!</h2>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
