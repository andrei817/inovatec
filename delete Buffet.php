<?php
// Inclua seu arquivo de configuração
include("php/Config.php");

// Verifique se o parâmetro 'delete' está presente na URL
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // Pega o ID do buffet a ser excluído

    // Passo 1: Verificar se o buffet está associado a algum evento
    $checkAssociacaoSql = "SELECT COUNT(*) FROM evento_buffet WHERE buffet_id = ?";
    if ($stmt = $conn->prepare($checkAssociacaoSql)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (id do buffet)
        $stmt->execute();
        $stmt->bind_result($countEventos);
        $stmt->fetch();
        $stmt->close();

        // Se o buffet estiver associado a algum evento, redireciona com o parâmetro 'erro=associado'
        if ($countEventos > 0) {
            header("Location: lista de buffet.php?erro=associado");
            exit();
        }
    }

    // Passo 2: Remove as referências ao buffet na tabela intermediária 'evento_buffet'
    $deleteEventoBuffetSql = "DELETE FROM evento_buffet WHERE buffet_id = ?";
    if ($stmt = $conn->prepare($deleteEventoBuffetSql)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (id do buffet)
        $stmt->execute();
        $stmt->close();
    }

    // Passo 3: Atualiza os eventos para remover a referência ao buffet
    $updateEventosSql = "UPDATE eventos SET buffet_id = NULL WHERE buffet_id = ?";
    if ($stmt = $conn->prepare($updateEventosSql)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (id do buffet)
        $stmt->execute();
        $stmt->close();
    }

    // Passo 4: Excluir o buffet da tabela buffet
    $deleteBuffetSql = "DELETE FROM buffet WHERE id = ?";
    if ($stmt = $conn->prepare($deleteBuffetSql)) {
        $stmt->bind_param("i", $id); // 'i' para inteiro (id do buffet)
        if ($stmt->execute()) {
            echo "Buffet excluído com sucesso!";
            header("Location: lista de buffet.php"); // Redireciona para a lista de buffets após a exclusão
            exit;
        } else {
            echo "Erro ao excluir o buffet.";
        }
        $stmt->close();
    }

} else {
    echo "ID não fornecido.";
    exit;
}

// Fechar a conexão com o banco
$conn->close();
?>
