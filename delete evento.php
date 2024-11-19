<?php
include("php/Config.php");

// Verificar se o evento a ser deletado foi enviado via GET
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Deletar o evento do banco de dados
    $sqlDelete = "DELETE FROM eventos WHERE id = $id";

    if ($conn->query($sqlDelete) === TRUE) {
        echo "Evento deletado com sucesso!";
        // Redirecionar de volta para a lista de eventos após a exclusão
        header("Location: lista de eventos.php"); // Troque 'lista_eventos.php' pela página de listagem de eventos
        exit();
    } else {
        echo "Erro ao deletar evento: " . $conn->error;
    }
}
?>
