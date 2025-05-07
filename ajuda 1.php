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
    <title>SGE - Ajuda</title>
    <link rel="stylesheet" href="ajuda.css">
    
</head>
<body>
    <header>

      <div class="logo-foto"> 
        <img src="Logo_SGE_inova.png"width=80% height="100%">
    <div class="header-content"> 
   <h1> S.G.E.</h1> 
   <p> Sistema de Gestão de Eventos</p>

   </div>   
       </div>

   

<div class="logo">

    <img src="eventos.png"width=103% height="100%">
   
   </div>
        <nav>
            <ul>   
        <li><a href="index.php" title="Página inicial">Home</a></li>  
        <li><a href="ajuda 1.php" title="Obtenha ajuda">Ajuda</a></li>
        <li><a href="Sobre 1.php" title="Sobre nós">Sobre</a></li>
        <a onclick="abrirPopUp()" title="Área Restrita">
      <li> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
    </svg>
</a></li>

<!-- Overlay para o Popup -->
<div id="overlay">
    <div id="popup">
        <p class="text">Área restrita ao produtor, deseja continuar? </p>
        <button class="btn-sim" onclick="openModal()">Sim</button>
        <button class="btn-nao" onclick="fecharPopUp()">Não</button>
    </div>
</div>



<!-- Modal de Login -->
<div id="loginModal" class="modal-login" style="display:none;">
    <section class="login-section-modal">
        <div class="login-box-modal"> 
            <h2> Fazer Login</h2>
            <a href="ajuda 1.php" class="btn-close-login">&times;</a>
            <form action="" method="post">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($emailCookie); ?>" required>
                </div>
                <div class="input-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div> 
                <button type="submit" class="login-btn">Entrar</button>
                <a href="ajuda 1.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
                <div class="circular-checkbox-wrapper">
                    <input type="checkbox" id="circular-checkbox" style="display: none;">
                    <label for="rememberMe">
                        <input type="checkbox" name="rememberMe" id="rememberMe" class="circular-checkbox"
                        <?php echo isset($_COOKIE['rememberMe']) ? 'checked' : ''; ?>
                        > Manter-me Conectado
                    </label>
                </div>

                <a href="esqueci_senha.php">Esqueceu sua senha?</a>
            </form>
        </div>
    </section>
</div>

<!-- Modal de erro de senha -->
<div id="senhaIncorretaModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn-popup" onclick="fecharModal()">&times;</span>
        <h2>Erro!</h2>
        <p>Senha incorreta. Tente novamente.</p>
        <img src="erro login.png" alt="Erro">
    </div>
</div>

<!-- Modal de Produtor Não Encontrado -->
<div id="produtorNaoEncontradoModal" class="modal">
    <div class="modal-content">
        <span class="close-btn-popup" onclick="fecharModal('produtorNaoEncontradoModal')">&times;</span>
        <h2>Erro!</h2>
        <p>Produtor não encontrado. Verifique o email informado e tente novamente.</p>
        <img src="email nao encontrado.png" alt="Erro">
    </div>
</div>


<script> 
    // Função para abrir o pop-up
    function abrirPopUp() {
        document.getElementById("overlay").style.display = "flex";
    }

    // Função para fechar o pop-up
    function fecharPopUp() {
        document.getElementById("overlay").style.display = "none";
    }

    // Função para abrir o modal de login
    function openModal() {
        fecharPopUp();
        document.getElementById("overlay").style.display = "flex";
        document.getElementById("loginModal").style.display = "block";
    }

    // Função para fechar o modal de login
    function fecharModal() {
        document.getElementById("senhaIncorretaModal").style.display = "none";
        document.getElementById("produtorNaoEncontradoModal").style.display = "none";         
        document.getElementById("loginModal").style.display = "block";
    }


    // Exibir o modal de erro de senha se a variável PHP for verdadeira
    <?php if (isset($_SESSION['senha_incorreta']) && $_SESSION['senha_incorreta'] === true): ?>
        document.getElementById("senhaIncorretaModal").style.display = "block";
        <?php unset($_SESSION['senha_incorreta']); ?>
    <?php endif; ?>

     // Exibir o modal de sucesso se o login foi bem-sucedido
     <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true): ?>
        document.getElementById("modalSucesso").style.display = "block";
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>

    // Exibir o modal de produtor não encontrado
    <?php if (isset($_SESSION['produtor_nao_encontrado']) && $_SESSION['produtor_nao_encontrado'] === true): ?>
        document.getElementById("produtorNaoEncontradoModal").style.display = "block";
    <?php unset($_SESSION['produtor_nao_encontrado']); ?>
   <?php endif; ?>

    // Fechar o modal quando o usuário clicar fora dele
    window.onclick = function(event) {
        if (event.target == document.getElementById("senhaIncorretaModal")) {
            fecharModal();
        }

    }

       // Fechar o modal quando o usuário clicar fora dele
       window.onclick = function(event) {
        if (event.target == document.getElementById("produtorNaoEncontradoModal")) {
            fecharModal();
        }

    }
    
</script>


            </ul>
        </nav>
         
    </header>
  
    <div class="agenda-evento">
    <div class="conteudo">
       
        <a href="index.php" class="close-btn-ajuda">&times;</a>
        <div class="help-container">

        <div class="ajuda-title">
            <h1 class="Ajuda"> Ajuda</h1>
            <p> Olá Seja Bem Vindo ao S.G.E. Aqui a baixo estão as opções de ajuda caso você precise compreender melhor nosso site.</p>
        </div>
            <div class="help-section">
              <button class="help-title" onclick="toggleHelp(this)">Agenda</button>
              <div class="help-content">
                <p>Centralizado em sua tela é nossa agenda de eventos, rolando para os lados você visualiza outros eventos disponíveis<br> e ao clicar no "Saiba Mais", você terá mais informações sobre o evento escolhido.</p>
              </div>
            </div>
        
            <div class="help-section">
              <button class="help-title" onclick="toggleHelp(this)">Sobre</button>
              <div class="help-content">
                <p>Na tela de sobre você terá informações sobre que somos e sobre nosso site.</p>
                
              </div>
            </div>
        
            <div class="help-section">
              <button class="help-title" onclick="toggleHelp(this)"> Acessar</button>
              <div class="help-content">
                <p>Esta opção está indisponível, pois está limitada apenas aos produtores do site.</p>
              </div>
            </div>

            <div class="help-section">
              <button class="help-title" onclick="toggleHelp(this)"> Como funciona o carrossel de eventos?</button>
              <div class="help-content">
                <p>No carrossel automático os eventos centralizados serão os próximos e ao usuário clicar nas setas indicadoras serão exibidos todos os outros eventos.</p>
              </div>
            </div>

            <div class="help-section">
              <button class="help-title" onclick="toggleHelp(this)"> Como posso visualizar detalhes dos eventos?</button>
              <div class="help-content">
                <p>Abaixo dos eventos que aparecem na tela inicial possui a opção de "Saiba Mais" nessa opção clicável você terá informações detalhadas desse evento.</p>
              </div>
            </div>

        </div>
          </div> 
   </section>

    <script src="ajuda.js"> </script>
</body>
</html>