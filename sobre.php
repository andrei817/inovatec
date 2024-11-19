<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  fetch('/menu principal.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<div class="content">
  <!-- Conteúdo da página -->
</div>



             <div class="agenda-evento">
    <div class="conteudo">

        <div class="container">

            <div class="btn"> 
            <a href="ambiente.php" class="close-btn-sobre">&times;</a>
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


   <script>
// Função para abrir a sidebar
function abrirSidebar() {
  document.getElementById("mySidebar").style.width = "250px";
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

// Função para abrir a sidebar
function abrirSidebar() {
  // Se for um dispositivo móvel, ocupa 100% da tela; caso contrário, 250px
  if (window.innerWidth <= 768) {
      document.getElementById("mySidebar").style.width = "100%";
  } else {
      document.getElementById("mySidebar").style.width = "310px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}
</script>
    <script src="evento.js"> </script>
</body>
</html>
