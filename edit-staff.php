<?php
// Inclua seu arquivo de configuração
include("php/Config.php");

// Verifique se o parâmetro 'edit' está presente na URL
if(isset($_GET['edit'])) {
    $id = $_GET['edit']; // Pega o ID do staff a ser editado

    // Consulta SQL para buscar as informações do staff com o ID fornecido
    $sql = "SELECT s.id, s.nome, s.cargo_id, s.telefone, s.email, c.nome AS cargo
            FROM staffs_eventos s
            INNER JOIN cargos c ON s.cargo_id = c.id
            WHERE s.id = ?";
    
    // Preparando a consulta para evitar SQL Injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // 'i' indica que o parâmetro é um inteiro
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o staff foi encontrado
        if ($result->num_rows > 0) {
            // Pega os dados do staff
            $row = $result->fetch_assoc();
            $nome = $row['nome'];
            $telefone = $row['telefone'];
            $email = $row['email'];
            $cargo_id = $row['cargo_id'];
        } else {
            // Caso o staff não seja encontrado
            echo "Staff não encontrado.";
            exit;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        // Caso haja erro na consulta SQL
        echo "Erro na consulta SQL.";
        exit;
    }
} else {
    echo "ID não fornecido.";
    exit;
}

// Verifique se o formulário foi submetido para salvar as edições
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cargo_id = $_POST['cargo'];

    // Atualiza os dados do staff no banco de dados
    $updateSql = "UPDATE staffs_eventos SET nome = ?, telefone = ?, email = ?, cargo_id = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("ssssi", $nome, $telefone, $email, $cargo_id, $id);
        if ($stmt->execute()) {
            echo "Staff atualizado com sucesso!";
            header("Location: lista de staff.php"); // Redireciona para a lista após a edição
            exit;
        } else {
            echo "Erro ao atualizar o staff.";
        }
        $stmt->close();
    } else {
        echo "Erro na consulta SQL de atualização.";
    }
}

// Consulta para pegar os cargos disponíveis
$cargoQuery = "SELECT id, nome FROM cargos";
$cargosResult = $conn->query($cargoQuery);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Editar Staff</title>
    <link rel="stylesheet" href="edit staff.css">
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
    <h2>Editar Staff</h2>
    <a href="lista de staff.php" class="close-btn-staff">&times;</a>

    <form action="edit-staff.php?edit=<?php echo $id; ?>" method="POST">

    <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>
    </div>

      <div class="input-group">
        <label for="cargo">Cargo:</label>
        <select id="cargo" name="cargo" required>
            <?php
            // Exibe os cargos disponíveis para seleção
            while ($cargo = $cargosResult->fetch_assoc()) {
                $selected = ($cargo['id'] == $cargo_id) ? 'selected' : '';
                echo "<option value='" . $cargo['id'] . "' $selected>" . $cargo['nome'] . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="input-group">
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo $telefone; ?>" required>
    </div>

    <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
    </div>

  
        <button type="submit" class="login-btn-staff">Atualizar</button>
       <a class="a" href="lista de staff.php"><button type="button" class="Cancel-btn-staff">Cancelar</a></button>

    </form>
    </div>

    </div>

  
        </section>
</body>
</html>

<?php
// Fechar a conexão com o banco
$conn->close();
?>
