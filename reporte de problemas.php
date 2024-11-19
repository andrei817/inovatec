<?php
// Conexão com o banco de dados
include ("php/Config.php");
// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome_evento = $conn->real_escape_string($_POST['nome_evento']);
    $data_evento = $conn->real_escape_string($_POST['data_evento']);
    $descricao_problema = $conn->real_escape_string($_POST['descricao_problema']);
    $contato = $conn->real_escape_string($_POST['contato']);

    // Inserir dados no banco de dados
    $sql = "INSERT INTO problemas_evento (nome_evento, data_evento, descricao_problema, contato)
            VALUES ('$nome_evento', '$data_evento', '$descricao_problema', '$contato')";

    if ($conn->query($sql) === TRUE) {
        echo "Problema reportado com sucesso!";
    } else {
        echo "Erro ao reportar o problema: " . $conn->error;
    }
}

// Fechar conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reporte de problemas.css">
    <title>Reportar Problema</title>
    <style>
        
        .form {
            height: 510px;
            max-width: 800px;
            padding: 20px;
            background-color: #5214cb;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

            .close_login-btn {
            position: relative; /* Posiciona o botão de fechar */
            top: -80px; /* Distância do topo */
            right: -390px; /* Distância da direita */
            font-size: 35px; /* Tamanho da fonte */
            color: rgb(243, 243, 243); /* Cor do botão */
            cursor: pointer; /* Muda o cursor ao passar o mouse */
            z-index: 1000;
            text-decoration: none; 
        }
        
        .input-group label {
    display: block;
    margin-bottom: 3px;
    border-radius: 10px;
    color: rgb(255, 255, 255);
    position: sticky;
    margin-top: 2px;

}

.input-group input {
    width: 40%;
    padding: 5px;
    border: 1px solid rgb(143, 10, 10) #f80000;
    border-radius: 20px; 
    font-size: 16px;
}

        .login-reportar {
         position: relative; 
        right: -190px; 
        width: 20%;
        padding: 10px; 
        background-color: #0db400;
        color: #fff;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        cursor: pointer;
        border-bottom: 4px solid black;
        box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.5);
        box-shadow: 0px 5px 0px , #0db400  /* Sombra superior para profundidade */
                    0px 7px 15px rgba(0, 0, 0, 0.2);  /* Sombra geral para efeito 3D */
        transition: all 0.3s ease-in-out;  /* Suaviza a animação */

        }

        .login-btn-reportar:active{

        box-shadow: 0px 2px 0px ,  #0db400 /* Sombra ao pressionar, simulando o botão "afundar" */
                    0px 4px 10px rgba(0, 0, 0, 0.2);
        transform: translateY(3px);        /* Move o botão para baixo */
}

        .login-reportar:hover {
            background-color: #34e028;
                }

                .Cancel-btn {
    position: relative; 
    right: -200px; 
    width: 20%;
    padding: 10px; 
    background-color: rgba(206, 35, 35, 0.89);
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    border-bottom: 4px solid black;
    box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.5);
    box-shadow: 0px 5px 0px , #cf1111  /* Sombra superior para profundidade */
                0px 7px 15px rgba(0, 0, 0, 0.2);  /* Sombra geral para efeito 3D */
    transition: all 0.3s ease-in-out;  /* Suaviza a animação */


}
.Cancel-btn:active{

    box-shadow: 0px 2px 0px ,  #bd1010 /* Sombra ao pressionar, simulando o botão "afundar" */
                0px 4px 10px rgba(0, 0, 0, 0.2);
    transform: translateY(3px);        /* Move o botão para baixo */
}

.Cancel-btn:hover {
    background-color: #f53e3e;
}
    </style>
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
     document.getElementById("mySidebar").style.width = "410px";
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
         document.getElementById("mySidebar").style.width = "410px";
     }
   }
   
   // Função para fechar a sidebar
   function fecharSidebar() {
     document.getElementById("mySidebar").style.width = "0";
   }
   </script>

<section class="agenda">
        
    <div class="form">

    <h1>Reportar Problema de Evento</h1>
    <a href="Menu2.html" class="close_login-btn">&times;</a>

    <form action="processar_problema.php" method="POST">
        <div class="input-group">
        <label for="nome_evento">Nome do Evento:</label>
        <input type="text" id="nome_evento" name="nome_evento" required placeholder="Digite o nome do evento">
        </div>

        <div class="input-group">
        <label for="data_evento">Data do Evento:</label>
        <input type="date" id="data_evento" name="data_evento" required>
        </div>

        <div class="input-group">
        <label for="descricao_problema">Descrição do Problema:</label>
        <textarea id="descricao_problema" name="descricao_problema" rows="5" required placeholder="Descreva o problema aqui..."></textarea>
        </div>

        <div class="input-group">
        <label for="contato">Contato (E-mail ou Telefone):</label>
        <input type="text" id="contato" name="contato" required placeholder="Digite seu e-mail ou telefone">
        </div>

        <button type="submit" class="login-reportar"> Reportar</button>
        <a href="ambiente.php"><button type="button" class="Cancel-btn">Cancelar</button></a>
        
    </form>
    </div>
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

</body>
</html>



