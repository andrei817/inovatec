<!-- editar_buffet.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="edit-buffet.css">
    <title>Editar Buffet</title>
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
     <a href="lista de buffet.php" class="close-btn-buffet">&times;</a>
    <h3>Editar Buffet</h3>

    <?php
include('php/Config.php');

$cadastroSucesso = false;
$buffetDuplicado = false;

// Buscar tipos de buffet
$sqlTipos = "SELECT id, descricao FROM tipo";
$resultTipos = $conn->query($sqlTipos);

// Verifica se está editando
$buffet = null;
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = intval($_GET['id']);

    $sql = "SELECT b.*, bt.tipo_id 
            FROM buffet b 
            LEFT JOIN buffet_tipo bt ON b.id = bt.buffet_id 
            WHERE b.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $buffet = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $tipo_id = intval($_POST['tipo_id'] ?? 0);
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    // Validação básica
    if (empty($nome) || empty($descricao) || $tipo_id <= 0) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    if (strlen($nome) > 100 || strlen($descricao) > 255) {
        echo "O nome ou descrição excedem o tamanho permitido.";
        exit;
    }

    if ($id) {
        // Atualizar buffet
        $conn->begin_transaction();
        try {
            $sqlUpdate = "UPDATE buffet SET nome = ?, descricao = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ssi", $nome, $descricao, $id);
            $stmtUpdate->execute();

            // Atualizar tipo associado
            $sqlTipo = "UPDATE buffet_tipo SET tipo_id = ? WHERE buffet_id = ?";
            $stmtTipo = $conn->prepare($sqlTipo);
            $stmtTipo->bind_param("ii", $tipo_id, $id);
            $stmtTipo->execute();

            $conn->commit();
            header("Location: lista de buffet.php");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Erro na atualização do buffet: " . $e->getMessage());
            echo "Erro ao atualizar o buffet.";
        }
    } else {
        // Verificar se buffet já existe
        $sqlCheck = "SELECT id FROM buffet WHERE nome = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $nome);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $buffetDuplicado = true;
        } else {
            $conn->begin_transaction();
            try {
                // Inserir novo buffet
                $sqlBuffet = "INSERT INTO buffet (nome, descricao) VALUES (?, ?)";
                $stmtBuffet = $conn->prepare($sqlBuffet);
                $stmtBuffet->bind_param("ss", $nome, $descricao);
                $stmtBuffet->execute();
                $buffet_id = $stmtBuffet->insert_id ?? $conn->insert_id;

                // Relacionar tipo ao buffet
                $sqlTipo = "INSERT INTO buffet_tipo (buffet_id, tipo_id) VALUES (?, ?)";
                $stmtTipo = $conn->prepare($sqlTipo);
                $stmtTipo->bind_param("ii", $buffet_id, $tipo_id);
                $stmtTipo->execute();

                $conn->commit();
                $cadastroSucesso = true;
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Erro ao cadastrar buffet: " . $e->getMessage());
                echo "Erro ao cadastrar buffet.";
            }
        }

        $stmtCheck->close();
    }
}
?>

    <!-- Formulário de edição -->
    <form method="POST" action="">
    <?php if (isset($id)) { ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <?php } ?>
    
    <div class="input-group"> 
        <label for="nome">Nome do Buffet:</label>
        <input type="text" id="nome" name="nome" required value="<?php echo isset($buffet['nome']) ? htmlspecialchars($buffet['nome']) : ''; ?>">
    </div> 

    <div class="input-group"> 
        <label for="tipo_id">Tipo do Buffet:</label>
        <select id="tipo_id" name="tipo_id" required>
            <option value="">Selecione um tipo</option>
            <?php while ($row = $resultTipos->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>" <?php echo (isset($buffet['tipo_id']) && $buffet['tipo_id'] == $row['id']) ? 'selected' : ''; ?>>
                    <?php echo $row['descricao']; ?>
                </option>
            <?php } ?>
        </select>
    </div> 
    
    <div class="input-group"> 
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="2" cols="40" class="inputUser" required><?php echo isset($buffet['descricao']) ? htmlspecialchars($buffet['descricao']) : ''; ?></textarea>
    </div> 
    
    
    
    <button type="submit" class="login-btn-buffet"> <?php echo isset($id) ? "Atualizar" : "Cadastrar"; ?> </button>
    <a href="lista de buffet.php"><button type="button" class="Cancel-btn-buffet">Cancelar</button></a>
</form>

   
</body>
</html>
