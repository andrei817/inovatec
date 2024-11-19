<?php
// Incluir o arquivo de conexão
include("php/Config.php");

// Verificar se o ID foi passado via URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Excluir o evento do banco de dados
    $sql_delete = "DELETE FROM objetivo WHERE id = $id";

    if (mysqli_query($conn, $sql_delete)) {
        echo "Objetivo excluído com sucesso!";
        header('Location: lista de objetivos.php'); // Redirecionar de volta para a tabela de eventos
    } else {
        echo "Erro ao excluir o objetivo: " . mysqli_error($conn);
    }
} else {
    echo "ID do evento não especificado.";
}
?>
