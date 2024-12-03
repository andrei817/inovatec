<?php
include("php/Config.php");

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebendo e sanitizando os dados do formulário
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nova_senha = isset($_POST['nova_senha']) ? trim($_POST['nova_senha']) : '';

    // Validando os campos
    if (empty($email) || empty($nova_senha)) {
        die("Por favor, preencha todos os campos.");
    }

    // Validando o formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Formato de e-mail inválido.");
    }

    // Conexão com o banco de dados
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Verificar se o e-mail existe no banco de dados
    $sql = "SELECT id FROM produtor WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        // Resposta genérica para evitar expor informações
        die("Não foi possível redefinir a senha. Verifique os dados e tente novamente.");
    }

    // Criptografando a nova senha com MD5
    $hashed_password = md5($nova_senha);

    // Atualizando a senha no banco de dados
    $update_sql = "UPDATE produtor SET senha = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("Erro ao preparar a consulta de atualização: " . $conn->error);
    }

    $update_stmt->bind_param("ss", $hashed_password, $email);
    $update_stmt->execute();

    // Verificando se a senha foi atualizada
    if ($update_stmt->affected_rows > 0) {
        echo "Senha redefinida com sucesso!";
    } else {
        echo "Erro ao redefinir a senha. Tente novamente mais tarde.";
    }

    

    // Fechando as consultas
    $update_stmt->close();
    $stmt->close();
    $conn->close();
} else {
    //echo "Acesso inválido.";
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="esquecir_senha.css">
</head>

<body>



<header>
     
     <div class="logo-foto"> 
        <img src="SGE logo.png"width=80% height="100%">
        
    <div class="header-content"> 
   <h1> S.G.E.</h1> 
   <p> Sistema de Gestão de Eventos</p>

   </div>   
       </div>

   

<div class="logo">

    <img src="eventos.png"width=103% height="100%">
   
   </div>
   

       <nav> 
           
           <ul> 
              
               <li> <a href="index.php"> Home</a></li>  
               <li> <a href= "ajuda 1.php">Ajuda</a></li>
               <li> <a href= "Sobre 1.php">Sobre</a></li>
               <li> <a onclick="abrirPopUp()"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
               <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
           </svg></a></li>
       </ul>
   </nav>
</header>
      
   <section class="agenda"> 

   <section class="redefinir-section">

   <div class="esquecir-box">

   <a href="index.php" class="btn-closs">&times;</a>
    <h2 class="legend">Redefinir Senha</h2>
    <form action="" method="POST">

        <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        </div>

        <div class="input-group">
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" required>
        </div>

        <button type="submit" class="redefinir-btn">Redefinir Senha</button>

    <!-- Modal de Sucesso -->
<div id="senhaRedefinidaModal" class="modal-redefinir">
  <div class="modal-content-redefinir">
    <span class="close-btn" onclick="fecharModal()">&times;</span>
    <h3>Senha Redefinida com Sucesso!</h3>
    <p>A senha foi atualizada corretamente</p>
    <button onclick="window.location.href='index.php';">Ir para Login</button>
  </div>
</div>

    </form>



   </div>
   </section>
   </section>
</body>
</html>


