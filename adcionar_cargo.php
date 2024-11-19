<?php
// Incluir o arquivo de configuração do banco de dados
include('php/Config.php');

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber e sanitizar os dados do formulário
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
   
    // Validar os campos
    if (empty($nome)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Inserir o cargo no banco de dados
        $sql = "INSERT INTO cargos (nome) VALUES ('$nome')";
        if (mysqli_query($conn, $sql)) {
            echo "Cargo adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar o cargo: " . mysqli_error($conn);
        }
    }
}

// Buscar os eventos disponíveis para exibição no formulário
$eventos_sql = "SELECT id, nome FROM eventos";
$eventos_result = mysqli_query($conn, $eventos_sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Cargo</title>
</head>
<body>
    <h1>Adicionar Cargo de Staff</h1>
    <form action="" method="POST">
        <label for="nome">Nome do Cargo:</label>
        <input type="text" name="nome" id="nome" required><br>

        <button type="submit">Adicionar Cargo</button>
    </form>
</body>
</html>
