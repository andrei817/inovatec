<?php
// cadastrar_produtor.php
 include ('php/Config.php');

 $cadastroSucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $remember = isset($_POST['remember']); // Verifica se o checkbox foi marcado

    // Query de inserção
    $sql = "INSERT INTO produtor (email, senha) 
            VALUES ('$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        //echo "Produtor cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Verifique as credenciais
    if ($username === $usuario_correto && $password === $senha_correta) {
         //Cria a sessão do usuário
        $_SESSION['username'] = $username;

        // Se "Manter-me conectado" estiver marcado, define um cookie
        if ($remember) {
            setcookie('username', $username, time() + (86400 * 365), "/"); // 365 dias de duração
        }
    }

    $cadastroSucesso = true;
}

    // Redireciona para outra página se o cadastro for bem-sucedido
    // if ($cadastroSucesso) {
       // header("Location: Menu2.html"); // Redireciona para a página desejada
       // exit(); // Garante que o restante do script não seja executado
   // }


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGE - Tela de Login</title>
    <link rel="stylesheet" href="logar produtor.css">
    
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
                <li> <a href= "Sobre.html">Sobre</a></li>
                <li> <a href= "ajuda.html">Ajuda</a></li>
               <li>  <a href="logar produtor.php"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
  <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
</svg></a></li>
            </ul>
        </nav>
        
             
    
    </header>
    
    <section class="agenda"> 
    <section class="login-section"> 
        
       
        <div class="login-box"> 

            <h2> Fazer Login</h2>
           
            <a href="index.html" class="close-btn">&times;</a>
            <form action="" method="post">
               
              <div class="input-group">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" class="inputUser" required>
               </div> 

             <div class="input-group">
             <label for="senha">Senha:</label><br>
             <input type="password" id="senha" name="senha" >
             </div> 
                <button type="submit" class="login-btn">Entrar</button>
                <a href="?"><button type="button" class="Cancel-btn">Cancelar</button></a>
               
                <!-- Checkbox para "Manter-me conectado" -->
        <label class="remember">
            <input type="checkbox" name="remember" id="circular-checkbox"> Manter-me conectado
        </label>

        <p><a href="esqueci_senha.php">Esqueceu sua senha?</a></p>

            </form>

      <!-- Modal de Sucesso -->
<div id="modalSucesso" class="modal">
    <div class="modal-content">
         <span class="close-icon" onclick="fecharModal()">&times;</span>
        <h2>Produtor Cadastrado com Sucesso!</h2>
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
        setTimeout(function() {
            window.location.href = "Menu2.html"; // Redireciona após 3 segundos
        }, 3000);
    <?php endif; ?>
</script>

           
</section>

    </section>


    <script>
        function closeLogin() {
            
            document.querySelector('.login-container').style.display = 'none';
        }
       
    </script>

</body>
</html>
    
   
    
