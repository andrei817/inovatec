<?php
include("php/Config.php");

// Verificar se o evento a ser deletado foi enviado via GET
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Passo 1: Deletar as associações na tabela problemas_evento
    $sqlDeleteProblemasEvento = "DELETE FROM problemas_evento WHERE evento_id = ?";
    if ($stmt = $conn->prepare($sqlDeleteProblemasEvento)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (ID do evento)
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Erro ao excluir associação com problemas_evento: " . $conn->error;
    }

    // Passo 2: Deletar as associações na tabela evento_buffet
    $sqlDeleteAssocBuffet = "DELETE FROM evento_buffet WHERE evento_id = ?";
    if ($stmt = $conn->prepare($sqlDeleteAssocBuffet)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (ID do evento)
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Erro ao excluir associação com buffet: " . $conn->error;
    }

// Passo 3: Deletar as associações na tabela evento_objetivo
$sqlDeleteAssocObjetivo = "DELETE FROM evento_objetivo WHERE evento_id = ?";
if ($stmt = $conn->prepare($sqlDeleteAssocObjetivo)) {
    $stmt->bind_param("i", $id); // 'i' para inteiro (ID do evento)
    $stmt->execute();
    $stmt->close();
} else {
    echo "Erro ao excluir associação com objetivo: " . $conn->error;
}

// Passo 4: Deletar as associações na tabela evento_tema
$sqlDeleteAssocTema = "DELETE FROM evento_tema WHERE evento_id = ?";
if ($stmt = $conn->prepare($sqlDeleteAssocTema)) {
    $stmt->bind_param("i", $id); // 'i' para inteiro (ID do evento)
    $stmt->execute();
    $stmt->close();
} else {
    echo "Erro ao excluir associação com tema: " . $conn->error;
}

    // Passo 5: Deletar o evento da tabela eventos
    $sqlDeleteEvento = "DELETE FROM eventos WHERE id = ?";
    if ($stmt = $conn->prepare($sqlDeleteEvento)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (ID do evento)
        if ($stmt->execute()) {
            echo "Evento deletado com sucesso!";
            // Redirecionar de volta para a lista de eventos após a exclusão
            header("Location: lista de eventos.php"); // Troque 'lista_de_eventos.php' pela página de listagem de eventos
            exit();
        } else {
            echo "Erro ao deletar evento: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erro ao preparar a exclusão do evento: " . $conn->error;
    }
} else {
    echo "ID do evento não fornecido.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
