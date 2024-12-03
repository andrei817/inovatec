<?php
include("php/Config.php");

$cadastroSucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['staff_id']) && isset($_POST['evento_id']) && !empty($_POST['staff_id']) && !empty($_POST['evento_id'])) {
        $staff_id = intval($_POST['staff_id']);
        $evento_id = intval($_POST['evento_id']);
        
        // Inserir dados na tabela evento_staff
        $sql = "INSERT INTO evento_staff (staff_id, evento_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $staff_id, $evento_id);
        
        if ($stmt->execute()) {
            // Se a execução for bem-sucedida, defina como sucesso
            $cadastroSucesso = true;
        } else {
            echo "Erro ao associar staff ao evento: " . $stmt->error;
        }
    } else {
        echo "Dados não fornecidos corretamente!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>SGE - Associar Staff</title>
</head>
<body>
    
<?php
// Inclua sua configuração de conexão com o banco de dados
include("php/Config.php");

// Função para buscar os staffs
function obterStaffs() {
    global $conn;
    $sql = "SELECT id, nome FROM staffs_eventos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $staffs = [];
        while ($row = $result->fetch_assoc()) {
            $staffs[] = $row;
        }
        return $staffs;
    }
    return [];
}

// Função para buscar os eventos
function obterEventos() {
    global $conn;
    $sql = "SELECT id, nome FROM eventos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $eventos = [];
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        return $eventos;
    }
    return [];
}

// Obter os staffs e eventos
$staffs = obterStaffs();
$eventos = obterEventos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="associar staff.css">
    <title>Document</title>
</head>
<body>
    
<div id="header"></div> <!-- Div onde o menu será injetado -->

<script>
  fetch('/menu principal.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<div class="content">
  <!-- Conteúdo da página -->
</div>

<!-- script do sidebar -->
<script> 

// Função para abrir a sidebar
function abrirSidebar() {
  document.getElementById("mySidebar").style.width = "310px";
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

// Função para abrir a sidebar
function abrirSidebar() {
  // Se for um dispositivo móvel, ocupa 100% da tela; caso contrário, 250px
  if (window.innerWidth <= 768) {
      document.getElementById("mySidebar").style.width = "100%";
  } else {
      document.getElementById("mySidebar").style.width = "310px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}

    </script>



<section class="agenda-evento">
     <div class="conteudo">

     <section class="login-staff"> 

     <div class="login-box-staff">
         
     <h2> Associar Staff </h2>
     <a href="colaboradores.php" class="close-btn-staff">&times;</a>
<form action="" method="post">

     <div class="input-group">
    <label for="staff_id">Staff:</label>
    <select name="staff_id" id="staff_id">
        <?php
        // Gerar as opções de staff dinamicamente
        foreach ($staffs as $staff) {
            echo "<option value='{$staff['id']}'>{$staff['nome']}</option>";
        }
        ?>
    </select>
     </div>

     <div class="input-group">
    <label for="evento_id">Evento:</label>
    <select name="evento_id" id="evento_id">
        <?php
        // Gerar as opções de eventos dinamicamente
        foreach ($eventos as $evento) {
            echo "<option value='{$evento['id']}'>{$evento['nome']}</option>";
        }
        ?>
    </select>
     </div>


    <button type="submit" class="btn-login">Associar</button>
    <a href="colaboradores.php"><button type="button" class="btn-Close">Cancelar</button></a>
</form>

 <!-- Modal de Sucesso -->
 <div id="modalSucesso" class="modal-de-sucesso">
    <div class="modal-content-sucesso">
         <span class="close-icon-correto" onclick="fecharModal()">&times;</span>
        <h2>Staff Associado com Sucesso!</h2>
        <img src="correto.png" class="correto-img">
       
    </div>
</div>

<script>
    // Função para fechar o modal
    function fecharModal() {
        document.getElementById("modalSucesso").style.display = "none";
    }

    // Exibe o modal se o cadastro foi bem-sucedido
    <?php if ($cadastroSucesso): ?>
        document.getElementById("modalSucesso").style.display = "flex";
        setTimeout(fecharModal, 3000); // Fecha automaticamente após 3 segundos
    <?php endif; ?>
</script>



    </section> 
     </div>
</body>
</html>
