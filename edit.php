<?php
include ('php/Config.php'); // Inclui a conexão com o banco de dados


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM produtor WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $produtor = mysqli_fetch_assoc($result);

    if (!$produtor) {
        die("Produtor não encontrado!");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = md5($_POST['senha']);  // Criptografar a senha com MD5
    $cpf = $_POST['cpf'];

    $sql = "UPDATE produtor SET nome = '$nome', email = '$email', telefone = '$telefone', senha = '$senha', cpf = '$cpf' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: listar produtores.php");
        exit;
    } else {
        echo "Erro ao atualizar o produtor: " . mysqli_error($conn);
    }

  
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit.css">
    <title>Editar Produtor</title>
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

        <section class="agenda"> 
        <section class="login-section"> 



    <div class="login-box"> 



    <h1>Editar Produtor</h1>

    <a href="listar produtores.php" class="close-btn-edit">&times;</a>
    <form method="POST">

       <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produtor['nome']); ?>" required><br>
       </div>

       <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($produtor['email']); ?>" required><br>
       </div>

       <div class="input-group">
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($produtor['telefone']); ?>"><br>
       </div>

       <div class="input-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" value="<?php echo htmlspecialchars($produtor['senha']); ?>"><br>
       </div>

       <div class="input-group">
        <label for="cpf">CPF:</label>
        <input type=numeric id="cpf" name="cpf" value="<?php echo htmlspecialchars($produtor['cpf']); ?>"><br>
       </div>


        <button type="submit" class="login-btn">Salvar</button>
        
        
    </form>


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
      document.getElementById("mySidebar").style.width = "250px";
  }
}

// Função para fechar a sidebar
function fecharSidebar() {
  document.getElementById("mySidebar").style.width = "0";
}
</script>

       
</body>
</html>
