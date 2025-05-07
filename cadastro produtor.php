<?php
include('php/Config.php');

$cadastroSucesso = false;
$erroCadastro = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $pergunta_seg = $_POST['pergunta_seg'];
    $resposta_seg = $_POST['resposta_seg'];

    // Criptografar a senha com BCRYPT
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    // Verifica se já existe produtor com o mesmo email ou CPF
    $sqlCheck = "SELECT * FROM produtor WHERE email = ? OR cpf = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ss", $email, $cpf);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Já existe um produtor com este email ou CPF
        $erroCadastro = true;
    } else {
        // Inserir novo produtor no banco
        $sql = "INSERT INTO produtor (nome, email, telefone, senha, cpf, pergunta_seg, resposta_seg) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nome, $email, $telefone, $senha, $cpf, $pergunta_seg, $resposta_seg);

        if ($stmt->execute()) {
           $cadastroSucesso = true;
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmtCheck->close();
} 
?>





<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Cadastro do Produtor</title>
    <link rel="stylesheet" href="cadastro produtor.css">
    
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

    
              
            <h1> Cadastro do Produtor</h1>
           
            <a href="listar produtores.php" class="fecha-btn">&times;</a>
            <form action="" method="post">
            <form id="formCadastro" onsubmit="return cadastrarConta()">

               <div class="input-group-prod">
               <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
              </div> 

              <div class="input-group-prod">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" required>
               </div> 

                <div class="input-group-prod">
              <label for="telefone">Telefone:</label>
              <input type="tel" id="telefone" name="telefone" > 
             </div> 

          
                <div class="input-group-prod">
                 <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" >
                </div> 

               <div class="input-group-prod">
             <label for="senha">Senha:</label>
             <input type="password" id="senha" name="senha" >
             </div> 

             <div class="input-group-prod">
             <label for="confirmPassword">Confirmar Senha:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>

            <div class="input-group-prod">
                    <label for="pergunta_seg">Pergunta</label>
                    <input type="text" name="pergunta_seg" required>
                </div>

                <div class="input-group-prod">
                    <label for="resposta_seg">Resposta</label>
                    <input type="text" name="resposta_seg" required>
                </div>


            <p id="error-message" class="error-message" style="display: none;">As senhas não coincidem.</p>

                <button type="submit" class="login-btn">Cadastrar</button>
                <a href="listar produtores.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
               
            </form>

          <!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="close-icon-correto" onclick="fecharModal()">&times;</span>
        <h2>Produtor Cadastrado com Sucesso!</h2>
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
         //setTimeout(fecharModal, 3000); // Fecha automaticamente após 3 segundos
    <?php endif; ?>
</script>


<!-- Modal de Erro -->
<div id="modalErro" class="modal-incorreto">
    <div class="modal-content-incorreto">
        <span class="close-icon-incorreto" onclick="fecharModalErro()">&times;</span>
        <h2>Erro ao Cadastrar!</h2>
        <p>Esse produtor já está cadastrado no sistema.</p>
        <img src="erro2.png" class="erro-img">
    </div>
</div>

<script>
    // Função para fechar o modal de erro
    function fecharModalErro() {
        document.getElementById("modalErro").style.display = "none";
    }

    // Exibe o modal de erro se o cadastro falhou por duplicidade
    <?php if ($erroCadastro): ?>
        document.getElementById("modalErro").style.display = "flex";
    <?php endif; ?>
</script>




        </section>
    </section>
    <script>
        function closeLogin() {
            
            document.querySelector('.login-container').style.display = 'none';
        }
       
    </script>

    <script> 
    // script.js
function validatePasswords() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const errorMessage = document.getElementById("error-message");

    // Verificar se as senhas coincidem
    if (password !== confirmPassword) {
        errorMessage.style.display = "block"; // Exibe a mensagem de erro
        return false; // Impede o envio do formulário
    } else {
        errorMessage.style.display = "none"; // Esconde a mensagem de erro
        return true; // Permite o envio do formulário
    }
}

    </script>

    <script src="login.js"> </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script> $('#cpf').mask('000.000.000-00', {reverse: true}); </script>
<script> $('#telefone').mask('(00) 00000-0000'); </script>
           
</body>
</html>



