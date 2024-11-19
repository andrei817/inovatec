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

                // Lembrar o produtor (cookies)
                if (isset($_POST['rememberMe'])) {
                    setcookie('id', $produtor['id'], time() + (86400 * 30), "/", "", false, true);
                    setcookie('email', $produtor['email'], time() + (86400 * 30), "/", "", false, true);
                }

                // Redirecionar para o painel do produtor
                header('Location: ambiente.php');
                exit;
            } else {
                echo "Senha incorreta";
            }
        } else {
            echo "Produtor não encontrado";
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
                <li> <a href="Sobre 1.php"> Sobre </a></li>
                <li> <a href= "ajuda 1.php">Ajuda</a></li>
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

             <section class="Sobre"> 

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