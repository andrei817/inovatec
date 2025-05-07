<?php
include('php/Config.php');

$cadastroSucesso = false;
$buffetDuplicado = false;

// Buscar tipos de buffet
$sqlTipos = "SELECT id, descricao FROM tipo";
$resultTipos = $conn->query($sqlTipos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpeza e validação dos dados
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $tipo_id = intval($_POST['tipo_id'] ?? 0);

    // Validação básica
    if (empty($nome) || empty($descricao) || $tipo_id <= 0) {
        echo "Todos os campos são obrigatórios.";
        exit;
    }

    if (strlen($nome) > 100 || strlen($descricao) > 255) {
        echo "Nome ou descrição ultrapassam o tamanho permitido.";
        exit;
    }

    // Verificar se o buffet já existe
    $sqlCheck = "SELECT id FROM buffet WHERE nome = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $nome);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $buffetDuplicado = true;
    } else {
        // Iniciar transação
        $conn->begin_transaction();

        try {
            // Inserir buffet
            $sqlBuffet = "INSERT INTO buffet (nome, descricao) VALUES (?, ?)";
            $stmtBuffet = $conn->prepare($sqlBuffet);
            $stmtBuffet->bind_param("ss", $nome, $descricao);

            if (!$stmtBuffet->execute()) {
                throw new Exception("Erro ao cadastrar buffet.");
            }

            $buffet_id = $conn->insert_id;

            // Inserir relação buffet_tipo
            $sqlBuffetTipo = "INSERT INTO buffet_tipo (buffet_id, tipo_id) VALUES (?, ?)";
            $stmtTipo = $conn->prepare($sqlBuffetTipo);
            $stmtTipo->bind_param("ii", $buffet_id, $tipo_id);

            if (!$stmtTipo->execute()) {
                throw new Exception("Erro ao associar tipo ao buffet.");
            }

            $conn->commit();
            $cadastroSucesso = true;

        } catch (Exception $e) {
            $conn->rollback();
            error_log("Erro no cadastro de buffet: " . $e->getMessage());
            echo "Ocorreu um erro ao processar o cadastro.";
        }
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
    <title>SGE - Cadastro do Buffet</title>
    <link rel="stylesheet" href="Buffet.css">
    <script src="evento.js"> </script>

   

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
        <h3> Cadastrar Buffet </h3>
       
        <form method="POST" action="">
    <div class="input-group"> 
        <label for="nome">Nome do Buffet:</label>
        <input type="text" id="nome" name="nome" required>
    </div> 

    <div class="input-group"> 
        <label for="tipo_id">Tipo do Buffet:</label>
        <select id="tipo_id" name="tipo_id" required>
            <option value="">Selecione um tipo</option>
            <?php while ($row = $resultTipos->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['descricao']; ?></option>
            <?php } ?>
        </select>
    </div> 
    
    <div class="input-group"> 
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="2" cols="40" class="inputUser" required></textarea>
    </div> 
    
    
    
    <button type="submit" class="login-btn-buffet"> Cadastrar</button>
    <a href="lista de buffet.php"><button type="button" class="Cancel-btn-buffet">Cancelar</button></a>
</form>


<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h1>Buffet Cadastrado com Sucesso!</h1>
        <img src="correto.png" class="correto-img">
    </div>
</div>

<!-- Modal de Buffet Duplicado -->
<div id="modalDuplicado" class="modal-erro" style="display: none;">
    <div class="modal-content-erro"> 
        <span class="close-erro" onclick="fecharModalDuplicado('modalDuplicado')">&times;</span>
        <h1>Erro: Buffet já cadastrado!</h1>
        <p>O buffet com o nome informado já existe no sistema.</p>
        <img src="erro2.png" class="erro-img">
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalSucesso").style.display = "none";
    }

    function fecharModalDuplicado() {
        document.getElementById("modalDuplicado").style.display = "none";
    }

    // Função para redirecionar para outra página
    function redirecionarParaPagina() {
        window.location.href = "lista de buffet.php";  // Substitua com o URL da página desejada
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        //setTimeout(function() {
            //fecharModal();           // Fecha o modal
            //redirecionarParaPagina();  // Redireciona para outra página após 3 segundos
        //}, 3000); // Fecha automaticamente após 3 segundos
        <?php elseif ($buffetDuplicado): ?>
        document.getElementById("modalDuplicado").style.display = "flex";
    <?php endif; ?>
</script>


</section>

     </section>

   <script>
        function closeLogin() {   
            document.querySelector('.login-container').style.display = 'none'; }
    </script>

        </body>
        </html>