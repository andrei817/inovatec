<?php
include("php/Config.php");

$cadastroSucesso = false;
$problemaDuplicado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação e sanitização
    $evento_id = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);
    $data = trim($_POST['data']);
    $descricao_problema = trim($_POST['descricao_problema']);
    $contato = trim($_POST['contato']);

    if ($evento_id && $data && $descricao_problema && $contato) {
        // Verificar duplicidade com prepared statement
        $stmtCheck = $conn->prepare("SELECT id FROM problemas_evento WHERE evento_id = ? AND data = ?");
        $stmtCheck->bind_param("is", $evento_id, $data);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $problemaDuplicado = true;
        } else {
            // Inserir dados com segurança
            $stmtInsert = $conn->prepare("INSERT INTO problemas_evento (evento_id, data, descricao_problema, contato) VALUES (?, ?, ?, ?)");
            $stmtInsert->bind_param("isss", $evento_id, $data, $descricao_problema, $contato);

            if ($stmtInsert->execute()) {
               $cadastroSucesso = true;
            } else {
                error_log("Erro ao inserir problema: " . $stmtInsert->error);
                echo "Erro ao reportar o problema. Tente novamente mais tarde.";
            }
            $stmtInsert->close();
        }

        $stmtCheck->close();
    } else {
        echo "Todos os campos são obrigatórios e devem ser válidos.";
    }
} 
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="reporte de problemas.css">
    <title>Reportar Problema</title>
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
        
    <div class="form">

    <h1>Reportar Problema de Evento</h1>
    <a href="relatório de problemas.php" class="close_login-btn">&times;</a>

    <form action="" method="POST">
       <div class="input-group">
        <label for="evento_id">Evento:</label>
        <select name="evento_id" id="evento_id" class="inputUser" required>
            <option value=""> Selecione o Evento </option>
            <?php
            // Conexão com o banco de dados
            include("php/Config.php");

            // Buscar os eventos e suas datas
            $sql = "SELECT id, nome, data FROM eventos"; // Alterado para 'data'
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "' data-data='" . $row['data'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum evento encontrado</option>";
            }
            ?>
        </select><br>
     </div>

        <div class="input-group">
        <label for="data">Data do Evento:</label>
        <input type="date" id="data" name="data" required readonly> 
        </div>

        <div class="input-group">
        <label for="descricao_problema">Descrição do Problema:</label>
        <textarea id="descricao_problema" name="descricao_problema" rows="2" class="inputUser" required></textarea>
        </div>

        <div class="input-group">
        <label for="contato">E-mail :</label>
        <input type="text" id="contato" name="contato" required placeholder="Digite seu e-mail">
        </div>

        <button type="submit" class="login-reportar"> Reportar</button>
        <a href="relatório de problemas.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
        
    </form>
    </div>
</div>

<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="close-btn-icon" onclick="fecharModal()">&times;</span>
        <h1 class="msg">Problema Reportado!</h1>
        <img src="correto.png" class="correto-img">
    </div>
</div>


<!-- Modal de Problema Duplicado -->
<div id="modalDuplicado" class="modal-erro" style="display: none;">
    <div class="modal-content-erro">
        <span class="close-icon-erro" onclick="fecharModalDuplicado('modalDuplicado')">&times;</span>
        <h1 class="msg2">Erro: Este Problema Já Foi Reportado!</h1>
        <p>Este problema já foi registrado para o evento na mesma data.</p>
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
        window.location.href = "relatório de problemas.php";  // Substitua com o URL da página desejada
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        //setTimeout(function() {
           // fecharModal();           // Fecha o modal
            //redirecionarParaPagina();  // Redireciona para outra página após 3 segundos
        //}, 3000); // Fecha automaticamente após 3 segundos
        <?php elseif ($problemaDuplicado): ?>
        // Exibir modal de problema duplicado
        document.getElementById("modalDuplicado").style.display = "flex";
    <?php endif; ?>
</script>

<script>
    document.getElementById('evento_id').addEventListener('change', function() {
        var eventoSelect = this;
        var data = eventoSelect.options[eventoSelect.selectedIndex].getAttribute('data-data');
        
        // Preencher o campo 'data' com a data associada ao evento selecionado
        if (data) {
            document.getElementById('data').value = data;
        }
    });
</script>

</body>
</html>
