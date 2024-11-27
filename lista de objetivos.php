<?php
include('php/Config.php');

// Defina o número de registros por página
$registros_por_pagina = 3;

// Determine a página atual
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_atual - 1) * $registros_por_pagina;

// Consulta para contar o total de registros
$sql_total = "SELECT COUNT(*) AS total FROM objetivo";
$resultado_total = mysqli_query($conn, $sql_total);
$total_registros = mysqli_fetch_assoc($resultado_total)['total'];

// Calcula o total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta com LIMIT para paginação
$sql = "SELECT * FROM objetivo LIMIT $inicio, $registros_por_pagina";
$resultado = mysqli_query($conn, $sql);
?>

j

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Objetivos de Eventos</title>
    <link rel="stylesheet" href="objtivo.css">
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




<section class="obj-evento">
     <div class="conteudo">
        <h1>OBJETIVOS</h1>
           <a href="objetivo.php" class="button">Adicionar Novo Objetivo</a>
    </div>

    <table class="tabela-eventos">
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th class="ações">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php

                // Verifica se existem resultados e exibe-os na tabela
                while($objetivo = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td class= id-column>" . $objetivo['id'] . "</td>";
                    echo "<td>" . $objetivo['nome'] . "</td>";
                    echo "<td>" . $objetivo['descricao'] . "</td>";

                    echo "<td class= action>
                        <a class= 'a' href='edit objetivo.php?id=" . $objetivo['id'] . "'><button class='btn-edit'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                        <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/> </a> </svg> </button></a>
                        
                        <a class= 'a' href='delete objetivo.php?excluir=" . $objetivo['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir?\")'><button class='btn-delete'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                        <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                      </svg> </a> </button></a>
                      </td>";
                echo "</tr>";
                    
                    
                }

            ?>             
        </tbody>
    </table>

    <!-- Navegação da Paginação -->
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i' class='" . ($pagina_atual == $i ? "ativo" : "") . "'>$i</a>";
        }
        ?>
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
