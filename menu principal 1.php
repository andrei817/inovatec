<?php
session_start();

include("php/Config.php");

// Cookies para preencher o formulário
$emailCookie = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : '';

// Verificação do POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['senha'])) {
    $email = $_POST['email'];
    $senhaDigitada = $_POST['senha'];

    // Buscar produtor pelo email
    $sql = "SELECT * FROM produtor WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $produtor = mysqli_fetch_assoc($result);
        $senhaBanco = $produtor['senha'];

        // Detecta se é um hash bcrypt (começa com $2y$ ou $2a$)
        $senhaEhBcrypt = preg_match('/^\$2[ayb]\$/', $senhaBanco);

        // 1️⃣ Verifica a senha usando bcrypt
        $senhaCorreta = $senhaEhBcrypt
            ? password_verify($senhaDigitada, $senhaBanco)
            : md5($senhaDigitada) === $senhaBanco;

        if ($senhaCorreta) {
            // 2️⃣ Se estava em MD5, atualiza para Bcrypt
            if (!$senhaEhBcrypt) {
                $novoHash = password_hash($senhaDigitada, PASSWORD_BCRYPT);
                $updateSenha = "UPDATE produtor SET senha = ? WHERE id = ?";
                $stmtUpdate = mysqli_prepare($conn, $updateSenha);
                mysqli_stmt_bind_param($stmtUpdate, "si", $novoHash, $produtor['id']);
                mysqli_stmt_execute($stmtUpdate);
            }

            // 3️⃣ Login bem-sucedido
            $_SESSION['id'] = $produtor['id'];
            $_SESSION['email'] = $produtor['email'];
            $_SESSION['nome'] = $produtor['nome'];

            if (isset($_POST['rememberMe'])) {
                $token = bin2hex(random_bytes(16));
                $updateTokenSql = "UPDATE produtor SET remember_token = ? WHERE id = ?";
                $stmtToken = mysqli_prepare($conn, $updateTokenSql);
                mysqli_stmt_bind_param($stmtToken, "si", $token, $produtor['id']);
                mysqli_stmt_execute($stmtToken);

                setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
                setcookie('rememberMe', '1', time() + (86400 * 30), "/", "", false, false);
                setcookie('user_email', $email, time() + (86400 * 30), "/", "", false, false);
            } else {
                // Limpa cookies se desmarcado
                setcookie('rememberMe', '', time() - 3600, "/", "", false, false);
                setcookie('user_email', '', time() - 3600, "/", "", false, false);
                setcookie('remember_token', '', time() - 3600, "/", "", false, true);
            }

            $_SESSION['login_success'] = true;
            header('Location: ambiente.php');
            exit;
        } else {
            $_SESSION['senha_incorreta'] = true;
        }
    } else {
        $_SESSION['produtor_nao_encontrado'] = true;
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Agenda de Eventos</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js"> </script>


    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            background-image: url(Gestão.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            overflow: hidden;


        }

        /* Estilo do cabeçalho */

        header {
            display: flex;
            width: 100%;
            height: 70px;
            text-align: left;
            justify-content: space-between;
            align-items: left;
            position: sticky;
            top: 5;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-image: radial-gradient(circle, #5214Cb, #3c1292);

        }

        .logo {
            display: flex;
            justify-content: center;
            /* Centraliza horizontalmente dentro da div */
            align-items: center;
            /* Centraliza verticalmente dentro da div */
            width: 150px;
            /* Define o tamanho da logo */
            height: auto;
            /* Mantém a proporção da imagem */
            margin: 7px;
            margin-left: 365px;
        }


        header h1 {
            display: flex;
            margin: 5px;
            padding: 0 auto;
            font-size: 20px;
            text-align: left;
            position: sticky;
        }

        header p {
            display: flex;
            font-size: 20px;
            margin-top: 5px;
            white-space: nowrap;
        }


        .logo-foto {
            display: flex;
            width: 100px;
            /* Define o tamanho da logo */
            height: auto;
            align-items: right;
            opacity: 0.9;
            margin-left: 18px;
            padding: 0 auto;
            text-align: left;
            margin-top: 10px;
            position: sticky;
        }




        nav ul {
            list-style: none;
            padding: 12px;
            margin: 13px 0;
            display: flex;
            justify-content: right;
            gap: 30px;
            margin-right: 159px;

        }

        .bi bi-gear-fill {
            background-color: white;
        }


        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
            transition: color 0.3s;
            position: relative;
        }

        nav ul li a:hover {
            color: yellow;

        }

        nav ul li a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: rgb(255, 255, 255);
            left: 0;
            bottom: 0;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease;
        }

        nav ul li a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .active::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

    </style>


</head>

<body>

    <header>

        <div class="logo-foto">
            <img src="Logo_SGE_inova.png" width=80% height="100%">

            <div class="header-content">
                <h1> S.G.E.</h1>
                <p> Sistema de Gestão de Eventos</p>

            </div>
        </div>



        <div class="logo">

            <img src="eventos.png" width=103% height="100%">

        </div>


        <nav>

            <ul>

                <li><a href="index.php" title="Página inicial">Home</a></li>
                <li><a href="ajuda 1.php" title="Obtenha ajuda">Ajuda</a></li>
                <li><a href="Sobre 1.php" title="Sobre nós">Sobre</a></li>
                <a onclick="abrirPopUp()" title="Área Restrita">
                    <li> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                        </svg>
                </a></li>

            </ul>
        </nav>
    </header>

    </header>