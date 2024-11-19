<?php
// Conectar ao banco de dados
include ("php/Config.php");

$cadastroSucesso = false;

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Preparar a query de inserção
    $sql = "INSERT INTO objetivo (nome, descricao) VALUES ('$nome', '$descricao')";

    // Preparar a declaração
    $stmt = $conn->prepare($sql);

    // Verificar se a preparação falhou
    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Vincular os parâmetros
    //$stmt->bind_param("sss", $nome, $descricao);

    // Executar a query
    if ($stmt->execute()) {
        //echo "Objetivo cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar objetivo: " . $stmt->error;
    }

    // Fechar a declaração
    $stmt->close();

    $cadastroSucesso = true;
}

// Fechar a conexão
$conn->close();



?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> SGE - Objetivo do Evento</title>
    <link rel="stylesheet" href="objtivo.css">
    <style> 

</style>
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
     
    <section class="login-section">

    <div class="login-box"> 
         <h2> Cadastrar Objetivo </h2>
    <a href="lista de objetivos.php" class="close-btn-obj">&times;</a>
    <form action="objetivo.php" method="POST">

    <div class="input-group"> 
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"  required>
    </div>

    <div class="input-group"> 
        <label for="objetivo">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="2" cols="40" placeholder="Descrição do Objetivo"required ></textarea>
    </div>

      
        <button type="submit" class="login-btn-obj">Cadastrar</button>
                <a href="?"><button type="button" class="Cancel-btn-obj">Cancelar</button></a>
    </form>
   </div>

 <!-- Modal de Sucesso -->
 <div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="icon-close" onclick="fecharModal()">&times;</span>
        <h2>Objetivo Cadastrado com Sucesso!</h2>
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
      document.getElementById("mySidebar").style.width = "310px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

    </script>

</body>
</html>
