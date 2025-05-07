<?php
include("php/Config.php");

$erroResposta = false; // Variável para controle do modal
$pergunta = ''; // Variável para armazenar a pergunta de segurança
$emailNaoEncontrado = false; // Variável para controle do erro de e-mail não encontrado

// Etapa 1: Validação da pergunta e resposta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verificar_resposta'])) {
    $email = trim($_POST['email']);
    $resposta_usuario = trim($_POST['resposta_seg']);
    $pergunta_usuario = trim($_POST['pergunta_seg']);  // Resposta para a pergunta de segurança

    // Validar campos
    if (empty($email) || empty($resposta_usuario) || empty($pergunta_usuario)) {
        die("Por favor, preencha todos os campos.");
    }

    // Validar formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Formato de e-mail inválido.");
    }

    // Conexão com o banco de dados
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Buscar pergunta e resposta no banco
    $sql = "SELECT pergunta_seg, resposta_seg FROM produtor WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $emailNaoEncontrado = true;  // Define o erro de e-mail não encontrado
    } else {
        $stmt->bind_result($pergunta, $resposta_cadastrada);
        $stmt->fetch();

        // Comparar respostas
        if (strtolower($resposta_usuario) === strtolower($resposta_cadastrada) && strtolower($pergunta_usuario) === strtolower($pergunta)) {
            session_start();
            $_SESSION['email_validado'] = $email;
            header("Location: esqueci_senha.php");
            exit();
        } else {
            $erroResposta = true; // Ativa o modal de erro
        }
    }

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
    <link rel="stylesheet" href="validar resposta.css">
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

    <div class="agenda-evento">
        <div class="conteudo">

            <section class="redefinir-section">

                <div class="esquecir-box">

                    <!-- Modal de Pergunta -->
                    <div id="modalPergunta" class="modal-pergunta" style="display: block;">
                        <div class="modal-content-pergunta">

                            <h3> Recuperar Senha </h3>

                            <form action="" method="POST">

                                <a href="index.php" class="fecha-btn">&times;</a>

                                <div class="input-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" required>
                                </div>

                                <div class="input-group">
                                    <label for="pergunta_seg">Digite a Pergunta:</label>

                                    <input type="text" name="pergunta_seg" required>
                                </div>


                                <div class="input-group">
                                    <label for="resposta_seg">Digite a Resposta:</label>
                                    <input type="text" name="resposta_seg" required>
                                </div>

                                <button type="submit" name="verificar_resposta" class="btn-pergunta">Verificar</button>
                                <a href="index.php"><button type="button" class="Cancel-btn-pergunta">Cancelar</button></a>
                            </form>

                            <div id="modalRespostaIncorreta" class="modal-erro" style="display: none;">
                                <div class="modal-content-erro">
                                    <span class="close-btn" onclick="fecharModalErro('modalRespostaIncorreta')">&times;</span>
                                    <h3>Resposta Incorreta!</h3>
                                    <p>Por favor, tente novamente.</p>
                                </div>
                            </div>



                            <script>
                                // Verifica se houve erro na resposta
                                <?php if ($erroResposta) : ?>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        exibirModalErro('modalRespostaIncorreta'); // Exibe o modal de erro
                                    });
                                <?php endif; ?>

                                // Função para exibir o modal de erro
                                function exibirModalErro(idModal) {
                                    document.getElementById(idModal).style.display = "flex";
                                }

                                // Função para fechar o modal de erro
                                function fecharModalErro(idModal) {
                                    document.getElementById(idModal).style.display = "none";
                                }
                            </script>


                            <!-- Modal de erro: E-mail não encontrado -->
                            <div id="modalNotFound" class="modal-not-found" style="display: none;">
                                <div class="modal-content-not-found">
                                    <span class="close-btn-not-found" onclick="closeModal()">&times;</span>
                                    <h1>E-mail não encontrado</h1>
                                    <p>O e-mail informado não foi encontrado no sistema. Por favor, verifique e tente novamente.</p>
                                </div>
                            </div>


                            <!-- Script para abrir e fechar o modal -->
                            <script>
                                // Função para abrir o modal
                                function openModal() {
                                    document.getElementById("modalErro").style.display = "block";
                                }

                                // Função para fechar o modal
                                function closeModal() {
                                    document.getElementById("modalErro").style.display = "none";
                                }
                            </script>

                            <script>
                                // Função para abrir o modal
                                function openModal() {
                                    document.getElementById("modalNotFound").style.display = "block";
                                }

                                // Função para fechar o modal
                                function closeModal() {
                                    document.getElementById("modalNotFound").style.display = "none";
                                }
                            </script>

                            <!-- Se o e-mail não for encontrado, abre o modal -->
                            <?php if ($emailNaoEncontrado) : ?>
                                <script>
                                    openModal(); // Abre o modal de erro de e-mail não encontrado
                                </script>
                            <?php endif; ?>

                        </div>
            </section>
</body>

</html>