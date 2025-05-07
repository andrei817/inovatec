<?php
include("php/Config.php");

// Verificar se o parâmetro "edit" está definido para exibir os dados
if (isset($_GET['edit'])) {
    $staffId = (int)$_GET['edit'];

    // Consulta para obter os dados do staff e do cargo atual utilizando prepared statement
    $sql = "
        SELECT s.id, s.nome, s.telefone, s.email, c.id AS cargo_id, c.nome AS cargo
        FROM staffs_eventos s
        LEFT JOIN staff_cargo sc ON s.id = sc.staff_id
        LEFT JOIN cargos c ON sc.cargo_id = c.id
        WHERE s.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staffId); // Protege contra SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
    } else {
        die("Staff não encontrado.");
    }

    $stmt->close();
} else {
    die("ID de staff não fornecido.");
}

// Verificar se os dados foram enviados para atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar os dados do formulário com validação e proteção contra SQL Injection
    $staffId = (int)$_POST['staff_id'];
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $cargoId = (int)$_POST['cargo'];

    // Validar email com função nativa do PHP
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido.");
    }

    // Atualizar os dados na tabela staffs_eventos com prepared statement
    $sqlStaff = "
        UPDATE staffs_eventos 
        SET nome = ?, telefone = ?, email = ? 
        WHERE id = ?
    ";
    $stmtStaff = $conn->prepare($sqlStaff);
    $stmtStaff->bind_param("sssi", $nome, $telefone, $email, $staffId); // Protege contra SQL Injection

    // Atualizar o cargo na tabela intermediária staff_cargo com prepared statement
    $sqlCargo = "
        UPDATE staff_cargo 
        SET cargo_id = ? 
        WHERE staff_id = ?
    ";
    $stmtCargo = $conn->prepare($sqlCargo);
    $stmtCargo->bind_param("ii", $cargoId, $staffId); // Protege contra SQL Injection

    // Executar as atualizações
    if ($stmtStaff->execute() && $stmtCargo->execute()) {
        echo "Staff atualizado com sucesso";
        header('Location: lista de staff.php');
        exit();
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }

    // Fechar as declarações preparadas
    $stmtStaff->close();
    $stmtCargo->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit staff.css">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Editar Staff</title>
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
// Função para abrir a sidebar
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


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script> $('#telefone').mask('(00) 00000-0000'); </script>


<div class="agenda-evento">
    <div class="conteudo">
    

    <section class="login-section">

    <div class="login-box">

    <h1>Editar Staff</h1>
    <a href="lista de staff.php" class="close-btn-staff">&times;</a>
    <form action="" method="POST">
        <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">

        <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $staff['nome']; ?>" required>
        </div>

<div class="input-group">
        <label for="cargo">Cargo:</label>
        <select name="cargo" required>
            <!-- Carregar o cargo atual como selecionado -->
            <option value="<?php echo $staff['cargo_id']; ?>" selected><?php echo $staff['cargo']; ?></option>
            <?php
            // Consultar todos os cargos disponíveis
            $sqlCargos = "SELECT id, nome FROM cargos";
            $resultCargos = $conn->query($sqlCargos);

            if ($resultCargos->num_rows > 0) {
                while ($cargo = $resultCargos->fetch_assoc()) {
                    // Evitar duplicar o cargo já selecionado
                    if ($cargo['id'] != $staff['cargo_id']) {
                        echo "<option value='{$cargo['id']}'>{$cargo['nome']}</option>";
                    }
                }
            }
            ?>
        </select>
</div>

        <div class="input-group">
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" value="<?php echo $staff['telefone']; ?>" required>
        </div>

        <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $staff['email']; ?>" required>
        </div>
        <button type="submit" class="login-btn-staff" >Atualizar</button>
        <a class="a" href="lista de staff.php"><button type="button" class="Cancel-btn-staff">Cancelar</button></a>
    </form>

    </div>
    </section>
        
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script>
  $(document).ready(function() {
    $('#telefone').mask('(00) 00000-0000'); // Aplica a máscara de telefone
  });
</script>


</body>
</html>
