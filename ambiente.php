<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

// Verificar se o login foi bem-sucedido
$showSuccessPopup = false;
if (isset($_SESSION['login_success'])) {
    $showSuccessPopup = true;
    unset($_SESSION['login_success']); // Remove a variável para evitar exibições futuras
}

 // Verificar se o usuário já está logado pela sessão
 if (isset($_SESSION['user_id'])) {
    // O usuário já está logado, redireciona para a página de sucesso
    header("Location: ambiente.php");
    exit();
}

    // Se o usuário já estiver logado através da sessão
    if (isset($_SESSION['user_id'])) {
        header("Location: ambiente.php");
        exit();
    }



$produtor_email = $_SESSION['email'];
$produtor_nome = $_SESSION['nome'];
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>SGE - Ambiente do Produtor</title>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
  />
    <link rel="stylesheet" href="ambiente.css">
   
    
</head>
<body>
      

    <header>
        
     <!-- Botão para abrir a sidebar -->
<button class="open-btn" onclick="abrirSidebar()">☰</button>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar">
    <span class="close-btn" onclick="fecharSidebar()">&times;</span> <!-- Botão "X" -->
    <h2 style="padding-left: 20px;">Menu</h2>
    &nbsp;
    <ul class="separator"> 
    <a href="lista de eventos.php">Eventos</a>
    &nbsp;
    <ul> <a href="lista de staff.php">Staff</a></ul>
    &nbsp;
    <ul> <a href="Cargo.php">Cargo</a></ul>
    &nbsp;
    <ul> <a href="tema lista.php">Tema</a></ul>
    &nbsp;
    <ul> <a href="lista de buffet.php">Buffet</a></ul>
    &nbsp;
    <ul><a href="lista de objetivos.php">Objetivos</a> </ul>   
    &nbsp;
    <a href="reporte de problemas.php">Reportar Problemas</a>
    &nbsp;

   <!-- Modal de confirmação -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Tem certeza que deseja se deslogar?</h2>
        <button class="btn-yes" onclick="confirmLogout()">Sim</button>
        <button class="btn-no" onclick="closeModal()">Não</button>
    </div>
</div>

<script>
    // Função para abrir o modal
    function openModal() {
        document.getElementById("logoutModal").style.display = "block";
    }

    // Função para fechar o modal
    function closeModal() {
        document.getElementById("logoutModal").style.display = "none";
    }

    // Função para confirmar o logout
    function confirmLogout() {
        window.location.href = "index.php"; // Redireciona para a página de logout
    }

    // Fecha o modal se o usuário clicar fora da área de conteúdo
    window.onclick = function(event) {
        const modal = document.getElementById("logoutModal");
        if (event.target === modal) {
            closeModal();
        }
    }
</script>



       
</div>


<!-- Pop-up de Login Bem-Sucedido -->
<div id="loginSuccessPopup" class="popup" style="display: none;">
    <div class="popup-content">
     <span class="close-btn-popup" onclick="closePopup()">&times;</span> <!-- Botão "X" -->
        <h2>Login Bem-Sucedido</h2>
        <img src="correto.png" alt="Bem-vindo" class="popup-image">
        <p class= "bem-vindo">Bem-vindo(a), <?php echo htmlspecialchars($produtor_nome); ?>!</p>
    </div>
</div>

<script>
    // Verifica se o pop-up deve ser exibido
    const showSuccessPopup = <?php echo json_encode($showSuccessPopup); ?>;
    if (showSuccessPopup) {
        const popup = document.getElementById('loginSuccessPopup');
        popup.style.display = 'flex'; // Exibe o pop-up

        // Define um tempo para o pop-up desaparecer automaticamente (ex.: 3 segundos)
        setTimeout(() => {
            popup.style.display = 'none'; // Esconde o pop-up
        }, 3000); // 3000ms = 3 segundos
    }

    // Função para fechar o pop-up manualmente (opcional)
    function closePopup() {
        document.getElementById('loginSuccessPopup').style.display = 'none';
    }
</script>


        <div class="logo-foto"> 
           <img src="SGE logo.png"width=80% height="100%">
           
       <div class="header-content"> 
      <h1> S.G.E.</h1> 
      <p> Sistema de Gestão de Eventos</p>
  
      </div>   
          </div>
  
          <div class="foto-container">
            <div class="logo">
              <img src="eventos.png"width=103% height="100%"> 
                 </div>
      
       </div>
      
   
          <nav> 
              
              <ul> 
                 
                  <li> <a href="ambiente.php"> Home</a></li>  
                  <li> <a href= "ajuda.php">Ajuda</a></li>
                  <li> <a href= "sobre.php">Sobre</a></li>
                  <li>  <a onclick="toggle()"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                  </svg> </a> 
             </ul>
            
            </div>

            
                </nav>

               
                    <ul class="profile-dropdown-list">
                    <p>Bem-vindo, <?php echo htmlspecialchars($produtor_nome); ?>!</p>
                      <li class="profile-dropdown-list-item">
                        <a href="listar produtores.php">
                          <i class="fa-regular fa-user"></i>
                          Gerenciar produtor
                        </a>
                      </li>
                      <li class="profile-dropdown-list-item">
                        <a href="perfil do produtor.php">
                          <i class="fa-regular fa-envelope"></i>
                           Editar Perfil
                        </a>
                      </li>
                      <li class="profile-dropdown-list-item">
                      <a href="logo out.php">
                          <i class="fa-solid fa-arrow-right-from-bracket"></i>
                          Sair
                        </a>
                      </li>

                      
                  
                    </ul>
                  </div>
                </nav>
              </li>
    
    </header>

    <section class="agenda-evento">
        
        <div class="conteudo">
          
           <div class="container"> 
               
          <h2>PRÓXIMOS EVENTOS</h2>
                                   
          <div class="eventos">

          
              
              <div class="evento">
  
                   
                  <h1>30/10/2024 <br>HALLOWEEN </h1>
                  <img src="Halloween eventos.jpg" alt="haloween">
                  <button onclick="showDetails()">Saiba Mais →</button>
                
              </div>
                        <!-- Modal de Confirmação -->
                        <div id="confirmModal" class="modal">
                          <div class="modalContent">
                              <h3>Deseja visualizar as informações do evento?</h3>
                              <button class="btn btn-sim" onclick="showDetails()">Sim</button>
                              <button class="btn btn-nao" onclick="closeConfirmBox()">Não</button>
                          </div>
                      </div>

                      <!-- Modal para exibir informações do evento -->
                      <div id="infoModal" class="modal">
                          <div class="modalContent">
                              <span class="msg-btn" onclick="closeDetails()">×</span>
                              <h2>Detalhes do Evento</h2>
                              <p><strong>Nome:</strong> Halloween</p>
                              <p><strong>Data:</strong> 31 de Outubro de 2024</p>
                              <p><strong>Horário:</strong> 13:00 - 18:00</p>
                              <p><strong>Local:</strong> FAETEC cvt Nilópolis, Paiol</p>
                              <p><strong>Descrição:</strong> Dia das bruxas</p>
                          </div>
                      </div>


  
              <div class="evento">
                  <h1>30/07/2024 <br>FESTA JUNINA</h1>
                  <img src="Festa junina.jpg" alt="Festa junina">
                  <button onclick="showDetails2()">Saiba Mais →</button>
  
                  </div>
  
                       <!-- Modal de Confirmação -->
             <div id="confirmModal 2" class="modal">
              <div class="modalContent">
                  <h3>Deseja visualizar as informações do evento?</h3>
                  <button class="btn btn-sim" onclick="showDetails2()">Sim</button>
                  <button class="btn btn-nao" onclick="closeConfirmBox2()">Não</button>
              </div>
          </div>
          
          <!-- Modal para exibir informações do evento -->
          <div id="infoModal 2" class="modal">
              <div class="modalContent">
                  <span class="msg-btn" onclick="closeDetails2()">×</span>
                  <h2>Detalhes do Evento</h2>
                  <p><strong>Nome:</strong> Festa Junina</p>
                  <p><strong>Data:</strong> 30 de Julho de 2024</p>
                  <p><strong>Horário:</strong> 14:00 - 17:00</p>
                  <p><strong>Local:</strong> FAETEC cvt Nilópolis, Paiol</p>
                  <p><strong>Descrição:</strong> Arraiá da FAETEC</p>
              </div>
          </div>


  
              <div class="evento">
                  
                   <h1> 12/10/2024 <br>DIA DAS CRIANÇAS</h1>
                   <img src="dia das crianças.jpg" alt="Dia das crianças">
                   <button onclick="showDetails3()">Saiba Mais →</button>

              </div>

              <!-- Modal de Confirmação -->
              <div id="confirmModal 3" class="modal">
                <div class="modalContent">
                    <h3>Deseja visualizar as informações do evento?</h3>
                    <button class="btn btn-sim" onclick="showDetails3()">Sim</button>
                    <button class="btn btn-nao" onclick="closeConfirmBox3()">Não</button>
                </div>
            </div>
            
            <!-- Modal para exibir informações do evento -->
            <div id="infoModal 3" class="modal">
                <div class="modalContent">
                    <span class="msg-btn" onclick="closeDetails3()">×</span>
                    <h2>Detalhes do Evento</h2>
                    <p><strong>Nome:</strong> Dia das Crianças</p>
                    <p><strong>Data:</strong> 12 de Outubro de 2024</p>
                    <p><strong>Horário:</strong> 12:00 - 18:00</p>
                    <p><strong>Local:</strong> FAETEC cvt Nilópolis, Paiol</p>
                    <p><strong>Descrição:</strong> Venha se divertir com a criançada</p>
                </div>
            </div>
  
  
          </div>
          
     </div>
      </div>

    <script src="Menu3.js"></script>
</body>
</html>
