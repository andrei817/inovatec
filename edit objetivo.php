<?php
include('php/Config.php');

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID inválido ou não informado.";
    exit;
}

$id = intval($_GET['id']);

// Buscar os dados do objetivo
$sql = "SELECT * FROM objetivo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Objetivo não encontrado.";
    exit;
}

$objetivo = $result->fetch_assoc();

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    // Validação básica
    if (empty($nome)) {
        echo "O nome é obrigatório.";
        exit;
    }

    if (strlen($nome) > 100 || strlen($descricao) > 255) {
        echo "O nome ou a descrição excedem o tamanho permitido.";
        exit;
    }

    // Atualizar os dados com prepared statement
    $sql_update = "UPDATE objetivo SET nome = ?, descricao = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        error_log("Erro ao preparar update: " . $conn->error);
        echo "Erro interno ao tentar atualizar.";
        exit;
    }

    $stmt_update->bind_param("ssi", $nome, $descricao, $id);

    if ($stmt_update->execute()) {
        header('Location: lista de objetivos.php');
        exit;
    } else {
        error_log("Erro ao atualizar objetivo: " . $stmt_update->error);
        echo "Erro ao atualizar o objetivo.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="edit objetivo.css">
    <title>Alterar Objetivo</title>
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
    <a href="lista de objetivos.php" class="close-btn-obj">&times;</a>
    <h1>Editar Objetivo</h1>
    <form method="POST">
        <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $objetivo['nome']; ?>" required>
        </div>

        <div class="input-group">
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" rows="2" cols="40" class="inputUser" required><?php echo $objetivo['descricao']; ?></textarea><br>
        </div>


        <button type="submit" class="login-btn-obj">Alterar </button>
        <a href="lista de objetivos.php"><button type="button" class="Cancel-btn-obj">Cancelar</button></a>
    </form>
</body>
</html>
