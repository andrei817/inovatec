<?php
session_start();
include("php/Config.php");

// Verifica se o ID do cargo foi passado e é válido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['msg'] = "ID do cargo inválido.";
    header("Location: Cargo.php");
    exit();
}

$cargo_id = intval($_GET['id']);

// Consulta o cargo
$sql = "SELECT * FROM cargos WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Erro ao preparar SELECT do cargo: " . $conn->error);
    $_SESSION['msg'] = "Erro ao buscar o cargo.";
    header("Location: Cargo.php");
    exit();
}

$stmt->bind_param("i", $cargo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['msg'] = "Cargo não encontrado.";
    header("Location: Cargo.php");
    exit();
}

$cargo = $result->fetch_assoc();
$stmt->close();

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_cargo = trim($_POST['nome_cargo'] ?? '');

    if (empty($nome_cargo)) {
        $_SESSION['msg'] = "O nome do cargo não pode estar vazio.";
    } elseif (strlen($nome_cargo) > 100) {
        $_SESSION['msg'] = "O nome do cargo é muito longo.";
    } else {
        $sqlUpdate = "UPDATE cargos SET nome = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        if (!$stmtUpdate) {
            error_log("Erro ao preparar UPDATE do cargo: " . $conn->error);
            $_SESSION['msg'] = "Erro ao atualizar o cargo.";
            header("Location: Cargo.php");
            exit();
        }

        $stmtUpdate->bind_param("si", $nome_cargo, $cargo_id);

        if ($stmtUpdate->execute()) {
            $_SESSION['msg'] = "Cargo atualizado com sucesso!";
        } else {
            error_log("Erro ao executar UPDATE do cargo: " . $stmtUpdate->error);
            $_SESSION['msg'] = "Erro ao atualizar o cargo.";
        }

        $stmtUpdate->close();
        header("Location: Cargo.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="adcionar cargo.css">
    <title>Editar Cargo de Staff</title>
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

<section class="login-section">

        <div class="login-box">

    <a href="cargo.php" class="btn-close-cargo">&times;</a>
    <h2>Editar Cargo</h2>


    <form method="POST">

    <div class="input-group">
        <label for="nome_cargo">Nome do Cargo:</label>
        <input type="text" name="nome_cargo" value="<?php echo htmlspecialchars($cargo['nome']); ?>" required><br><br>
    </div>

        <button type="submit" class="login-btn-cargo">Atualizar</button>
        <a href="cargo.php"><button type="button" class="Cancel-btn-cargo">Cancelar</button></a>
    </form>

   
</body>
</html>
