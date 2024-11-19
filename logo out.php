<?php
session_start();

// Verifica se o logout foi confirmado
if (isset($_GET['confirm_logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php'); // Redireciona para a página inicial após o logout
    exit;
}

// Sinaliza que o modal deve ser exibido
$showModal = true;
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout com Pop-up</title>
    <style>

        
        /* Estilos do modal */
        .modal {
            display: none; /* Inicialmente escondido */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Fundo escuro */
        }
        .modal-content {
            background-color: #5214cb;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 80%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .btn {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-yes {
            background-color: #4CAF50;
            color: white;
        }
        .btn-no {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<!-- Modal de Logout -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Deseja se deslogar?</h2>
        <button class="btn btn-yes" onclick="confirmLogout()">Sim</button>
        <button class="btn btn-no" onclick="closeModal()">Não</button>
    </div>
</div>

<script>
    // Exibe o modal automaticamente se o PHP sinalizar
    const showModal = <?php echo isset($showModal) && $showModal ? 'true' : 'false'; ?>;
    if (showModal) {
        document.getElementById('logoutModal').style.display = 'block';
    }

    // Função para fechar o modal
    function closeModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    // Função para confirmar o logout
    function confirmLogout() {
        closeModal(); // Fecha o modal
        alert('Obrigado por usar o nosso sistema!!!'); // Mostra a mensagem
        window.location.href = '?confirm_logout=true'; // Redireciona para confirmar o logout
    }
</script>

</body>
</html>
