<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: logar produtor.php");
    exit;
}

// Dados fictícios para exibição (em produção, você buscaria isso no banco de dados)
$nome = "Produtor de Eventos";
$email = "produtor@exemplo.com";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Produtor</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }

        /* Ícone de perfil */
        .profile-icon {
            cursor: pointer;
            width: 40px;
            height: 40px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 20px;
        }

        /* Caixinha de perfil */
        .profile-box {
            position: absolute;
            top: 60px;
            right: 20px;
            width: 200px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            padding: 15px;
        }

        .profile-box h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .profile-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        /* Botões dentro da caixinha */
        .profile-box button {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Ícone de perfil que, ao clicar, mostra a caixinha de perfil -->
    <div class="profile-icon" onclick="toggleProfileBox()">P</div>

    <!-- Caixinha de perfil -->
    <div class="profile-box" id="profileBox">
        <h3><?php echo $username; ?></h3>
        <p><?php echo $email; ?></p>
        <button class="edit-btn" onclick="window.location.href='perfil.php'">Editar Perfil</button>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <script>
        // Função para exibir ou ocultar a caixinha de perfil
        function toggleProfileBox() {
            const profileBox = document.getElementById('profileBox');
            profileBox.style.display = profileBox.style.display === 'flex' ? 'none' : 'flex';
        }

        // Fecha a caixinha de perfil ao clicar fora dela
        window.onclick = function(event) {
            const profileBox = document.getElementById('profileBox');
            const profileIcon = document.querySelector('.profile-icon');
            if (event.target !== profileBox && event.target !== profileIcon) {
                profileBox.style.display = 'none';
            }
        }
    </script>

</body>
</html>
