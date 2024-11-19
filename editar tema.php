<?php
session_start();
include("php/Config.php");

// Verifica se o ID do tema foi passado via GET e é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID do tema não fornecido ou inválido.'); window.location.href='tema lista.php';</script>";
    exit();
}

$id_tema = intval($_GET['id']); // Converte o ID para inteiro de forma segura

// Consulta o tema de evento a ser editado
$sql = "SELECT * FROM tema WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tema);
$stmt->execute();
$result = $stmt->get_result();

// Se o tema de evento foi encontrado
if ($result->num_rows > 0) {
    $tema = $result->fetch_assoc();
} else {
    echo "<script>alert('Tema não encontrado.'); window.location.href='tema lista.php';</script>";
    exit();
}

// Verifica se o formulário foi enviado para atualizar o tema
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);

    // Atualiza os dados do tema no banco de dados
    $sql = "UPDATE tema SET nome = ?, descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $descricao, $id_tema);

    if ($stmt->execute()) {
        echo "<script>alert('Tema atualizado com sucesso!'); window.location.href='tema lista.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar o tema: {$conn->error}');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tema</title>
    <link rel="stylesheet" href="editar tema.css">
</head>
<body>
<div id="header"></div> <!-- Div onde o menu será injetado -->

<script>
fetch('/menu principal.html')
    .then(response => response.text())
    .then(data => {
        document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<div class="content">
    <section class="login-section">
        <div class="login-box">
            <h2>Editar Tema</h2>
            <a href="tema lista.php" class="close-btn">&times;</a>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id_tema) ?>">

                <div class="input-group">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($tema['nome']) ?>" required>
                </div>

                <div class="input-group">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" required><?= htmlspecialchars($tema['descricao']) ?></textarea>
                </div>

                <div class="buttons">
                    <input type="submit" name="editar" value="Atualizar Tema">
                    <a href="tema lista.php"><button type="button">Cancelar</button></a>
                </div>
            </form>
        </div>
    </section>
</div>
</body>
</html>
