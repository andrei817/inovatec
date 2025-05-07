<?php
// Inclua seu arquivo de configuração
include("php/Config.php");

// Verifique se o parâmetro 'delete' está presente na URL
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // Pega o ID do staff a ser excluído

    // Consulta SQL para excluir o staff com o ID fornecido
    $sql = "DELETE FROM staffs_eventos WHERE id = ?";

    // Preparando a consulta para evitar SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // 'i' indica que o parâmetro é um inteiro

        // Executa a consulta de exclusão
        if ($stmt->execute()) {
            //echo "Staff excluído com sucesso!";
            header("Location: lista de staff.php"); // Redireciona para a lista de staffs após a exclusão
            exit;
        } else {
            echo "Erro ao excluir o staff.";
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        // Caso haja erro na consulta SQL
        echo "Erro na consulta SQL de exclusão.";
    }
} else {
    echo "ID não fornecido.";
    exit;
}

// Fechar a conexão com o banco
$conn->close();
?>
