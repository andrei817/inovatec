<?php
// Incluir o arquivo de conexão
include('php/Config.php');

// Verificar se o ID do evento foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar os dados do evento no banco de dados
    $sql = "SELECT * FROM objetivo WHERE id = $id";
    $resultado = mysqli_query($conn, $sql);
    $objetivo = mysqli_fetch_assoc($resultado);
} else {
    // Caso o ID não seja passado
    echo "Objetivo não encontrado.";
    exit;
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    

    // Atualizar os dados no banco de dados
    $sql_update = "UPDATE objetivo SET nome = '$nome', descricao = '$descricao' WHERE id = $id";
    
    if (mysqli_query($conn, $sql_update)) {
        echo "Objetivo alterado com sucesso!";
        header('Location: lista de objetivos.php'); // Redirecionar para a página inicial (ou para a tabela de eventos)
    } else {
        echo "Erro ao alterar o objetivo: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Objetivo</title>
</head>
<body>
    <h1>Atualizar Objetivo</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $objetivo['nome']; ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required><?php echo $objetivo['descricao']; ?></textarea><br>

        <button type="submit">Alterar </button>
    </form>
</body>
</html>
