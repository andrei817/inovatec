<?php
session_start();
include("php/Config.php");

// Verifica se o ID do tema foi passado via GET e é válido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['msg'] = "ID do tema não fornecido ou inválido.";
    header("Location: tema lista.php");
    exit();
}

$id_tema = intval($_GET['id']); // Converte o ID para inteiro de forma segura

// Consulta o tema de evento a ser editado
$sql = "SELECT * FROM tema WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tema);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tema = $result->fetch_assoc();
} else {
    $_SESSION['msg'] = "Tema não encontrado.";
    header("Location: tema lista.php");
    exit();
}

// Verifica se o formulário foi enviado para atualizar o tema
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpa os dados recebidos (trim e validações simples)
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    // Validações simples
    if (empty($nome)) {
        $_SESSION['msg'] = "O campo nome é obrigatório.";
        header("Location: tema lista.php");
        exit();
    }

    if (strlen($nome) > 100 || strlen($descricao) > 255) {
        $_SESSION['msg'] = "Nome ou descrição ultrapassam o tamanho permitido.";
        header("Location: tema lista.php");
        exit();
    }

    // Atualiza os dados com prepared statements (proteção contra SQL Injection)
    $sql = "UPDATE tema SET nome = ?, descricao = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $descricao, $id_tema);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Tema atualizado com sucesso!";
    } else {
        // Registra o erro no log, mas não mostra ao usuário
        error_log("Erro ao atualizar tema: " . $stmt->error);
        $_SESSION['msg'] = "Erro ao atualizar o tema.";
    }

    header("Location: tema lista.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Editar Tema</title>
    <link rel="stylesheet" href="editar tema.css">

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

        <section class="login-section-tema">
            <div class="login-box-tema">
                <h2>Editar Tema</h2>
                <a href="tema lista.php" class="close-btn-edit">&times;</a>

                <form action="" method="POST">
                    <div class="input-group">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" value="<?= htmlspecialchars($tema['nome']) ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="descricao">Descrição:</label>
                        <textarea name="descricao" rows="3" cols="40" class="inputUser" required><?= htmlspecialchars($tema['descricao']) ?></textarea>
                    </div>

                   
                        <button type="submit" name="editar" class="login-btn-tema"> Atualizar</button>
                        <a href="tema lista.php"><button type="button" class="Cancel-btn-tema">Cancelar</button></a>
                    
                </form>
            </div>
        </section>
    </div>
</body>
</html>
