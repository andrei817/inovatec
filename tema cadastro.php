<?php
// cadastrar_tema.php
include ('php/Config.php');

$cadastroSucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Query de inserção
    $sql = "INSERT INTO tema (nome, descricao) VALUES ('$nome', '$descricao')";

    if ($conn->query($sql) === TRUE) {
        //echo "Tema cadastrado com sucesso!";
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
    <title>SGE - Agenda de Eventos</title>
    <link rel="stylesheet" href="temas.css">
  
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



<div class="agenda-evento">
    <div class="conteudo">
      
    <section class="login-section-tema">

        <div class="login-box-tema">
        <a href="tema lista.php" class="close-btn-tema">&times;</a>

  <h3> Cadastrar Tema </h3>

<form method="POST" action="">

     <div class="input-group">
    <label for="nome">Nome do Tema:</label>
    <input type="text" id="nome" name="nome" required>
     </div>

     <div class="input-group">
    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao"  rows="2" cols="40" class="inputUser" placeholder="Descrição do Tema"required ></textarea>
     </div>

         <button type="submit" class="login-btn-tema"> Cadastrar</button>
         <a href="tema lista.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
        
</form>

 <!-- Modal de Sucesso -->
 <div id="modalSucesso" class="modal-correto">
 
    <div class="modal-content-correto">
         <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h2>Tema Cadastrado com Sucesso!</h2>
        <img src="correto.png" class="correto-img">
       
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalSucesso").style.display = "none";
    }

    // Função para redirecionar para outra página
    function redirecionarParaPagina() {
        window.location.href = "tema lista.php";  // Substitua com o URL da página desejada
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        setTimeout(function() {
            fecharModal();           // Fecha o modal
            redirecionarParaPagina();  // Redireciona para outra página após 3 segundos
        }, 3000); // Fecha automaticamente após 3 segundos
       
    <?php endif; ?>
</script>
        
  <script src="evento.js"> </script>


  <!-- script do sidebar -->
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
      document.getElementById("mySidebar").style.width = "250px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}
    </script>
</div>
    </section>






</body>
</html>
