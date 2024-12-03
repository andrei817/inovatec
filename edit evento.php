<?php
include("php/Config.php");

// Verificar se o evento está sendo editado
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    // Buscar dados do evento para editar
    $sql = "SELECT * FROM eventos WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    // Verificar se o formulário de edição foi enviado
    if (isset($_POST['atualizar'])) {
        // Coletar os dados do formulário
        $nome = $_POST['nome'];
        $data = $_POST['data'];
        $local = $_POST['local'];
        $hora = $_POST['hora'];
        $lotacao = $_POST['lotacao'];
        $duracao = $_POST['duracao'];
        $descricao = $_POST['descricao'];
        $tema_id = $_POST['tema_id'];
        $objetivo_id = $_POST['objetivo_id'];
        $buffet_id = $_POST['buffet_id'];

        // Atualizar o evento no banco de dados
        $sqlUpdate = "UPDATE eventos SET 
                      nome = '$nome', 
                      data = '$data', 
                      local = '$local', 
                      hora = '$hora', 
                      lotacao = '$lotacao', 
                      duracao = '$duracao', 
                      descricao = '$descricao',
                      tema_id = '$tema_id',
                      objetivo_id = '$objetivo_id',
                      buffet_id = '$buffet_id'
                      WHERE id = $id";

        if ($conn->query($sqlUpdate) === TRUE) {
            echo "Evento atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar evento: " . $conn->error;
        }
    }
} else {
    echo "Evento não encontrado!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="evento.css">
    <title>SGE- Editar Evento</title>
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

<!-- Conteúdo principal -->
<div class="content">
    <section class="agenda">
        <!-- Formulário de Cadastro de Evento -->
        <div class="form-container">
<h2>Editar Evento</h2>
<a href="lista de eventos.php" class="close-btn-evento">&times;</a>
<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <div class="input-group">
    <label for="nome">Nome do Evento:</label>
    <input type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" required>
    </div>

    <div class="input-group">
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" value="<?php echo $row['data']; ?>" required>
    </div>

    <div class="input-group">
    <label for="local">Local:</label>
    <input type="text" id="local" name="local" value="<?php echo $row['local']; ?>">
    </div>

    <div class="input-group">
    <label for="hora">Hora:</label>
    <input type="time" id="hora" name="hora" value="<?php echo $row['hora']; ?>">
    </div>

    <div class="input-group">
    <label for="lotacao">Lotação:</label>
    <input type="number" id="lotacao" name="lotacao" value="<?php echo $row['lotacao']; ?>">
    </div>

    <div class="input-group">
    <label for="duracao">Duração:</label>
    <input type="number" id="duracao" name="duracao" value="<?php echo $row['duracao']; ?>">
    </div>

    <div class="input-group">
    <label for="inicio">Início:</label>
    <input type="time" id="inicio" name="inicio" value="<?php echo $row['inicio']; ?>">
    </div>

    <div class="input-group">
    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao" rows="5"><?php echo $row['descricao']; ?></textarea>
    </div>
    
    <!-- Adicionando os novos campos -->
    <div class="input-group">
    <label for="tema_id">Tema:</label>
    <select name="tema_id" id="tema_id">
        <!-- Aqui você deve carregar os temas disponíveis do banco de dados -->
        <?php
        $tema_sql = "SELECT * FROM tema";
        $tema_result = $conn->query($tema_sql);
        while ($tema = $tema_result->fetch_assoc()) {
            echo "<option value='" . $tema['id'] . "' " . ($tema['id'] == $row['tema_id'] ? 'selected' : '') . ">" . $tema['nome'] . "</option>";
        }
        ?>
    </select>
    </div>

    <div class="input-group">
    <label for="objetivo_id">Objetivo:</label>
    <select name="objetivo_id" id="objetivo_id">
        <!-- Aqui você deve carregar os objetivos disponíveis do banco de dados -->
        <?php
        $objetivo_sql = "SELECT * FROM objetivo";
        $objetivo_result = $conn->query($objetivo_sql);
        while ($objetivo = $objetivo_result->fetch_assoc()) {
            echo "<option value='" . $objetivo['id'] . "' " . ($objetivo['id'] == $row['objetivo_id'] ? 'selected' : '') . ">" . $objetivo['descricao'] . "</option>";
        }
        ?>
    </select>
    </div>

    <div class="input-group">
    <label for="buffet_id">Buffet:</label>
    <select name="buffet_id" id="buffet_id">
        <!-- Aqui você deve carregar os buffets disponíveis do banco de dados -->
        <?php
        $buffet_sql = "SELECT * FROM buffet";
        $buffet_result = $conn->query($buffet_sql);
        while ($buffet = $buffet_result->fetch_assoc()) {
            echo "<option value='" . $buffet['id'] . "' " . ($buffet['id'] == $row['buffet_id'] ? 'selected' : '') . ">" . $buffet['nome'] . "</option>";
        }
        ?>
    </select>
    </div>

    <button type="submit" class="login-btn-evento">Atualizar</button>
                <a href="lista de eventos.php"><button type="button" class="Cancel-btn-evento">Cancelar</button></a>
            </form>
        </div>

    </section>
</div>


</div>

</body>
</html>
