<?php
session_start();
include('php/Config.php');

// Inicializa variáveis de controle
$cadastroSucesso = false;
$erroCadastro = false;
$senhaIncorreta = false;
$senhaJaExisteOutro = false;
$tipoErro = null;
$mensagemErro = "";

// Carrega status da sessão e limpa imediatamente
if (isset($_SESSION['cadastroSucesso'])) {
    $cadastroSucesso = $_SESSION['cadastroSucesso'];
    unset($_SESSION['cadastroSucesso']);
}
if (isset($_SESSION['erroCadastro'])) {
    $erroCadastro = $_SESSION['erroCadastro'];
    unset($_SESSION['erroCadastro']);
}
if (isset($_SESSION['tipoErro'])) {
    $tipoErro = $_SESSION['tipoErro'];
    unset($_SESSION['tipoErro']);
}
if (isset($_SESSION['mensagemErro'])) {
    $mensagemErro = $_SESSION['mensagemErro'];
    unset($_SESSION['mensagemErro']);
}
if (isset($_SESSION['senhaIncorreta'])) {
    $senhaIncorreta = $_SESSION['senhaIncorreta'];
    unset($_SESSION['senhaIncorreta']);
}
if (isset($_SESSION['senhaJaExisteOutro'])) {
    $senhaJaExisteOutro = true;
    unset($_SESSION['senhaJaExisteOutro']);
}

// Processa o formulário apenas se for POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace("/[^0-9]/", "", $_POST['telefone']); // Remove caracteres não numéricos
    $cpf = preg_replace("/[^0-9]/", "", $_POST['cpf']);
    $pergunta_seg = $_POST['pergunta_seg'];
    $resposta_seg = $_POST['resposta_seg'];
    $nova_senha = $_POST['senha'];
    $confirma_senha = $_POST['confirmPassword'];

    // Valida as senhas
    if ($nova_senha !== $confirma_senha) {
        $_SESSION['senhaIncorreta'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Valida o CPF (11 dígitos)
    if (!preg_match("/^\d{11}$/", $cpf)) {
        $_SESSION['erroCadastro'] = true;
        $_SESSION['tipoErro'] = 'cpf';
        $_SESSION['mensagemErro'] = 'Formato de CPF inválido. Use o formato: 123.456.789-09';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Telefone inválido
    if (!preg_match("/^\d{11}$/", $telefone)) {
        $_SESSION['erroCadastro'] = true;
        $_SESSION['tipoErro'] = 'telefone';
        $_SESSION['mensagemErro'] = 'Telefone inválido. Use o formato: (21) 98765-4321';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Conecta ao banco
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Verifica duplicidade de email ou CPF
    $sqlCheck = "SELECT * FROM produtor WHERE email = ? OR cpf = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ss", $email, $cpf);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $_SESSION['erroCadastro'] = true;
        $_SESSION['tipoErro'] = 'duplicado';
        $_SESSION['mensagemErro'] = 'E-mail ou CPF já cadastrado.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Verifica se o telefone já está cadastrado
    $sqlCheckTelefone = "SELECT id FROM produtor WHERE telefone = ?";
    $stmtCheckTelefone = $conn->prepare($sqlCheckTelefone);
    $stmtCheckTelefone->bind_param("s", $telefone);
    $stmtCheckTelefone->execute();
    $resultTelefone = $stmtCheckTelefone->get_result();

    if ($resultTelefone->num_rows > 0) {
        $_SESSION['erroCadastro'] = true;
        $_SESSION['tipoErro'] = 'telefone_duplicado';
        $_SESSION['mensagemErro'] = 'Este número de telefone já está sendo usado por outro produtor. Por favor, tente outro número.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Verifica se a senha já existe em outro produtor
    $sql_check_senha = "SELECT id, senha FROM produtor";
    $stmt_check_senha = $conn->prepare($sql_check_senha);
    $stmt_check_senha->execute();
    $result_check_senha = $stmt_check_senha->get_result();

    $senhaDuplicada = false;
    while ($produtorExistente = $result_check_senha->fetch_assoc()) {
        if (password_verify($nova_senha, $produtorExistente['senha'])) {
            $senhaDuplicada = true;
            break;
        }
    }

    if ($senhaDuplicada) {
        $_SESSION['senhaJaExisteOutro'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Criptografa a senha
    $senha = password_hash($nova_senha, PASSWORD_BCRYPT);

    // Insere no banco
    $sql = "INSERT INTO produtor (nome, email, telefone, senha, cpf, pergunta_seg, resposta_seg) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nome, $email, $telefone, $senha, $cpf, $pergunta_seg, $resposta_seg);

    if ($stmt->execute()) {
        $_SESSION['cadastroSucesso'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $stmtCheck->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Cadastro do Produtor</title>
    <link rel="stylesheet" href="cadastro produtor.css">

</head>

<body>

    <div id="header"></div> <!-- Div onde o menu será injetado -->

    <script>
        fetch('/menu principal.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header').innerHTML = data;
            })
            .catch(error => console.error('Erro ao carregar o menu:', error));
    </script>

    <div class="content">
        <!-- Conteúdo da página -->
    </div>

    <script>
        function abrirSidebar() {
            if (window.innerWidth <= 768) {
                document.getElementById("mySidebar").style.width = "100%";
            } else {
                document.getElementById("mySidebar").style.width = "310px";
            }
            // Adiciona a classe "aberto" à sidebar
            document.getElementById("mySidebar").classList.add("aberto");
        }

        // Função para fechar a sidebar
        function fecharSidebar() {
            document.getElementById("mySidebar").style.width = "0";
            // Remove a classe "aberto"
            document.getElementById("mySidebar").classList.remove("aberto");
        }

        // Adiciona o evento para fechar ao clicar fora da sidebar
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById("mySidebar");
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnButton = event.target.closest('.open-btn');

            // Fecha a sidebar se o clique não for nela nem no botão de abrir
            if (!isClickInsideSidebar && !isClickOnButton && sidebar.classList.contains("aberto")) {
                fecharSidebar();
            }
        });

        // Fecha a sidebar ao clicar nos links
        document.querySelectorAll('#mySidebar a').forEach(link => {
            link.addEventListener('click', fecharSidebar);
        });
    </script>


    <script>
        // Função para mostrar/ocultar a lista suspensa do perfil
        function toggle() {
            var profileDropdownList = document.querySelector('.profile-dropdown-list');
            profileDropdownList.classList.toggle('active');
        }

        // Função para mostrar o modal de logout
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        // Função para fechar qualquer modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Função para confirmar o logout e mostrar o modal de agradecimento
        function confirmLogout() {
            closeModal('logoutModal'); // Fecha o modal de logout
            document.getElementById('thankYouModal').style.display = 'flex'; // Mostra o modal de agradecimento

            // Redireciona após alguns segundos (opcional)
            setTimeout(function() {
                window.location.href = 'index.php'; // Redireciona para a página inicial
            }, 2000); // Aguarda 2 segundos antes de redirecionar
        }
    </script>



    <div class="agenda-evento">
        <div class="conteudo">

            <section class="login-section">

                <div class="login-box">

                    <h1> Cadastro do Produtor</h1>

                    <a href="listar produtores.php" class="fecha-btn">&times;</a>
                    <form action="" method="post">
                        <form id="formCadastro" onsubmit="return cadastrarConta()">

                            <div class="form-row">
                                <div class="input-group-prod">
                                    <label for="nome">Nome:</label>
                                    <input type="text" id="nome" name="nome" required>
                                </div>

                                <div class="input-group-prod">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" required>
                                </div>

                                <div class="input-group-prod">
                                    <label for="telefone">Telefone:</label>
                                    <input type="tel" id="telefone" name="telefone">
                                </div>

                                <p id="error-message" style="color: red; display: none;">Formato inválido.</p>

                                <div class="input-group-prod">
                                    <label for="cpf">CPF:</label>
                                    <input type="text" id="cpf" name="cpf">
                                </div>

                                <div class="input-group-prod">
                                    <label for="senha">Senha:</label>
                                    <input type="password" id="senha" name="senha">
                                </div>

                                <div class="input-group-prod">
                                    <label for="confirmPassword">Confirmar Senha:</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                                </div>

                                <div class="input-group-prod">
                                    <label for="pergunta_seg">Pergunta</label>
                                    <input type="text" name="pergunta_seg" required>
                                </div>

                                <div class="input-group-prod">
                                    <label for="resposta_seg">Resposta</label>
                                    <input type="text" name="resposta_seg" required>
                                </div>

                            </div>

                            <button type="submit" class="login-btn">Cadastrar</button>
                            <a href="listar produtores.php"><button type="button" class="Cancel-btn">Cancelar</button></a>

                        </form>

                        <!-- Modal de Sucesso -->
                        <div id="modalSucesso" class="modal-correto">
                            <div class="modal-content-correto">
                                <span class="close-icon-correto" onclick="fecharModal()">&times;</span>
                                <h2>Produtor Cadastrado com Sucesso!</h2>
                                <img src="correto.png" class="correto-img">

                            </div>
                        </div>

                        <!-- Modal de Erro -->
                        <div id="modalErro" class="modal-incorreto">
                            <div class="modal-content-incorreto">
                                <span class="close-icon-incorreto" onclick="fecharModalErro()">&times;</span>
                                <h2>Erro ao Cadastrar!</h2>
                                <p>E-mail ou CPF já está sendo usado por outro produtor..</p>
                            </div>
                        </div>

                        <!-- Modal de Erro: Senhas não coincidem -->
                        <div id="modalSenhaErro" class="modal-senha-erro">
                            <div class="modal-content-senha-erro">
                                <span class="close-icon-senha-erro" onclick="fecharModalSenha()">&times;</span>
                                <h3>Erro de Validação!</h3>
                                <p>As senhas não coincidem.</p>
                            </div>
                        </div>

                        <!-- Modais -->
                        <div id="modalSenhaJaExiste" class="modal-senha-erro">
                            <div class="modal-content-senha-erro">
                                <span class="close-btn" onclick="fecharModalSenhaJaExiste()">&times;</span>
                                <h3>Erro de Validação!</h3>
                                <p>Essa senha já está sendo utilizada por outro produtor. tente outra senha.</p>
                            </div>
                        </div>

                        <!-- Modal de Erro de Validação (CPF/Telefone) -->
                        <div id="modalErroValidacao" class="modal-senha-erro">
                            <div class="modal-content-senha-erro">
                                <span class="close-btn" onclick="fecharModalErroValidacao()">&times;</span>
                                <h3>Erro de Validação!</h3>
                                <p id="mensagemErroValidacao"></p>
                            </div>
                        </div>



                        <script>
                            function validarSenhas() {
                                const senha = document.getElementById("senha").value.trim();
                                const confirmPassword = document.getElementById("confirmPassword").value.trim();
                                const errorMsg = document.getElementById("error-message");

                                if (senha !== confirmPassword) {
                                    errorMsg.style.display = "block";
                                    return false;
                                }

                                errorMsg.style.display = "none";
                                return true;
                            }

                            // Mostra os modais conforme variáveis de sessão
                            <?php if ($cadastroSucesso) : ?>
                                document.getElementById("modalSucesso").style.display = "flex";
                            <?php endif; ?>

                            <?php if ($senhaIncorreta) : ?>
                                document.getElementById("modalSenhaErro").style.display = "flex";
                            <?php endif; ?>

                            <?php if ($senhaJaExisteOutro) : ?>
                                document.getElementById("modalSenhaJaExiste").style.display = "flex";
                            <?php endif; ?>

                            <?php if ($erroCadastro) : ?>
                                <?php if ($tipoErro === 'duplicado') : ?>
                                    document.getElementById("modalErro").style.display = "flex";
                                <?php else : ?>
                                    const mensagem = <?php echo json_encode($mensagemErro); ?>;
                                    document.getElementById("mensagemErroValidacao").textContent = mensagem;
                                    document.getElementById("modalErroValidacao").style.display = "flex";
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($erroCadastro && $tipoErro === 'telefone_duplicado'): ?>
                                document.getElementById("modalTelefoneDuplicado").style.display = "flex";
                            <?php endif; ?>

                            // Funções para fechar os modais
                            function fecharModal() {
                                document.getElementById("modalSucesso").style.display = "none";
                            }

                            function fecharModalErro() {
                                document.getElementById("modalErro").style.display = "none";
                            }

                            function fecharModalSenha() {
                                document.getElementById("modalSenhaErro").style.display = "none";
                            }

                            function fecharModalSenhaJaExiste() {
                                document.getElementById("modalSenhaJaExiste").style.display = "none";
                            }

                            function validarFormulario() {
                                const cpfInput = document.getElementById("cpf");
                                const telefoneInput = document.getElementById("telefone");
                                const modal = document.getElementById("modalErroValidacao");
                                const mensagem = document.getElementById("mensagemErroValidacao");

                                const cpf = cpfInput.value;
                                const telefone = telefoneInput.value;


                                if (cpf.length !== 11) {
                                    mensagem.textContent = "CPF inválido! Ele deve conter exatamente 11 dígitos.";
                                    modal.style.display = "flex";
                                    return false;
                                }

                                if (telefone.length !== 11) {
                                    mensagem.textContent = "Telefone inválido! Ele deve conter exatamente 11 dígitos.";
                                    modal.style.display = "flex";
                                    return false;
                                }

                                return true;
                            }

                            function fecharModalErroValidacao() {
                                document.getElementById("modalErroValidacao").style.display = "none";
                            }
                        </script>

                        <script src="login.js"> </script>

                        <!-- jQuery e jQuery Mask-->
                        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

                        
                        <script>
                            $(document).ready(function () {
                                $('#cpf').mask('000.000.000-00', { reverse: true });
                                $('#telefone').mask('(00) 00000-0000');
                            });
                        </script>


</body>

</html>
