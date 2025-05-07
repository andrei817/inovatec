<?php
include ("php/Config.php");

// Verifica se o ID do tema foi passado via GET e é válido
if (!isset($_GET['excluir']) || !is_numeric($_GET['excluir'])) {
    echo "<script>alert('ID do tema não fornecido ou inválido.'); window.location.href='tema lista.php';</script>";
    exit();
}

$id_tema = intval($_GET['excluir']); // Converte o ID para inteiro de forma segura

// Verificar se o tema está associado a algum evento
$checkAssociationSql = "SELECT COUNT(*) AS totalEventos FROM eventos WHERE tema_id = ?";
$stmt = $conn->prepare($checkAssociationSql);
$stmt->bind_param("i", $id_tema); // 'i' para inteiro (id do tema)
$stmt->execute();
$stmt->bind_result($totalEventos);
$stmt->fetch();
$stmt->close();

// Se o tema estiver associado a eventos, redireciona com erro na URL
if ($totalEventos > 0) {
    header("Location: tema lista.php?erro=1");
    exit(); // Interrompe o script
}

// Verificar se o tema está associado na tabela 'evento_tema'
$checkIntermediariaSql = "SELECT COUNT(*) FROM evento_tema WHERE tema_id = ?";
$stmt = $conn->prepare($checkIntermediariaSql);
$stmt->bind_param("i", $id_tema);
$stmt->execute();
$stmt->bind_result($vinculadoAuxiliar);
$stmt->fetch();
$stmt->close();

if ($vinculadoAuxiliar > 0) {
    header("Location: tema lista.php?erro=2"); // Vinculado indiretamente
    exit();
}

// Atualizar os eventos para remover a referência ao tema
$updateEventosSql = "UPDATE eventos SET tema_id = NULL WHERE tema_id = ?";
$stmt = $conn->prepare($updateEventosSql);
$stmt->bind_param("i", $id_tema); // 'i' para inteiro (id do tema)
$stmt->execute();
$stmt->close();

// Remover as referências na tabela evento_tema
$deleteEventoTemaSql = "DELETE FROM evento_tema WHERE tema_id = ?";
$stmt = $conn->prepare($deleteEventoTemaSql);
$stmt->bind_param("i", $id_tema); // 'i' para inteiro (id do tema)
$stmt->execute();
$stmt->close();

// Agora, excluir o tema
$deleteTemaSql = "DELETE FROM tema WHERE id = ?";
$stmt = $conn->prepare($deleteTemaSql);
$stmt->bind_param("i", $id_tema); // 'i' para inteiro (id do tema)
if ($stmt->execute()) {
    header("Location: tema lista.php");
    exit();
} else {
    echo "<script>alert('Erro ao excluir tema.'); window.location.href='tema lista.php';</script>";
}
$stmt->close();
