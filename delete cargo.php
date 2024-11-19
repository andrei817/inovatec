<?php
session_start();
include("php/Config.php");

if (isset($_GET['id'])) {
    $cargo_id = intval($_GET['id']); // Garante que o ID é um número inteiro

    // Prepara o comando SQL para deletar o cargo
    $sql = "DELETE FROM cargos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Vincula o parâmetro do ID ao comando SQL
    $stmt->bind_param("i", $cargo_id);

    // Executa a exclusão
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Cargo excluído com sucesso!";
        header("Location: Cargo.php"); // Redireciona para a página de listagem
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao excluir o cargo: " . $stmt->error;
        header("Location: Cargo.php"); // Redireciona para a página de listagem com mensagem de erro
        exit();
    }
} else {
    $_SESSION['erro'] = "ID do cargo não especificado!";
    header("Location: Cargo.php"); // Redireciona para a página de listagem com mensagem de erro
    exit();
}
?>
