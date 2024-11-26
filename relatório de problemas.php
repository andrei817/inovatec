<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Problemas por Eventos</title>
    
    <link rel="stylesheet" href="relatório de probelmas.css">
    <link rel="stylesheet" href="print-relatório.css">
</head>
<body>

<div class="no-print" id="header"></div> <!-- Div onde o menu será injetado -->

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

<section class="agenda-relatorio">
     <div class="conteudo-relatorio">

    <h1>Relatório de Problemas por Eventos</h1>
    <a href="reporte de problemas.php" class="button no-print" >Reportar problemas</a>
    
    
     
    <table>
        <tr>
            <th class="id-column">ID</th>
            <th class="id-column">Evento</th>
            <th>Evento</th>
            <th class="problem">Problemas</th>
            <th>Data do Registro</th>
            <th>Contato</th> <!-- Nova coluna -->
            <th>Ações</th>
            
        </tr>
        <?php
// Conexão com o banco de dados
include("php/Config.php");

// Realizar consulta SQL para obter os problemas registrados
$sql = "SELECT p.evento_id, p.descricao_problema, p.data_evento, p.contato, e.nome AS nome_evento
        FROM problemas_evento p
        JOIN eventos e ON p.evento_id = e.id";

// Realizar a consulta e verificar se ocorreu algum erro
$resultado = $conn->query($sql);

// Verificar se a consulta foi bem-sucedida
if ($resultado === false) {
    // Exibir mensagem de erro, caso a consulta falhe
    echo "Erro na consulta: " . $conn->error;
} else {
    // Verificar se há registros
    if ($resultado->num_rows > 0) {
        // Exibir os resultados
        while($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td class= id-column>" . $row['evento_id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nome_evento']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descricao_problema']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['data_evento'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['contato']) . "</td>";
            echo "<td class='action'>
            <button class='no-print print-button' onclick='window.print()'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-printer' viewBox='0 0 16 16'>
                    <path d='M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1'/>
                    <path d='M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1'/>
                </svg>
                
            </button>
          </td>";
    echo "</tr>";
            
        }
    } else {
        echo "Nenhum problema registrado.";
    }
}

// Fechar a conexão
$conn->close();
?>


    </table>
   
</body>
</html>

