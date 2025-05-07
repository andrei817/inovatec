<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
</head>
<body>
    <form method="post" action="redefinir senha.php">
        <h2>Redefinir Senha</h2>
        <!-- Adicionando o token do link na URL -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        
        <label for="nova_senha">Nova senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" required>

        <label for="confirmar_senha">Confirmar senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required>

        <button type="submit">Redefinir senha</button>
    </form>
</body>
</html>



<?php
// Conexão com o banco de dados
include("php/Config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        echo "As senhas não coincidem. Tente novamente.";
        exit;
    }

    // Criptografar a nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // Verificar o token e a expiração
    $sql = "SELECT user_id FROM reset_senhas WHERE token = ? AND expiracao > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token válido, agora atualize a senha
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        // Atualiza a senha do usuário no banco de dados
        $update_sql = "UPDATE produtor SET senha = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $senha_hash, $user_id);
        $update_stmt->execute();

        // Remove o token usado para que ele não possa ser reutilizado
        $delete_sql = "DELETE FROM reset_senhas WHERE token = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $token);
        $delete_stmt->execute();

        echo "Senha redefinida com sucesso!";
    } else {
        echo "Link de redefinição inválido ou expirado.";
    }
}
?>
