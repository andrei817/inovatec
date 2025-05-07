<?php
// Conectar ao banco de dados
include ("php/Config.php");

$cadastroSucesso = false;
$objetivoDuplicado = false;

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    // Verificar se o objetivo já existe no banco de dados
    $sqlCheck = "SELECT id FROM objetivo WHERE nome = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    
    if ($stmtCheck === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    // Vincular o parâmetro e executar a verificação
    $stmtCheck->bind_param("s", $nome);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    // Se o objetivo já existir, definir $objetivoDuplicado como true
    if ($stmtCheck->num_rows > 0) {
        $objetivoDuplicado = true;
    } else {
        // Preparar a query de inserção
        $sql = "INSERT INTO objetivo (nome, descricao) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }

        // Vincular os parâmetros e executar a query
        $stmt->bind_param("ss", $nome, $descricao);

        if ($stmt->execute()) {
            // Cadastro bem-sucedido
           $cadastroSucesso = true;
        } else {
            echo "Erro ao cadastrar objetivo: " . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
    }

    // Fechar a declaração de verificação
    $stmtCheck->close();
} 


// Fechar a conexão
$conn->close();
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title> SGE - Objetivo do Evento</title>
    <link rel="stylesheet" href="objtivo.css">
    <style> 

</style>
</head>
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
        <textarea id="descricao" name="descricao" rows="2" cols="40" class="inputUser" required ></textarea>
    </div>

      
        <button type="submit" class="login-btn-obj">Cadastrar</button>
                <a href="lista de objetivos.php"><button type="button" class="Cancel-btn-obj">Cancelar</button></a>
    </form>
   </div>

 <!-- Modal de Sucesso -->
 <div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="icon-close" onclick="fecharModal()">&times;</span>
        <h2 class="msg">Objetivo Cadastrado com Sucesso!</h2>
        <img src="correto.png" class="correto-img">
       
    </div>
</div>

<!-- Modal de Objetivo Duplicado -->
<div id="modalDuplicado" class="modal-duplicado" style="display: none;">
    <div class="modal-content-duplicado">
        <span class="close-icon-duplicado" onclick="fecharModalDuplicado('modalDuplicado')">&times;</span>
        <h1>Erro: Objetivo Já Cadastrado!</h1>
        <p>O objetivo com o nome informado já existe no sistema.</p>
        <img src="erro2.png" class="erro-img">
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalSucesso").style.display = "none";
    }

   // Função para fechar o modal
   function fecharModalDuplicado() {
        document.getElementById("modalDuplicado").style.display = "none";
    }

     // Função para redirecionar para outra página
     function redirecionarParaPagina() {
        window.location.href = "lista de objetivos.php";  // Substitua com o URL da página desejada
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        //setTimeout(function() {
            //fecharModal();           // Fecha o modal
           // redirecionarParaPagina();  // Redireciona para outra página após 3 segundos
        //}, 3000); // Fecha automaticamente após 3 segundos
        <?php elseif ($objetivoDuplicado): ?>
        document.getElementById("modalDuplicado").style.display = "flex";
    <?php endif; ?>
    
</script>

</body>
</html>