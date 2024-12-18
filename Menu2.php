<?php
include ("php/Config.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    header("Location: Logar produtor.php");
    exit;
}

$email = $_SESSION['nome'];
?>
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Ambiente do Produtor</title>
    <link rel="stylesheet" href="Menu2.css">
   
    
</head>
<body>
      

    <header>
        
     <!-- Botão para abrir a sidebar -->
<button class="open-btn" onclick="abrirSidebar()">☰</button>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar">
    <span class="close-btn" onclick="fecharSidebar()">&times;</span> <!-- Botão "X" -->
    <h2 style="padding-left: 20px;">Menu</h2>
    <ul class="separator"> 
    <a href="evento.php">Eventos</a>
    <ul> <a href="Staff cadastro .php">Staff</a></ul>
    <ul> <a href="tema lista.php">Tema</a></ul>
    <ul> <a href="lista de buffet.php">Buffet</a></ul>
    <ul><a href="objetivo.php">Objetivos</a> </ul>   
    <a href="listar produtores.php">Produtor</a>
    <a href="reporte de problemas.php">Reportar Problemas</a>
    <a onclick="openModal()">Logout</a>

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
        window.location.href = "logout.php"; // Redireciona para a página de logout
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
                 
                  <li> <a href="index.html"> Home</a></li>
                  <li> <a href= "sobre.php">Sobre</a></li>
                  <li> <a href= "ajuda.php">Ajuda</a></li>
                  <li>  <a href="Menu2.html"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                  </svg> </a> </li>
                  <div class="profile-container">
                      
                    <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h1>


                  </div>

              </ul>
  
          </nav>
       
      
    </header>



      <section class="agenda">

  
           <div class="container"> 

        
  
          <h2>PRÓXIMOS EVENTOS</h2>
                                   
          <div class="eventos">
              
              <div class="evento">
  
                   
                  <h1>30/10/2024 <br>HALLOWEEN </h1>
                  <img src="Halloween eventos.jpg" alt="haloween">
                  <button onclick="showConfirmBox()">Saiba Mais →</button>
                
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
                  <button onclick="showConfirmBox2()">Saiba Mais →</button>
  
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
                   <button onclick="showConfirmBox3()">Saiba Mais →</button>

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