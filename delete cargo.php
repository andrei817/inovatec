<?php
session_start();
include("php/Config.php");

if (isset($_GET['id'])) {
    $cargo_id = intval($_GET['id']); // Garante que o ID é um número inteiro

    // Verificar se o cargo está associado a algum staff
    $verificarVinculosSql = "
        SELECT s.nome AS staff_nome, c.nome AS cargo_nome 
        FROM staff_cargo sc 
        INNER JOIN staffs_eventos s ON sc.staff_id = s.id 
        INNER JOIN cargos c ON sc.cargo_id = c.id 
        WHERE c.id = ?
    ";
    $stmt = $conn->prepare($verificarVinculosSql);
    if (!$stmt) {
        die("Erro ao preparar a consulta de verificação de vínculos: " . $conn->error);
    }

    $stmt->bind_param("i", $cargo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // O cargo está associado a um ou mais staffs
        $staffs = [];
        while ($row = $result->fetch_assoc()) {
            $staffs[] = $row['staff_nome'];
        }
        $stmt->close();

        $staffList = implode(", ", $staffs); // Lista os nomes dos staffs associados
        $_SESSION['erro'] = "O cargo está associado aos seguintes staffs: $staffList. Não é possível excluí-lo.";
        header("Location: Cargo.php"); // Redireciona para a página de listagem
        exit();
    }

    // O cargo não está associado a nenhum staff; pode ser excluído
    $stmt->close();

    // Excluir o cargo da tabela cargos
    $sql = "DELETE FROM cargos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta de exclusão do cargo: " . $conn->error);
    }

    $stmt->bind_param("i", $cargo_id);

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
