<?php
// Conexão com o banco de dados
include('php/Config.php');

$cadastroSucesso = false;
$staffDuplicado = false;  // Variável para verificar duplicidade

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura dos dados do formulário
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $cargo_id = mysqli_real_escape_string($conn, $_POST['cargo_id']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Verificar se o staff já existe (mesmo email ou telefone)
    $sqlCheck = "SELECT id FROM staffs_eventos WHERE email = '$email' OR telefone = '$telefone'";
    $checkResult = $conn->query($sqlCheck);

    if ($checkResult->num_rows > 0) {
        // Se o staff já existe, define a variável para exibir o modal de erro
        $staffDuplicado = true;
    } else {
        // Iniciar uma transação para garantir a consistência
        $conn->begin_transaction();

        try {
            // Inserir na tabela `staffs_eventos`
            $sqlStaff = "INSERT INTO staffs_eventos (nome, telefone, email) 
                         VALUES ('$nome', '$telefone', '$email')";
            if (!$conn->query($sqlStaff)) {
                throw new Exception("Erro ao cadastrar staff: " . $conn->error);
            }

            // Obter o ID do staff recém-cadastrado
            $staff_id = $conn->insert_id;

            // Inserir na tabela intermediária `staff_cargo`
            $sqlStaffCargo = "INSERT INTO staff_cargo (staff_id, cargo_id) 
                              VALUES ('$staff_id', '$cargo_id')";
            if (!$conn->query($sqlStaffCargo)) {
                throw new Exception("Erro ao associar cargo ao staff: " . $conn->error);
            }

            // Confirmar a transação
            $conn->commit();
            $cadastroSucesso = true;
        } catch (Exception $e) {
            // Reverter a transação em caso de erro
            $conn->rollback();
            echo "Erro: " . $e->getMessage();
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Cadastro do Staff</title>
    <link rel="stylesheet" href="Staff.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
    <h1>Cadastrar Staff</h1>
   <a href="lista de staff.php" class="close-btn-staff">&times;</a>
 
<form method="POST" action="">

     <div class="input-group"> 
    <label for="nome">Nome do Staff:</label>
    <input type="text" id="nome" name="nome" required>
     </div>

     <div class="input-group">
    <label for="cargo">Cargo do Staff:</label>
    <select name="cargo_id" id="cargo_id" required>
        <option> Selecione o Cargo </option>
            <?php
            // Conexão com o banco de dados
            include("php/Config.php");

            // Buscar os cargos
            $sql = "SELECT id, nome FROM cargos";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum cargo encontrado</option>";
            }
            $conn->close();
            ?>
        </select><br>

     </div>

     <div class="input-group">
    <label for="telefone">Telefone:</label>
    <input type="tel" id="telefone" name="telefone" class="inputUser">
     </div>

     <div class="input-group">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" class="inputUser">
    </div>

    <button type="submit" class="login-btn-staff">Cadastrar</button>
    <a href="lista de staff.php"><button type="button" class="Cancel-btn-staff">Cancelar</button></a>

   
</form>

<!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal-correto">
    <div class="modal-content-correto"> 
        <span class="icon-close" onclick="fecharModal()">&times;</span>
        <h1>Staff Cadastrado com Sucesso!</h1>
        <img src="correto.png" class="correto-img">
    </div>
</div>


<!-- Modal de Staff Duplicado -->
<div id="modalDuplicado" class="modal-erro" style="display: none;">
    <div class="modal-content-erro">
        <span class="close-erro" onclick="fecharModalDuplicado('modalDuplicado')">&times;</span>
        <h1>Erro: Este Staff Já Está Cadastrado!</h1>
        <p>Este staff já foi registrado no sistema com o mesmo e-mail ou telefone.</p>
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
        window.location.href = "lista de staff.php";  // Substitua com o URL da página desejada
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        //setTimeout(function() {
            //fecharModal();           // Fecha o modal
            //redirecionarParaPagina();  // Redireciona para outra página após 3 segundos
        //}, 3000); // Fecha automaticamente após 3 segundos
        <?php elseif ($staffDuplicado): ?>
        // Exibir modal de staff duplicado
        document.getElementById("modalDuplicado").style.display = "flex";
    <?php endif; ?>
</script>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script> $('#telefone').mask('(00) 00000-0000'); </script>


</body>
</html>



