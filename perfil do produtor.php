<?php
session_start();
include("php/Config.php");

// Verificar se o produtor está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$produtor_id = $_SESSION['id'];

// Consultar informações do produtor logado
$stmt = $conn->prepare("SELECT * FROM produtor WHERE id = ?");
$stmt->bind_param("i", $produtor_id);
$stmt->execute();
$produtor = $stmt->get_result()->fetch_assoc();

$cadastroSucesso = false;
$cadastroErro = false;
$erros = []; // Armazena múltiplos erros

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura e limpa os dados do formulário
    $novo_nome = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);
    $novo_telefone = trim($_POST['telefone']);
    $novo_cpf = trim($_POST['cpf']);
    $nova_pergunta_seg = trim($_POST['pergunta_seg']);
    $nova_resposta_seg = trim($_POST['resposta_seg']);
    $senha_atual = trim($_POST['senha']);
    $nova_senha = trim($_POST['nova_senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);

    // Validações
    if (empty($novo_nome)) {
        $cadastroErro = true;
        $erros[] = "O nome não pode estar vazio.";
    }

    if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $cadastroErro = true;
        $erros[] = "Email inválido.";
    }

    if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $novo_cpf) && !preg_match('/^\d{11}$/', $novo_cpf)) {
        $cadastroErro = true;
        $erros[] = "CPF inválido. O formato correto é XXX.XXX.XXX-XX ou XXXXXXXXXXX.";
    }

    if (!preg_match('/^\(\d{2}\)\s?\d{4,5}-\d{4}$/', $novo_telefone)) {
        $cadastroErro = true;
        $erros[] = "Telefone inválido. O formato correto é (XX) XXXX-XXXX ou (XX) XXXXX-XXXX.";
    }

    // Se o campo de senha atual foi preenchido, valida troca de senha
    if (!empty($senha_atual)) {
        if (!password_verify($senha_atual, $produtor['senha'])) {
            $cadastroErro = true;
            $erros[] = "Senha atual incorreta.";
        }

        if (empty($nova_senha)) {
            $cadastroErro = true;
            $erros[] = "A nova senha não pode estar vazia.";
        }

        if ($nova_senha !== $confirmar_senha) {
            $cadastroErro = true;
            $erros[] = "As senhas não coincidem. tente novamente.";
        }
    }

    // Se não houver erro, realiza atualização
    if (!$cadastroErro) {
        if (!empty($senha_atual) && password_verify($senha_atual, $produtor['senha'])) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE produtor SET nome = ?, email = ?, telefone = ?, cpf = ?, pergunta_seg = ?, resposta_seg = ?, senha = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $novo_nome, $novo_email, $novo_telefone, $novo_cpf, $nova_pergunta_seg, $nova_resposta_seg, $nova_senha_hash, $produtor_id);
        } else {
            // Atualiza sem alterar senha
            $stmt = $conn->prepare("UPDATE produtor SET nome = ?, email = ?, telefone = ?, cpf = ?, pergunta_seg = ?, resposta_seg = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $novo_nome, $novo_email, $novo_telefone, $novo_cpf, $nova_pergunta_seg, $nova_resposta_seg, $produtor_id);
        }

        // Executa atualização
        $_SESSION['nome'] = $novo_nome; // Atualiza nome na sessão
        if ($stmt->execute()) {
            $cadastroSucesso = true;
        } else {
            $cadastroErro = true;
            $erros[] = "Erro ao atualizar os dados. Tente novamente.";
        }
    }

    // Junta todas as mensagens de erro (com quebra de linha HTML)
    if ($cadastroErro && count($erros) > 0) {
        $erroMensagem = implode($erros);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="perfil do produtor.css">
    <title>Editar Perfil</title>
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

    <?php if (isset($_SESSION['msg'])): ?>
        <p style="color: green;"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>


 

  <div class="login-box">

  <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
        </svg>
    </div>

    <form method="POST" action="">
    <h2>Atualizar Perfil</h2>
    <a href="ambiente.php" class="fecha-btn">&times;</a>

    <!-- Campos existentes (nome, email, telefone, cpf) -->
    <div class="input-group-prod">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produtor['nome']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($produtor['email']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($produtor['telefone']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($produtor['cpf']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="pergunta_seg">Pergunta de Segurança:</label>
        <input type="text" id="pergunta_seg" name="pergunta_seg" value="<?php echo htmlspecialchars($produtor['pergunta_seg']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="resposta_seg">Resposta de Segurança:</label>
        <input type="text" id="resposta_seg" name="resposta_seg" value="<?php echo htmlspecialchars($produtor['resposta_seg']); ?>" required>
    </div>

    <div class="input-group-prod">
        <label for="senha">Senha Atual:</label>
        <input type="password" id="senha" name="senha">
    </div>

    <div class="input-group-prod">
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha">
    </div>

    <div class="input-group-prod">
        <label for="confirmar_senha">Confirmar Nova Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha">
    </div>


    <button type="submit" name="update_profile" class="login-btn-alt">Salvar Alterações</button>
    <a href="ambiente.php" class="a"> 
        <button type="button" class="Cancel-btn-alt">Cancelar</button>
    </a>
</form>


<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto">
        <span class="close-icon" onclick="fecharModal('modalSucesso')">&times;</span>
        <h1>Perfil Atualizado com Sucesso!</h1>
        <img src="correto.png" class="correto-img">
    </div>
</div>

<!-- Modal de Erro -->
<div id="errorModal" class="modal-erro">
    <div class="modal-content-erro">
        <span class="close-btn-erro" onclick="fecharModal('errorModal')">&times;</span>
        <h2>Erro</h2>
        <p id="errorMessage"></p> <!-- A mensagem de erro será inserida dinamicamente aqui -->
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Função para redirecionar para outra página (no caso do sucesso)
    function redirecionarParaPagina() {
        window.location.href = "ambiente.php";  // Substitua com o URL da página desejada
    }

    // Função para exibir o modal de sucesso
    function mostrarSucesso() {
        document.getElementById("modalSucesso").style.display = "flex";
        setTimeout(function() {
            fecharModal("modalSucesso");           // Fecha o modal de sucesso
            redirecionarParaPagina();              // Redireciona após 3 segundos
        }, 3000); // Fecha automaticamente após 3 segundos
    }

    // Função para exibir o modal de erro
    function mostrarErro(message) {
        const modal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        modal.style.display = 'flex'; // Mostra o modal
        //setTimeout(function() {
            //fecharModal("errorModal");  // Fecha o modal após 3 segundos
        //}, 3000); // Fecha automaticamente após 3 segundos
    }
</script>

<?php if ($cadastroSucesso): ?>
    <script>
        // Exibe o modal de sucesso e redireciona após 3 segundos
        mostrarSucesso();
    </script>
<?php elseif ($cadastroErro): ?>
    <script>
        // Exibe o modal de erro com a mensagem do PHP
        mostrarErro("<?php echo $erroMensagem; ?>");
    </script>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script> $('#cpf').mask('000.000.000-00', {reverse: true}); </script>
<script> $('#telefone').mask('(00) 00000-0000'); </script>



    
</div>

</section>
    
</body>
</html>
