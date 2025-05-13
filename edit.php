<?php
include('php/Config.php');

$erromensagem = false;
$exibirmodal = false; // Flag para exibir o modal
$mostrarModal = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM produtor WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produtor = $result->fetch_assoc();

    if (!$produtor) {
        die("Produtor não encontrado!");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace("/\D/", "", $_POST['telefone']);
    $cpf = preg_replace("/\D/", "", $_POST['cpf']);
    $pergunta_seg = $_POST['pergunta_seg'];
    $resposta_seg = $_POST['resposta_seg'];

    $senha = $produtor['senha'];

    if (!preg_match("/^\d{11}$/", $cpf) || !preg_match("/^\d{11}$/", $telefone)) {
        $erromensagem = true;
        $exibirmodal = true; // Exibe o modal
    }

    if (!$erromensagem) {
        // Verifica se já existe outro produtor com o mesmo email ou CPF
        $sqlVerifica = "SELECT id FROM produtor WHERE (email = ? OR cpf = ?) AND id != ?";
        $stmtVerifica = $conn->prepare($sqlVerifica);
        $stmtVerifica->bind_param("ssi", $email, $cpf, $id);
        $stmtVerifica->execute();
        $resultadoVerifica = $stmtVerifica->get_result();
    
        if ($resultadoVerifica->num_rows > 0) {
            $erromensagem = true;
            $mostrarModal = true;
            $mensagemErro = "Já existe outro produtor com este e-mail ou CPF.";
        } else {
            // Atualiza normalmente
            $sql = "UPDATE produtor SET nome = ?, email = ?, telefone = ?, cpf = ?, pergunta_seg = ?, resposta_seg = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nome, $email, $telefone, $cpf, $pergunta_seg, $resposta_seg, $id);
    
            if ($stmt->execute()) {
                header("Location: listar produtores.php");
                exit;
            } else {
                echo "Erro ao atualizar o produtor: " . $stmt->error;
            }
            $stmt->close();
        }
    
        $stmtVerifica->close();
    }
}    

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="edit.css">
    <title>SGE - Editar Produtor</title>
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



<section class="agenda-evento">
    <div class="conteudo">
    </div>

        <section class="login-section"> 
            <div class="login-box"> 
                <h1 class="title">Editar Produtor</h1>
                <a href="listar produtores.php" class="close-btn-edit">&times;</a>
                <form method="POST">
                   <div class="input-group-prod">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produtor['nome']); ?>" required><br>
                   </div>

                   <div class="input-group-prod">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($produtor['email']); ?>" required><br>
                   </div>

                   <div class="input-group-prod">
                        <label for="telefone">Telefone:</label>
                        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($produtor['telefone']); ?>"><br>
                   </div>

                   <div class="input-group-prod">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($produtor['cpf']); ?>"><br>
                   </div>

                   <div class="input-group-prod">
                        <label for="pergunta_seg">Pergunta de Segurança:</label>
                        <input type="text" id="pergunta_seg" name="pergunta_seg" value="<?php echo htmlspecialchars($produtor['pergunta_seg']); ?>" required><br>
                   </div>

                   <div class="input-group-prod">
                  <label for="resposta_seg">Resposta de Segurança:</label>
                  <input type="text" id="resposta_seg" name="resposta_seg" value="<?php echo htmlspecialchars($produtor['resposta_seg']); ?>" required>
              </div>

                   <button type="submit" class="login-btn-edit">Salvar</button>
                   <a href="listar produtores.php"><button type="button" class="Cancel-btn-edit">Cancelar</button></a>
                </form>
            </div>
    </div>
</section>

<?php if ($exibirmodal): ?>
    <div id="modalErroValidacao" class="modal-validacao">
        <div class="modal-content-validacao">
            <span class="close-validacao" onclick="fecharModalErro()">&times;</span>
            <p>Erro: CPF ou Telefone inválido. Ambos devem conter exatamente 11 dígitos.</p>
        </div>
    </div>
<?php endif; ?>


<script>
function fecharModalErro() {
    document.getElementById('modalErroValidacao').style.display = 'none';
}
</script>


<div id="modalErroDuplicado" class="modal-erro">
  <div class="modal-content-erro">
    <span class="close-erro" onclick="fecharModal()">&times;</span>
    <h2>Erro de Atualização</h2>
    <p><?php echo isset($mensagemErro) ? htmlspecialchars($mensagemErro) : ''; ?></p>
  </div>
</div>

<?php if ($mostrarModal): ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("modalErroDuplicado").style.display = "block";
  });

  function fecharModal() {
    document.getElementById("modalErroDuplicado").style.display = "none";
  }
</script>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function () {
    $('#cpf').mask('000.000.000-00', { reverse: true });
    $('#telefone').mask('(00) 00000-0000');
});
</script>

</body>
</html>
