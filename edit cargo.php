<?php
session_start();
include("php/Config.php");

// Verifica se o ID do cargo foi passado via GET
if (isset($_GET['id'])) {
    $cargo_id = intval($_GET['id']); // Assegura que o ID é um número inteiro

    // Consulta o cargo a ser editado
    $sql = "SELECT * FROM cargos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $cargo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cargo = $result->fetch_assoc();
    } else {
        echo "Cargo não encontrado.";
        exit();
    }
} else {
    echo "ID do cargo não informado.";
    exit();
}

// Atualiza o cargo se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_cargo = trim($_POST['nome_cargo']); // Evita espaços em branco desnecessários

    if (empty($nome_cargo)) {
        echo "O nome do cargo não pode estar vazio.";
    } else {
        $sql = "UPDATE cargos SET nome = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Erro ao preparar atualização: " . $conn->error);
        }

        $stmt->bind_param("si", $nome_cargo, $cargo_id);

        if ($stmt->execute()) {
            echo "Cargo atualizado com sucesso!";
            header("Location: Cargo.php"); // Redireciona para a lista de cargos
            exit();
        } else {
            echo "Erro ao atualizar o cargo: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Editar Cargo de Staff</title>
</head>
<body>
    <h2>Editar Cargo de Staff</h2>
    <form method="POST">
        <label for="nome_cargo">Nome do Cargo:</label>
        <input type="text" name="nome_cargo" value="<?php echo htmlspecialchars($cargo['nome']); ?>" required><br><br>

        <button type="submit">Atualizar Cargo</button>
    </form>

    <br>
    <a href="listar_cargos.php">Voltar para a lista de cargos</a>
</body>
</html>
