<?php
session_start();
include("php/Config.php");

// Verificar se o produtor está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Obter o ID do produtor logado
$produtor_id = $_SESSION['id'];

// Consultar informações do produtor logado
$sql = "SELECT nome, email FROM produtor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produtor_id);
$stmt->execute();
$result = $stmt->get_result();
$produtor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizar perfil
    if (isset($_POST['update_profile'])) {
        $novo_nome = trim($_POST['nome']);
        $novo_email = trim($_POST['email']);

        // Validar os dados
        if (!empty($novo_nome) && !empty($novo_email) && filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            // Atualizar no banco de dados
            $update_sql = "UPDATE produtor SET nome = ?, email = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssi", $novo_nome, $novo_email, $produtor_id);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                $_SESSION['msg'] = "Perfil atualizado com sucesso!";
                header('Location: editar_perfil.php');
                exit;
            } else {
                $error = "Nenhuma alteração foi feita.";
            }
        } else {
            $error = "Por favor, insira um nome válido e um email válido.";
        }
    }

    // Alterar senha
    if (isset($_POST['change_password'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Validar campos
        if (!empty($senha_atual) && !empty($nova_senha) && !empty($confirmar_senha)) {
            if ($nova_senha === $confirmar_senha) {
                // Consultar senha atual no banco
                $senha_sql = "SELECT senha FROM produtor WHERE id = ?";
                $senha_stmt = $conn->prepare($senha_sql);
                $senha_stmt->bind_param("i", $produtor_id);
                $senha_stmt->execute();
                $senha_result = $senha_stmt->get_result();
                $senha_db = $senha_result->fetch_assoc()['senha'];

                if (md5($senha_atual) === $senha_db) {
                    // Atualizar senha para usar password_hash
                    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $update_senha_sql = "UPDATE produtor SET senha = ? WHERE id = ?";
                    $update_senha_stmt = $conn->prepare($update_senha_sql);
                    $update_senha_stmt->bind_param("si", $nova_senha_hash, $produtor_id);
                    $update_senha_stmt->execute();
                
                    if ($update_senha_stmt->affected_rows > 0) {
                        $_SESSION['msg'] = "Senha alterada com sucesso!";
                        //header('Location: editar_perfil.php');
                        exit;
                    } else {
                        $error = "Erro ao alterar a senha. Tente novamente.";
                    }
                } else {
                    $error = "Senha atual incorreta.";
                }
            } else {
                $error = "As novas senhas não coincidem.";
            }
        } else {
            $error = "Por favor, preencha todos os campos de senha.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil do produtor.css">
    <title>Editar Perfil</title>
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


<section class="agenda"> 
    <section class="login-section"> 

    <div class="login-box">


    <h1>Editar Perfil</h1>
    <?php if (isset($_SESSION['msg'])): ?>
        <p style="color: green;"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>


    <form method="POST" action="">
        <h2>Atualizar Perfil</h2>
        
        <div class="input-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produtor['nome']); ?>" required><br>
        </div>

       <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($produtor['email']); ?>" required><br>
       </div>

        <button type="submit" name="update_profile" class="login-btn-alt">Salvar Alterações</button>
    </form>
</div>


<div class="login-box2">
    <form method="POST" action="">
        <h2>Alterar Senha</h2>

        <div class="input-group">
        <label for="senha_atual">Senha Atual:</label>
        <input type="password" id="senha_atual" name="senha_atual" required><br>
        </div>

        <div class="input-group">
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" required><br>
        </div>

        <div class="input-group">
        <label for="confirmar_senha">Confirmar Nova Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" required><br>
        </div>

        <button type="submit" name="change_password" class="login-btn2">Alterar Senha</button> 
      
    </form>
  <a href="ambiente.php"> Voltar ao Ambiente</a>
    

</div>

</section>
    
</body>
</html>