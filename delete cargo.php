<?php
session_start();
include("php/Config.php");

if (isset($_GET['id'])) {
    $cargo_id = intval($_GET['id']); // Garante que o ID é um número inteiro

    // Passo 1: Atualizar os registros de staff para remover a referência ao cargo
    $updateStaffSql = "UPDATE staffs_eventos SET cargo_id = NULL WHERE cargo_id = ?";
    $stmt = $conn->prepare($updateStaffSql);
    if (!$stmt) {
        die("Erro ao preparar a consulta de atualização de staff: " . $conn->error);
    }

    // Vincula o parâmetro do ID do cargo ao comando SQL
    $stmt->bind_param("i", $cargo_id);

    // Executa a atualização
    if (!$stmt->execute()) {
        $_SESSION['erro'] = "Erro ao atualizar os registros de staff: " . $stmt->error;
        header("Location: Cargo.php"); // Redireciona para a página de listagem com mensagem de erro
        exit();
    }
    $stmt->close();

    // Passo 2: Excluir o cargo da tabela cargos
    $sql = "DELETE FROM cargos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta de exclusão do cargo: " . $conn->error);
    }

    // Vincula o parâmetro do ID do cargo ao comando SQL
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
    $stmt->close();
} else {
    $_SESSION['erro'] = "ID do cargo não especificado!";
    header("Location: Cargo.php"); // Redireciona para a página de listagem com mensagem de erro
    exit();
}
?>
