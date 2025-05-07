<?php
// Incluir o arquivo de conexão
include("php/Config.php");

// Verifica se o ID do objetivo foi passado via GET e é válido
if (!isset($_GET['excluir']) || !is_numeric($_GET['excluir'])) {
    echo "<script>alert('ID do objetivo não fornecido ou inválido.'); window.location.href='lista de objetivos.php';</script>";
    exit();
}

$id_objetivo = intval($_GET['excluir']); // Converte o ID para inteiro de forma segura

// Verificar se o objetivo está associado a algum evento
$checkAssociacaoSql = "SELECT COUNT(*) FROM eventos WHERE objetivo_id = ?";
$stmt = $conn->prepare($checkAssociacaoSql);
$stmt->bind_param("i", $id_objetivo); // 'i' para inteiro (id do objetivo)
$stmt->execute();
$stmt->bind_result($countEventos);
$stmt->fetch();
$stmt->close();

// Se o objetivo estiver associado a um evento, redireciona com o parâmetro 'erro=1'
if ($countEventos > 0) {
    // Não permitir a exclusão, pois o objetivo está vinculado a eventos
    header("Location: lista de objetivos.php?erro=1");
    exit();
}

// Verificar se o objetivo ainda está associado na tabela evento_objetivo
$checkEventoObjetivoSql = "SELECT COUNT(*) FROM evento_objetivo WHERE objetivo_id = ?";
$stmt = $conn->prepare($checkEventoObjetivoSql);
$stmt->bind_param("i", $id_objetivo);
$stmt->execute();
$stmt->bind_result($countRelacoes);
$stmt->fetch();
$stmt->close();

// Se ainda houver alguma associação na tabela evento_objetivo, bloqueia exclusão
if ($countRelacoes > 0) {
    header("Location: lista de objetivos.php?erro=2"); // erro=2 para indicar essa situação
    exit();
}


// Caso contrário, pode prosseguir com a exclusão
// 1. Primeiro desvincula o objetivo do evento na tabela 'eventos'
$updateEventosSql = "UPDATE eventos SET objetivo_id = NULL WHERE objetivo_id = ?";
$stmt = $conn->prepare($updateEventosSql);
if ($stmt === false) {
    echo "<script>alert('Erro ao preparar a consulta para atualizar eventos.'); window.location.href='lista de objetivos.php';</script>";
    exit();
}
$stmt->bind_param("i", $id_objetivo); // 'i' para inteiro (id do objetivo)
$stmt->execute();
$stmt->close(); // Fecha o statement após a execução da atualização

// 2. Excluir as referências na tabela 'evento_objetivo'
$deleteEventoObjetivoSql = "DELETE FROM evento_objetivo WHERE objetivo_id = ?";
$stmt = $conn->prepare($deleteEventoObjetivoSql);
if ($stmt === false) {
    echo "<script>alert('Erro ao preparar a consulta para excluir referências do objetivo.'); window.location.href='lista de objetivos.php';</script>";
    exit();
}
$stmt->bind_param("i", $id_objetivo); // 'i' para inteiro (id do objetivo)
$stmt->execute();
$stmt->close(); // Fecha o statement após a execução da exclusão das referências

// 3. Agora, excluir o objetivo
$deleteObjetivoSql = "DELETE FROM objetivo WHERE id = ?";
$stmt = $conn->prepare($deleteObjetivoSql);
if ($stmt === false) {
    echo "<script>alert('Erro ao preparar a consulta para excluir o objetivo.'); window.location.href='lista de objetivos.php';</script>";
    exit();
}
$stmt->bind_param("i", $id_objetivo); // 'i' para inteiro (id do objetivo)
if ($stmt->execute()) {
    // Objetivo excluído com sucesso, redireciona
    header("Location: lista de objetivos.php");
    exit();
} else {
    // Se houve erro ao excluir, mostra a mensagem
    echo "<script>alert('Erro ao excluir objetivo.'); window.location.href='lista de objetivos.php';</script>";
}

$stmt->close(); // Fecha o statement após a execução da exclusão
