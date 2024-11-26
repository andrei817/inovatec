<?php
session_start();

include("php/Config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['senha'])) {
    if (!empty($_POST['email']) && !empty($_POST['senha'])) {
        // Capturar os dados do formulário
        $email = $_POST['email'];
        $senha = md5($_POST['senha']); // Criptografar a senha com MD5

        // Preparar a consulta SQL para a tabela de produtores de eventos
        $sql = "SELECT * FROM produtor WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        // Verificar se o produtor existe
        if ($result && mysqli_num_rows($result) > 0) {
            $produtor = mysqli_fetch_assoc($result);

            // Verificar a senha
            if ($produtor['senha'] === $senha) {
                // Criar sessão para o produtor
                $_SESSION['id'] = $produtor['id'];
                $_SESSION['email'] = $produtor['email'];
                $_SESSION['nome'] = $produtor['nome']; // Exemplo: armazenar o nome do produtor

                if (isset($_POST['rememberMe'])) {
                    // Gerar um token único para identificar o usuário
                    $token = bin2hex(random_bytes(16)); // Token seguro e único
                
                    // Salvar o token no banco de dados, associado ao ID do usuário
                    $userId = $produtor['id'];
                    $updateTokenSql = "UPDATE produtor SET remember_token = '$token' WHERE id = $userId";
                    mysqli_query($conn, $updateTokenSql);
                
                    // Configurar cookies seguros
                    setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true); // HTTP-Only
                }

                // Exibir o modal de sucesso
                $_SESSION['login_success'] = true;

                // Redirecionar para o painel do produtor
                header('Location: ambiente.php');
                exit;
            } else {
                // Senha incorreta - configurar a sessão para mostrar o modal
                $_SESSION['senha_incorreta'] = true;
            }
        } else {
             // Adicionar sessão para mostrar o pop-up de produtor não encontrado
           $_SESSION['produtor_nao_encontrado'] = true;
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
} else {
   // echo "Acesso inválido.";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Sobre nós</title>
    <link rel="stylesheet" href="sobre.css">
    
</head>
<body>
    <header>

       
        <div class="logo-foto"> 
            <img src="SGE logo.png"width=80% height="100%">
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
               
                <li>  <a href="index.php"> Home</a></li>
                <li> <a href= "ajuda 1.php">Ajuda</a></li>
                <li> <a href="Sobre 1.php"> Sobre </a></li>
                 <li> <a onclick="abrirPopUp()"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                  </svg> </a></li>

<!-- Overlay e Popup -->
<div id="overlay">
    <div id="popup">
        <p class="p">Área restrita ao produtor, deseja continuar?</p>
        <button class="btn-sim" onclick="openModal()">Sim</button>
        <button class="btn-nao" onclick="fecharPopUp()">Não</button>
    </div>
</div>

<!-- Modal de Login -->
<div id="loginModal" class="modal-login" style="display:none;">
    <section class="login-section-modal">
        <div class="login-box-modal"> 
            <h2> Fazer Login</h2>
            <a href="index.php" class="btn-close-login">&times;</a>
            <form action="" method="post">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="senha">Senha:</label><br>
                    <input type="password" id="senha" name="senha" required>
                </div> 
                <button type="submit" class="login-btn">Entrar</button>
                <a href="index.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
                <div class="circular-checkbox-wrapper">
                    <input type="checkbox" id="circular-checkbox" style="display: none;">
                    <label for="rememberMe">
                   <input type="checkbox" name="rememberMe" id="rememberMe"> Manter-me Conectado
                  </label>
                </div>

                <p><a href="esqueci_senha.php">Esqueceu sua senha?</a></p>
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
        document.getElementById("loginModal").style.display = "block";
    }

    // Função para fechar o modal de login
    function fecharModal() {
        document.getElementById("loginModal").style.display = "none";
        document.getElementById("senhaIncorretaModal").style.display = "none";
        document.getElementById("produtorNaoEncontradoModal").style.display = "none";
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
        
          
                    <!-- Botão de login
                      <button class="open-modal-btn" onclick="openModal()">Login</button>
              -->
                      <!-- Overlay -->
                      <div id="overlay2" class="overlay2" onclick="closeModal()"></div>

                      <!-- Modal de Login -->
                      <div id="loginModal" class="modal-login">

                            <section class="login-section-modal"> 
                              
                          
                              <div class="login-box-modal"> 

                                  <h2> Fazer Login</h2>
                              
                                  <a href="sobre 1.php" class="btn-close-login">&times;</a>
                                  <form action="" method="post">
                                  
                                  <div class="input-group">
                                  <label for="email">Email:</label>
                                  <input type="email" id="email" name="email" class="inputUser" required>
                                  </div> 

                                  <div class="input-group">
                                  <label for="senha">Senha:</label><br>
                                  <input type="password" id="senha" name="senha" required>
                                  </div> 
                                      <button type="submit" class="login-btn">Entrar</button>
                                      <a href="index.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
                                  
                                      <!-- Checkbox para "Manter-me conectado" -->
                              <label class="remember">
                                  <input type="checkbox" name="remember" id="circular-checkbox"> Manter-me conectado
                              </label>

                              <p><a href="esqueci_senha.php">Esqueceu sua senha?</a></p>

                                  </form>

                          <script>
                              function closeLogin() {
                                  
                                  document.querySelector('.login-container').style.display = 'none';
                              }
                          
                          </script>

                      </div>

                      <script>
                      // Função para abrir o modal e exibir o overlay

                      function abrirPopUp() {
                          
                          document.getElementById("overlay").style.display = "flex";
                         

                      }

                      function fecharPopUp() {
                          document.getElementById("overlay").style.display = "none";
                      }


                      function openModal() {
                          fecharPopUp();

                          document.getElementById("loginModal").style.display = "block";
                          document.getElementById("overlay2").style.display = "block";
                      }

                      // Função para fechar o modal e ocultar o overlay
                      function closeModal() {
                          
                          document.getElementById("loginModal").style.display = "none";
                          document.getElementById("overlay2").style.display = "none";
                      }

                      </script>



        

            </ul>
        </nav>
          
    </header>

    <div class="agenda-evento">
    <div class="conteudo"> 

        <div class="container">

            <div class="btn"> 
            <a href="index.php" class="close-btn-sobre">&times;</a>
               </div>

            <h3>Sobre nós</h3>
            <p class="text"> Somos um grupo de estudantes comprometidos com a inovação e a tecnologia,
                <br> e estamos desenvolvendo um Sistema de Gestão de Eventos voltado para 
                <br>facilitar a organização e o gerenciamento de eventos educacionais e tecnológicos.<br>
                <br>  Nosso projeto está diretamente relacionado a nossa instituição FAETEC,
                <br>O nome do nosso grupo é Inovatec que é formado pelos integrantes:
                <br>Cauã Felipe,
                    Érica Souza,
                    Nicolle vitória,
                     Andrei Luiz.
                Estamos empenhados em
                <br> criar uma ferramenta que ajude organizadores e participantes a terem uma 
                <br>experiência mais fluida e organizada durante o evento.</p>
          
            <div class="foto"> 
                <img src="sobre (3).jpeg">
             </div>
             
            
           </section>
    </body>
    
    </html>
   
    <script src="evento.js"> </script>
</body>
</html>
