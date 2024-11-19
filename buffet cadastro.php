<?php
// cadastrar_buffet.php
include ('php/Config.php');

$cadastroSucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
   

    // Query de inserção
    $sql = "INSERT INTO buffet (nome, descricao) 
            VALUES ('$nome', '$descricao')";

    if ($conn->query($sql) === TRUE) {
       // echo "Buffet cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $cadastroSucesso = true;

}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Cadastro do Buffet</title>
    <link rel="stylesheet" href="Buffet.css">
    <script src="evento.js"> </script>

   

</head>

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

<script> 
    // Função para abrir a sidebar
    function abrirSidebar() {
     document.getElementById("mySidebar").style.width = "310px";
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

    <section class="agenda">

    <section class="login-section">

        <div class="login-box"> 

        <a href="lista de buffet.php" class="close-btn-buffet">&times;</a>
        <h3> Cadastrar Buffet </h3>
       
        <form method="POST" action="">
        
            <div class="input-group"> 
            <label for="nome">Nome do Buffet:</label>
            <input type="text" id="nome" name="nome" required>
            </div> 
        
            <div class="input-group"> 
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="5" cols="40" required placeholder="Descrição do Buffet" ></textarea>
            </div> 
        

            <button type="submit" class="login-btn-buffet"> Cadastrar</button>
            <a href="lista de buffet.php"><button type="button" class="Cancel-btn-buffet">Cancelar</button></a>

        </form>

         <!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto">
         <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h2>Buffet Cadastrado com Sucesso!</h2>
        <img src="correto.png" class="correto-img">
       
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalSucesso").style.display = "none";
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        setTimeout(fecharModal, 3000); // Fecha automaticamente após 3 segundos
    <?php endif; ?>
</script>

</section>

     </section>

   <script>
        function closeLogin() {   
            document.querySelector('.login-container').style.display = 'none'; }
    </script>

<!-- script do sidebar -->
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
      document.getElementById("mySidebar").style.width = "250px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

    </script>

        
        </body>
        </html>