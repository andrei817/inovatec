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
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Agenda de Eventos</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="index.js"> </script>

    <style> 
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0;
    padding: 0;
    color: white;
    background-image: url(Gestão.jpg);
    background-repeat: no-repeat ;
    background-size: cover;
    background-position: center;
    background-attachment: fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    overflow: hidden;

}


@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 300dpi) {
    body {
        background-image: url(Gestão.jpg);
    }
}


/* Para dispositivos com largura até 600px (celulares) */
@media only screen and (min-width: 375px) and (max-width: 667px) {
    body {
      font-size: 14px;
    }
  
    .container {
      width: 100%;
      padding: 1em;
    }
  }
  
  /* Para dispositivos com largura até 768px (tablets) */
  @media only screen and (min-width: 768px) and (max-width: 1024px) {
    .container {
      width: 100%;
    }
  }
  
  /* Para dispositivos maiores que 1024px (desktops) */
  @media only screen and (min-width: 1024px) {
    .container {
      width: 100%;
    }
  }


/* Estilo do cabeçalho */

header {
    display: flex;
    width: 100%;
    height: 70px;
    background-color: #5214CB #3c1292;
    color: #F3F4F6;
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
    justify-content: center; /* Centraliza horizontalmente dentro da div */
    align-items: center; /* Centraliza verticalmente dentro da div */
    width: 150px; /* Define o tamanho da logo */
    height: auto; /* Mantém a proporção da imagem */
    margin: 7px;
    margin-left: 318px;
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
    width: 100px; /* Define o tamanho da logo */
    height: auto; 
    align-items: right;
    opacity: 0.9;
    margin-left: 18px;
    padding: 0 auto;
    text-align: left;
    margin-top: 10px;
    position: relative;
    margin-left: 90px;
}




nav ul {
    list-style: none;
    padding: 12px;
    margin: 13px 0;
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-right: -100px;
    
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

@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap");
:root {
  --primary: #eeeeee;
  --secondary: #290670;
  --green: #82cd47;
  --secondary-light: rgba(110, 28, 216, 0.726);
  --secondary-light-2: rgb(127, 183, 126, 0.1);
  --white: #fff;
  --black: #393e46;

  --shadow: 0px 2px 8px 0px var(--secondary-light);
}

.profile-dropdown {
    position: relative;
    width: fit-content;
    list-style: none;
    top: 10px;
    left: 35px;
  }

  .profile-dropdown-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.05rem 0.2rem;   /* Padding ainda menor */
    font-size: 0.6rem;         /* Tamanho de fonte menor */
    font-weight: 400;          /* Peso da fonte ajustado */
    width: 75%;               /* Largura ajustada ao conteúdo */
    border-radius: 20px;       /* Menor raio de borda */
    border: 1px solid var(--secondary); /* Borda fina */
    cursor: pointer;
    transition: box-shadow 0.2s ease-in, background-color 0.2s ease-in, border 0.
  }  
  
  
  .profile-dropdown-btn:hover {
    background-color: var(--secondary-light-2);
    box-shadow: var(--shadow);
  }
  .profile-img {
    position: relative;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: url(./assets/profile-pic.jpg);
    background-size: cover;
  }

  .profile-dropdown-btn span {
    margin: 0 0.5rem;
    margin-right: 0;
  }

  .profile-dropdown-btn span {
    margin: 0 0.5rem;
    margin-right: 10px;;
  }
  .profile-dropdown-list {
    position: absolute;
    top: 68px;
    width: 269px;
    right: 0;
    background-color: #5214cb;
    border-radius: 10px;
    max-height: 0;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: max-height 0.5s;
    list-style: none; /* Remove as bolinhas */
  }
  .profile-dropdown-list hr {
    border: 0.5px solid var(--green);
  }
  .profile-dropdown-list.active {
    max-height: 400px;
  }
  .profile-dropdown-list-item {
    padding: 0.5rem 0rem 0.5rem 1rem;
    transition: background-color 0.2s ease-in, padding-left 0.2s;
  }
  .profile-dropdown-list-item a {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    color: white;
    cursor: pointer;
  }
  .profile-dropdown-list-item a i {
    margin-right: 0.8rem;
    font-size: 1.1rem;
    width: 2.3rem;
    height: 2.3rem;
    background-color: var(--secondary);
    color: var(--white);
    line-height: 2.3rem;
    text-align: center;
    margin-right: 1rem;
    border-radius: 50%;
    transition: margin-right 0.3s;
  }
  .profile-dropdown-list-item:hover {
    padding-left: 1.5rem;
    background-color: var(--secondary-light);
  }






/* Estilo do botão de abrir a sidebar */
.open-btn {
    padding: 12px 24px;
    background-color: #5214cb;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    font-size: 20px;
}

/* Estilo da sidebar */
.sidebar {
    height: 100%;
    width: 0px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #5214cb;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 60px;
    color: white;
    z-index: 1000;
    box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
    
}

/* Conteúdo dentro da sidebar */
.sidebar a {
    padding: 10px 20px;
    text-decoration: none;
    font-size: 20px;
    color: #ddd;
    display: block;
    transition: 0.3s;
   
}

.sidebar a:hover {
    color: #5214cb;
    background-color: rgba(61, 3, 136, 0.733);
    border-radius: 20px;
}

/* Botão de fechar (X) */
.btn-close{
    position: absolute;
    top: 20px;
    right: 25px;
    font-size: 36px;
    color: #ffff;
    cursor: pointer;
}

ul.separator {
	list-style: none;
	padding: 0;
	width: 100%;
}

ul.separator ul {
	padding: .5em 0;
	border-bottom: 1px solid #CCC;
}


/* Modal de fundo */
.modal-sair {
    display: none; /* Oculto por padrão */
    position: fixed;
    z-index: 1000;
    inset: 0; /* shorthand para top, right, bottom, left = 0 */
    background-color: rgba(0, 0, 0, 0.5); /* Fundo escuro com opacidade */
    justify-content: center;
    align-items: center;
}

/* Conteúdo do modal */
.modal-content-sair {
    background-color: #5214cb;
    padding: 30px;
    border-radius: 8px;
    width: 300px !important;
    max-width: 90%;
    height: auto !important;
    box-sizing: border-box;
    text-align: center;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s ease-out;
}

/* Título do modal */
.modal-content-sair h2 {
    margin: 0 0 10px 0;
    font-size: 1.2rem;
}

/* Botões do modal */
.modal-content-sair button {
    margin: 10px 5px 0 5px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
}

/* Botão Sim */
.modal-content-sair .btn-yes {
    background-color: #4CAF50;
    color: white;
}

/* Botão Não */
.modal-content-sair .btn-no {
    background-color: #e7190a;
    color: white;
}

/* Animação de entrada suave */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

    </style>

</head>

<body>

  <header>
        
    <!-- Botão para abrir a sidebar -->
<button class="open-btn" onclick="abrirSidebar()">☰</button>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar">
   <span class="btn-close" onclick="fecharSidebar()">&times;</span> <!-- Botão "X" -->
   <h2 style="padding-left: 20px;">Menu</h2>
   &nbsp;
   <ul class="separator"> 
        <li><a href="lista de eventos.php"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
        &nbsp;
        <li><a href="colaboradores.php"><i class="fas fa-users"></i> Colaboradores</a></li>
        &nbsp;
        <ul>
            <li><a href="lista de staff.php"><i class="fas fa-user-tie"></i> Staff</a></li>
        </ul>
        &nbsp;
        <ul>
            <li><a href="Cargo.php"><i class="fas fa-briefcase"></i> Cargo</a></li>
        </ul>
        &nbsp;
        <ul>
            <li><a href="tema lista.php"><i class="fas fa-tag"></i> Tema</a></li>
        </ul>
        &nbsp;
        <ul>
            <li><a href="lista de buffet.php"><i class="fas fa-concierge-bell"></i> Buffet</a></li>
        </ul>
        &nbsp;
        <ul>
            <li><a href="lista de objetivos.php"><i class="fas fa-bullseye"></i> Objetivos</a></li>
        </ul>
        &nbsp;
        <li><a href="relatório de problemas.php"><i class="fas fa-exclamation-circle"></i> Reportar Problemas</a></li>
        &nbsp;
    </ul>

</div>





       <div class="logo-foto"> 
          <img src="Logo_SGE_inova.png"width=80% height="100%">
          
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
                
                 <li> <a href="ambiente.php" title="Página Iinicial"> Home</a></li>  
                 <li> <a href= "ajuda do produtor.php" title="Obtenha ajuda">Ajuda</a></li>
                 <li> <a href= "sobre.php" title ="Sobre nós">Sobre</a></li>
                
            </ul>
           
           </div>

               </nav>
               <div class="profile-dropdown">
               <div onclick="toggle()" class="profile-dropdown-btn">
              
               <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
           <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
           <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
           
           
       </svg>
    
                   
               
               <span
                   > 
                  <h1>Bem-vindo, <?php echo htmlspecialchars($produtor_nome); ?>! </h1> 
                  
               </span>
               </div>
              
                   <ul class="profile-dropdown-list">
                  
                     <li class="profile-dropdown-list-item">
                       <a href="listar produtores.php">
                       <i class="bi bi-person-plus"></i>
                         Gerenciar produtor
                       </a>
                     </li>
                     <li class="profile-dropdown-list-item">
                       <a href="perfil do produtor.php">
                       <i class="bi bi-pen"></i>
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
<div id="logoutModal" class="modal-sair">
    <div class="modal-content-sair">
        <h2>Deseja se deslogar?</h2> 
            <!-- Botão Sim ativa a função JavaScript -->
            <button class="btn btn-yes" onclick="confirmLogout()">Sim</button>
        <!-- Botão Não fecha o modal -->
        <button class="btn btn-no" onclick="closeModal('logoutModal')">Não</button>
    </div>
</div>

<!-- Modal de Agradecimento -->
<div id="thankYouModal" class="modal-sair">
    <div class="modal-content-sair">
        <h2>Obrigado por usar o nosso site!</h2>
    </div>
</div>

<script>
    // Função para mostrar o modal de logout
    function showLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    // Função para fechar qualquer modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Função para lidar com o logout com modal de agradecimento
    function handleLogout() {
        // Fecha o modal de logout
        closeModal('logoutModal');

        // Mostra o modal de agradecimento
        document.getElementById('thankYouModal').style.display = 'flex';

        // Aguarda 2 segundos antes de enviar o formulário
        setTimeout(function() {
            document.getElementById('logoutForm').submit(); // Envia o formulário de logout
        }, 2000); // 2000 milissegundos = 2 segundos
    }
</script>




                     
                 
                   </ul>
                 </div>
               </nav>
             </li>
   
   </header>
          
    </section>

   <script> 

   // Função para abrir a sidebar
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
  document.addEventListener('click', function (event) {
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

    // Fechar o modal ao clicar fora do conteúdo
    window.onclick = function(event) {
      var confirmModal = document.getElementById("confirmModal");
      var infoModal = document.getElementById("infoModal");
      
      if (event.target == confirmModal) {
          confirmModal.style.display = "none";
      }
      if (event.target == infoModal) {
          infoModal.style.display = "none";
      }
  }

</script>





   

