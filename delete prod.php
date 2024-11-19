<?php
include ('php/Config.php'); // Inclui a conexão com o banco de dados

$deletadoComSucesso = false;
$idParaDeletar = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM produtor WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: listar produtores.php");
        exit;
    } else {
        echo "Erro ao excluir o produtor: " . mysqli_error($conn);
    }

    $deletadoComSucesso = true;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
        /* Estilos para o modal */
        .modal {
            display: none; /* Inicialmente oculto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Conteúdo do modal */
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Ícone de fechar */
        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #d9534f;
            cursor: pointer;
        }

        /* Botão de confirmação */
        .confirm-btn, .cancel-btn {
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirm-btn {
            background-color: #d9534f;
            color: #fff;
        }

        .cancel-btn {
            background-color: #ccc;
        }
    </style>
<body>

<!-- Modal de Confirmação de Exclusão -->
<div id="modalConfirmacao" class="modal">
    <div class="modal-content">
        <span class="close-icon" onclick="fecharModal()">&times;</span> <!-- Ícone "X" para fechar -->
        <h3>Tem certeza que deseja excluir este registro?</h3>
        <form id="formExclusao" action="" method="post" style="display: inline;">
            <input type="hidden" name="id" id="idParaDeletar">
            <button type="submit" class="confirm-btn">Sim, Excluir</button>
        </form>
        <button class="cancel-btn" onclick="fecharModal()">Cancelar</button>
    </div>
</div>

<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal">
    <div class="modal-content">
        <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h3>Registro excluído com sucesso!</h3>
    </div>
</div>

<script>
    // Função para abrir o modal de confirmação e definir o ID para exclusão
    function confirmarExclusao(id) {
        document.getElementById("idParaDeletar").value = id;
        document.getElementById("modalConfirmacao").style.display = "flex";
    }

    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalConfirmacao").style.display = "none";
        document.getElementById("modalSucesso").style.display = "none";
    }

    // Mostra o modal de sucesso se o registro foi deletado
    <?php if ($deletadoComSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        setTimeout(fecharModal, 2000); // Fecha automaticamente após 2 segundos
    <?php endif; ?>
</script>

    
</body>
</html>