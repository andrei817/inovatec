<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Sobre nós</title>
    <link rel="stylesheet" href="sobre.css">
    
</head>

<style> 

h3 {
    font-size: 2.5rem;
    color: white;
    margin-bottom: 20px;
    text-align: left;
    position: relative;

}
</style>

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

        <div class="container2">

            <div class="btn"> 
            <a href="ambiente.php" class="btn-close-sobre">&times;</a>
               </div>

            <h3>Sobre nós </h3>
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