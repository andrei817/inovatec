<?php
session_start();
include("php/Config.php");

// Inicializa variáveis de controle
$cadastroSucesso = false;
$senhaIncorreta = false;
$senhaIgualAntiga = false;
$senhaJaExisteOutro = false;

// Carrega status da sessão, se existir
if (isset($_SESSION['cadastroSucesso'])) {
    $cadastroSucesso = $_SESSION['cadastroSucesso'];
    unset($_SESSION['cadastroSucesso']);
}

if (isset($_SESSION['senhaIncorreta'])) {
    $senhaIncorreta = $_SESSION['senhaIncorreta'];
    unset($_SESSION['senhaIncorreta']);
}

if (isset($_SESSION['senhaIgualAntiga'])) {
    $senhaIgualAntiga = $_SESSION['senhaIgualAntiga'];
    unset($_SESSION['senhaIgualAntiga']);
}

if (isset($_SESSION['senhaJaExisteOutro'])) {
    $senhaJaExisteOutro = $_SESSION['senhaJaExisteOutro'];
    unset($_SESSION['senhaJaExisteOutro']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nova_senha = isset($_POST['nova_senha']) ? trim($_POST['nova_senha']) : '';
    $confirma_senha = isset($_POST['confirma_senha']) ? trim($_POST['confirma_senha']) : '';

    // Validação básica
    if (empty($email) || empty($nova_senha) || empty($confirma_senha)) {
        die("Por favor, preencha todos os campos.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Formato de e-mail inválido.");
    }

    if ($nova_senha !== $confirma_senha) {
        $_SESSION['senhaIncorreta'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Verifica se o e-mail existe
    $sql = "SELECT id, senha FROM produtor WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        die("Não foi possível redefinir a senha. Verifique os dados e tente novamente.");
    }

    // Obtem o ID e a senha antiga
    $stmt->bind_result($id_usuario_atual, $senha_antiga);
    $stmt->fetch();

    // Verifica se a nova senha é igual à antiga
    if (password_verify($nova_senha, $senha_antiga)) {
        $_SESSION['senhaIgualAntiga'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Verifica se a nova senha já existe em outro usuário
    $sql_check_senha = "SELECT id, senha FROM produtor WHERE id != ?";
    $stmt_check = $conn->prepare($sql_check_senha);
    if (!$stmt_check) {
        die("Erro ao preparar a verificação de senhas repetidas: " . $conn->error);
    }

    $stmt_check->bind_param("i", $id_usuario_atual);
    $stmt_check->execute();
    $stmt_check->store_result();
    $stmt_check->bind_result($id_outro, $senha_outro);

    while ($stmt_check->fetch()) {
        if (password_verify($nova_senha, $senha_outro)) {
            $_SESSION['senhaJaExisteOutro'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Hash da nova senha com bcrypt
    $hashed_password = password_hash($nova_senha, PASSWORD_BCRYPT);

    // Atualiza a senha
    $update_sql = "UPDATE produtor SET senha = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("Erro ao preparar a consulta de atualização: " . $conn->error);
    }

    $update_stmt->bind_param("ss", $hashed_password, $email);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        $_SESSION['cadastroSucesso'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        die("Erro ao redefinir a senha.");
    }

    // Fecha tudo
    $update_stmt->close();
    $stmt_check->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="esquecir_senha.css">
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
                      <li>  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                        </svg>
                    </a></li>
            </ul>
        </nav>
    </header>

    <div class="agenda-evento">
        <div class="conteudo">

            <section class="redefinir-section">

                <div class="esquecir-box">

                    <!-- Modal de Redefinição de Senha (oculto inicialmente) -->
                    <div id="modalSenha" class="modal">
                        <div class="modal-content">
                            <a href="index.php" class="btn-closs">&times;</a>
                            <h2> Redefinir Senha </h2>
                            <form action="" method="POST" onsubmit="return validarSenhas()">

                                <div class="input-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" required>
                                </div>

                                <div class="input-group">
                                    <label for="nova_senha">Nova Senha:</label>
                                    <input type="password" id="nova_senha" name="nova_senha" required>
                                </div>

                                <div class="input-group">
                                    <label for="confirma_senha">Confirme a senha:</label>
                                    <input type="password" name="confirma_senha" required>
                                </div>

                                <button type="submit" onclick="return validarSenhas()" class="redefinir-btn">Redefinir</button>
                                <a href="index.php"><button type="button" onclick="fecharModal()" class="Cancel-btn-redefinir">Cancelar</button></a>
                            </form>
                        </div>
                    </div>

                    <!-- Modal de Sucesso -->
                    <div id="modalSucesso" class="modal-correto">
                        <div class="modal-content-correto">
                            <span class="close-icon-correto" onclick="fecharModal()">&times;</span>
                            <h2>Senha Redefinida com Sucesso!</h2>
                            <img src="correto.png" class="correto-img">
                        </div>
                    </div>

                    <!-- Modal de Erro: Senhas não coincidem -->
                    <div id="modalSenhaErro" class="modal-erro">
                        <div class="modal-content-erro">
                            <span class="close-btn" onclick="fecharModalErro('modalSenhaErro')">&times;</span>
                            <h3>Erro de Validação!</h3>
                            <p>As senhas não coincidem.</p>
                        </div>
                    </div>

                    <!-- Modal de Erro: Senha igual à antiga -->
                    <div id="modalSenhaIgualAntiga" class="modal-erro">
                        <div class="modal-content-erro">
                            <span class="close-btn" onclick="fecharModalErro('modalSenhaIgualAntiga')">&times;</span>
                            <h3>Erro de Validação!</h3>
                            <p>A nova senha não pode ser igual à sua antiga senha.</p>
                        </div>
                    </div>

                    <!-- Modal de Erro: Senha já utilizada por outro usuário -->
                    <div id="modalSenhaJaExiste" class="modal-erro">
                        <div class="modal-content-erro">
                            <span class="close-btn" onclick="fecharModalErro('modalSenhaJaExiste')">&times;</span>
                            <h3>Erro de Validação!</h3>
                            <p>Essa senha já existe. tente outra senha.</p>
                        </div>
                    </div>
                    <script>
                        function fecharModal() {
                            document.getElementById("modalSucesso").style.display = "none";
                        }

                        function fecharModalErro(idModal) {
                            document.getElementById(idModal).style.display = "none";
                        }

                        function redirecionarParaLogin() {
                            window.location.href = "index.php";
                        }

                        <?php if ($cadastroSucesso) : ?>
                            document.getElementById("modalSucesso").style.display = "flex";
                            setTimeout(function() {
                                fecharModal();
                                redirecionarParaLogin();
                            }, 3000);
                        <?php endif; ?>

                        <?php if ($senhaIncorreta) : ?>
                            window.onload = function() {
                                document.getElementById("modalSenhaErro").style.display = "flex";
                            };
                        <?php endif; ?>

                        <?php if ($senhaIgualAntiga) : ?>
                            window.onload = function() {
                                document.getElementById("modalSenhaIgualAntiga").style.display = "flex";
                            };
                        <?php endif; ?>

                        <?php if ($senhaJaExisteOutro) : ?>
                            window.onload = function() {
                                document.getElementById("modalSenhaJaExiste").style.display = "flex";
                            };
                        <?php endif; ?>
                    </script>
                </div>
            </section>

        </div>

</body>

</html>
