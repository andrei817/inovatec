<?php
session_start();
include("php/Config.php");

$cadastroSucesso = false;

// Verificar se o produtor está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Obter o ID do produtor logado
$produtor_id = $_SESSION['id'];

// Consultar informações do produtor logado
$sql = "SELECT nome, email FROM produtor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produtor_id);
$stmt->execute();
$result = $stmt->get_result();
$produtor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $novo_nome = trim($_POST['nome']);
        $novo_email = trim($_POST['email']);

        // Validação básica
        if (!empty($novo_nome) && !empty($novo_email) && filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            $update_sql = "UPDATE produtor SET nome = ?, email = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssi", $novo_nome, $novo_email, $produtor_id);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                //$_SESSION['msg'] = "Perfil atualizado com sucesso!";
                //header('Location: ambiente.php');
                //exit;
            } else {
                $error = "Nenhuma alteração foi feita.";
            }
        } else {
            $error = "Por favor, insira um nome válido e um email válido.";
        }
         $cadastroSucesso = true;
    }
   
}

?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil do produtor.css">
    <title>Editar Perfil</title>
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

    <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produtor['nome']); ?>" required>
    </div>

    <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($produtor['email']); ?>" required>
    </div>

    <button type="submit" name="update_profile" class="login-btn-alt">Salvar Alterações</button>
    <a href="ambiente.php" class="a"> 
        <button type="button" class="Cancel-btn">Cancelar</button>
    </a>
</form>

<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h1>Perfil Atualizado com Sucesso!</h1>
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
        window.location.href = "ambiente.php";  // Substitua com o URL da página desejada
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

    
</div>

</section>
    
</body>
</html>
