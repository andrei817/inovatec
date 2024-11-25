<?php
// Conexão com o banco de dados
include("php/Config.php");

function buscarRelatorioStaff() {
    global $conn;

    $sql = "SELECT eventos.id AS evento_id, eventos.nome AS evento_nome, eventos.data AS evento_data, 
               staffs_eventos.id AS staff_id, staffs_eventos.nome AS staff_nome, staffs_eventos.email AS staff_email
        FROM evento_staff
        JOIN eventos ON evento_staff.evento_id = eventos.id
        JOIN staffs_eventos ON evento_staff.staff_id = staffs_eventos.id
        ORDER BY eventos.data DESC";


    $result = $conn->query($sql);

    if ($result === false) {
        // Se a consulta falhar, exibe o erro do MySQL
        die("Erro na consulta: " . $conn->error);
    }

    // Verifica se a consulta retornou resultados
    if ($result->num_rows > 0) {
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        return $events;  // Retorna os dados da consulta
    } else {
        return [];  // Retorna um array vazio se não houver resultados
    }
}

// Obter os eventos e staffs
$staffs = buscarRelatorioStaff();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Staff por Evento</title>
    <link rel="stylesheet" href="colaboradores.css">
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

    <h1>Relatório de Staff por Evento</h1>
    <a href="associar staff.php" class="button no-print">Associar Staff</a>
    <button class="button no-print" onclick="window.print()">Gerar Relatório</button>

    <table id="eventTable" class="table">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Data</th>
                <th>Nome do Staff</th>
                <th>Email do Staff</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($staffs)) {
                foreach ($staffs as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['evento_nome']) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($row['evento_data'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhum staff associado encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Função para filtro de pesquisa
function filterEvents() {
    let input = document.getElementById('eventSearch');
    let filter = input.value.toUpperCase();
    let table = document.getElementById('eventTable');
    let tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td')[0]; // Filtra pela primeira coluna (evento)
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

</body>
</html>
