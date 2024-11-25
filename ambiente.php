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

<?php


// Verifica se o parâmetro 'logout' foi passado na URL
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Destrua a sessão (fazendo logout)
    session_unset(); // Limpa todas as variáveis de sessão
    session_destroy(); // Destroi a sessão

    // Redireciona para a página de login ou homepage
    header("Location: index.php"); // Altere para a página de login ou onde desejar redirecionar
    exit();
}
// Sinaliza que o modal deve ser exibido
$showModal = true;
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
    <a href="colaboradores.php">Colaboradores</a>
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
    <a href="relatório de problemas.php">Reportar Problemas</a>
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
                      <a onclick="showLogoutModal()">
                          <i class="fa-solid fa-arrow-right-from-bracket"></i>
                          Sair
                        </a>
                      </li>

                      <!-- Modal de Logout -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Deseja se deslogar?</h2>
        <button class="btn btn-yes" onclick="confirmLogout()">Sim</button>
        <button class="btn btn-no" onclick="closeModal()">Não</button>
    </div>
</div>

<script>
    // Função para mostrar o modal de logout
    function showLogoutModal() {
        document.getElementById('logoutModal').style.display = 'block';
    }

    // Função para fechar o modal
    function closeModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    // Função para confirmar o logout
    function confirmLogout() {
        closeModal(); // Fecha o modal
        alert('Obrigado por usar o nosso sistema!!!'); // Mostra a mensagem de agradecimento
        window.location.href = 'index.php'; // Redireciona para o script PHP (ambiente.php) com o parâmetro logout
    }
</script>

                      
                  
                    </ul>
                  </div>
                </nav>
              </li>
    
    </header>

    <section class="agenda-evento">
        
        <div class="conteudo">
          
           <div class="container"> 
               
          <h2>PRÓXIMOS EVENTOS</h2>

          
       <?php
include('php/Config.php');

// Função para exibir todos os eventos
function exibirEventos() {
    global $conn;

    // Consulta para buscar todos os eventos, ordenados pela data
    $sql_eventos = "SELECT nome, imagem, data, descricao, local, hora, lotacao, duracao 
                    FROM eventos 
                    ORDER BY data DESC";  // Remove LIMIT 1

    $result = $conn->query($sql_eventos);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $caminho_imagem = "uploads/eventos/" . htmlspecialchars($row['imagem']);

            // Exibindo os dados do evento
            echo '<div class="carousel-slide">';
            echo '<div class="evento">';
            echo '<h1>' . date("d/m/Y", strtotime($row['data'])) . '<br>' . htmlspecialchars($row['nome']) . '</h1>';

            // Verificando se a imagem existe
            if (!empty($row['imagem']) && file_exists($caminho_imagem)) {
                echo '<img src="' . $caminho_imagem . '" class="evento-imagem" alt="' . htmlspecialchars($row['nome']) . '">';
            } else {
                echo '<p>Imagem não encontrada.</p>';
            }

            // Botão para exibir detalhes do evento
            echo '<button onclick="showDetails(\'' . addslashes($row['nome']) . '\', \'' . addslashes($caminho_imagem) . '\', \'' . date("d/m/Y", strtotime($row['data'])) . '\', \'' . addslashes($row['descricao']) . '\', \'' . addslashes($row['local']) . '\', \'' . $row['hora'] . '\', \'' . $row['lotacao'] . '\', \'' . $row['duracao'] . '\')">Saiba Mais →</button>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>Nenhum evento encontrado.</p>";
    }
}
?>

<div class="eventos">
            
        
<!-- Carrossel Automático-->
<div class="carousel">
    <div class="carousel-container">
        
            
        <?php exibirEventos(); ?>
           
        
    </div>
</div>

     

               <!-- Botões de navegação -->
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>


            </div>
            </div>
            </div>
             </div>

   

    <script> 

let currentSlide = 0;
const totalSlides = 3;  // Definindo o total de slides como 3
const slides = document.querySelectorAll('.carousel-slide');
let autoSlideInterval = null;

function showSlide(index) {
  // Ajuste para loop infinito
  if (index >= totalSlides) {
    currentSlide = 0;  // Volta ao primeiro slide
  } else if (index < 0) {
    currentSlide = totalSlides - 1;  // Vai para o último slide
  } else {
    currentSlide = index;
  }

  // Mover o carrossel para o slide correto
  const offset = -currentSlide * 100;
  document.querySelector('.carousel-container').style.transform = `translateX(${offset}%)`;
}

function moveSlide(direction) {
  showSlide(currentSlide + direction);
  resetAutoSlide();  // Reinicia o carrossel automático após interação manual
}

function startAutoSlide() {
  autoSlideInterval = setInterval(() => {
    moveSlide(1);  // Move para o próximo slide automaticamente
  }, 5000);  // Intervalo de 5 segundos
}

function stopAutoSlide() {
  clearInterval(autoSlideInterval);
}

function resetAutoSlide() {
  stopAutoSlide();
  startAutoSlide();
}

// Exibe o primeiro slide ao carregar a página
showSlide(currentSlide);

// Inicia o carrossel automático
startAutoSlide();

 </script>         
      

<!-- Modal -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="modalNome"></h2>
        <p id="modalData"></p>
        <p id="modalDescricao"></p>
        <p><strong>Local:</strong> <span id="modalLocal"></span></p>
        <p><strong>Hora:</strong> <span id="modalHora"></span></p>
        <p><strong>Lotação:</strong> <span id="modalLotacao"></span></p>
        <p><strong>Duração:</strong> <span id="modalDuracao"></span></p>
    </div>
</div>



<!-- Modal (já existente, sem alterações) -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="modalNome"></h2>
        <p id="modalData"></p>
        <p id="modalDescricao"></p>
        <p><strong>Local:</strong> <span id="modalLocal"></span></p>
        <p><strong>Hora:</strong> <span id="modalHora"></span></p>
        <p><strong>Lotação:</strong> <span id="modalLotacao"></span></p>
        <p><strong>Duração:</strong> <span id="modalDuracao"></span></p>
    </div>
</div>


 <!-- Estrutura do Modal -->
 <div id="infoModal" class="modal" style="display: none;">
                    <div class="modalContent">
                        <span class="clode-btn" onclick="closeDetails()">×</span>
                        <h2>Detalhes do Evento</h2>
                        <p><strong>Nome:</strong> <span id="modalNome"></span></p>
                        <p><strong>Data:</strong> <span id="modalData"></span></p>
                        <p><strong>Horário:</strong> <span id="modalHorario"></span></p>
                        <p><strong>Local:</strong> <span id="modalLocal"></span></p>
                        <p><strong>Descrição:</strong> <span id="modalDescricao"></span></p>
                    </div>
                </div>

              
            <!-- Modal (já existente, sem alterações) -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalNome"></h2>
        <p id="modalData"></p>
        <p id="modalDescricao"></p>
        <p><strong>Local:</strong> <span id="modalLocal"></span></p>
        <p><strong>Hora:</strong> <span id="modalHora"></span></p>
        <p><strong>Lotação:</strong> <span id="modalLotacao"></span></p>
        <p><strong>Duração:</strong> <span id="modalDuracao"></span></p>
    </div>
</div>



    <script src="Menu3.js"></script>
</body>
</html>
