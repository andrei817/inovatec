<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Ajuda</title>
    <link rel="stylesheet" href="ajuda.css">
    
</head>

<style> 


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
}

/* Estilo da sidebar */
.sidebar {
    height: 100%;
    width: 0;
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
    font-size: 18px;
    color: #ddd;
    display: block;
    transition: 0.3s;
   
}

.sidebar a:hover {
    color: #5214cb;
    background-color: rgba(255, 255, 255, 0.1);
}

/* Botão de fechar (X) */
.close-btn {
    position: absolute;
    top: 20px;
    right: 25px;
    font-size: 36px;
    color: #ffff;
    cursor: pointer;
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

      <section class="Ajudar"> 


        <a href="ambiente.php" class="close-btn-ajuda">&times;</a>
        <div class="help-container">
            <h1 class="Ajuda">Ajuda</h1>
            <p> Olá seja bem vindo ao S.G.E. aqui a baixo estão as opções de ajuda caso você precise compreender melhor nosso site</p>
            
            <div class="help-section">
              <button class="help-title">Agenda</button>
              <div class="help-content">
                <p>Centralizado em sua tela é nossa agenda de eventos,rolando para os lados você visualiza outros eventos disponíveis<br> e ao clicar em algum desses você terá mais informações sobre o evento escolhido.</p>
              </div>
            </div>
        
            <div class="help-section">
              <button class="help-title">Sobre</button>
              <div class="help-content">
                <p>Na tela de sobre você terá informações sobre que somos e sobre nosso site.</p>
                
              </div>
            </div>
        
            <div class="help-section">
              <button class="help-title"> Acessar</button>
              <div class="help-content">
                <p>Esta opção está indisponível, pois está limitada apenas aos produtores do site.</p>
              </div>
            </div>

            <div class="help-section">
              <button class="help-title"> Como funciona o carrossel de eventos?</button>
              <div class="help-content">
                <p>No carrossel os eventos centralizados serão os próximos e ao usuário clicar nas setas indicadoras serão exibidos todos os outros eventos.</p>
              </div>
            </div>

            <div class="help-section">
              <button class="help-title"> Como posso visualizar detalhes dos eventos?</button>
              <div class="help-content">
                <p>(Abaixo dos eventos que aparecem na tela inicial possui a opção de "saiba mais" nessa opção clicável você terá informações detalhadasa do evento escolhido.</p>
              </div>
            </div>

        </div>

          </div>
  </div>
   </section>

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
    <script src="ajuda.js"> </script>
</body>
</html>