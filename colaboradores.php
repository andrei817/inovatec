<?php
// Conexão com o banco de dados
include("php/Config.php");

// Defina o número de registros por página
$registros_por_pagina = 4;

// Verifica a página atual, padrão é 1
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Consulta para contar o total de registros
$total_registros_query = "SELECT COUNT(*) as total FROM evento_staff";
$total_resultado = $conn->query($total_registros_query);
$total_registros = $total_resultado->fetch_assoc()['total'];

// Calcula o total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta com LIMIT para buscar apenas os registros da página atual
$sql = "SELECT eventos.id AS evento_id, eventos.nome AS evento_nome, eventos.data AS evento_data, 
               staffs_eventos.id AS staff_id, staffs_eventos.nome AS staff_nome, staffs_eventos.email AS staff_email
        FROM evento_staff
        JOIN eventos ON evento_staff.evento_id = eventos.id
        JOIN staffs_eventos ON evento_staff.staff_id = staffs_eventos.id
        ORDER BY eventos.data DESC
        LIMIT $offset, $registros_por_pagina";

$result = $conn->query($sql);

// Verifica se a consulta retornou resultados
$staffs = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Staff por Evento</title>
    <link rel="stylesheet" href="colaboradores.css">
    <link rel="stylesheet" href="print-relatório.css">

    <style>
        
        /* Estilos para impressão */
        @media print {

            /* Ocultar elementos não necessários durante a impressão */
            .no-print {
                display: none;
            }

            /* Exibir a tabela na impressão */
            .printable-table {
              color: #000;
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .printable-table th, .printable-table td {
              color: #000;
                padding: 10px;
                font-size: 14px;
            }

            .printable-table th {
                background-color: #f1f1f1;
                font-weight: bold;
            }

            .print-button {
                display: none; /* Esconde o botão de imprimir durante a impressão */
            }

            h1 {
                font-size: 18px;
                text-align: center;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<div class="no-print" id="header"></div> <!-- Div onde o menu será injetado -->

<script>
  fetch('/menu principal.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header').innerHTML = data;
    })
    .catch(error => console.error('Erro ao carregar o menu:', error));
</script>

<script> 
   function abrirSidebar() {
    if (window.innerWidth <= 768) {
      document.getElementById("mySidebar").style.width = "100%";
    } else {
      document.getElementById("mySidebar").style.width = "310px";
    }
    // Adiciona a classe "aberto" à sidebar
    document.getElementById("mySidebar").classList.add("aberto");
  }

  // Função para fechar a sidebar
  function fecharSidebar() {
    document.getElementById("mySidebar").style.width = "0";
    // Remove a classe "aberto"
    document.getElementById("mySidebar").classList.remove("aberto");
  }

  // Adiciona o evento para fechar ao clicar fora da sidebar
  document.addEventListener('click', function (event) {
    const sidebar = document.getElementById("mySidebar");
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickOnButton = event.target.closest('.open-btn');

    // Fecha a sidebar se o clique não for nela nem no botão de abrir
    if (!isClickInsideSidebar && !isClickOnButton && sidebar.classList.contains("aberto")) {
      fecharSidebar();
    }
  });

  // Fecha a sidebar ao clicar nos links
  document.querySelectorAll('#mySidebar a').forEach(link => {
    link.addEventListener('click', fecharSidebar);
  });
   </script>


<script>
  // Função para mostrar/ocultar a lista suspensa do perfil
  function toggle() {
      var profileDropdownList = document.querySelector('.profile-dropdown-list');
      profileDropdownList.classList.toggle('active');
  }

  // Função para mostrar o modal de logout
  function showLogoutModal() {
      document.getElementById('logoutModal').style.display = 'flex';
  }

  // Função para fechar qualquer modal
  function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
  }

  // Função para confirmar o logout e mostrar o modal de agradecimento
  function confirmLogout() {
      closeModal('logoutModal'); // Fecha o modal de logout
      document.getElementById('thankYouModal').style.display = 'flex'; // Mostra o modal de agradecimento
      
      // Redireciona após alguns segundos (opcional)
      setTimeout(function() {
          window.location.href = 'index.php'; // Redireciona para a página inicial
      }, 2000); // Aguarda 2 segundos antes de redirecionar
  }
</script>



<section class="agenda-relatorio">
    <div class="conteudo-relatorio">
      <div class="nome-colaboradores">
        <h1>STAFF POR EVENTO</h1>
      </div>
        <a href="associar staff.php" class="button no-print">Associar Staff</a>
        <button id="generateReportBtn" class="no-print button" onclick="generateStaffReport()">Gerar Relatório</button>

    </div>

    <table id="eventTable" class="table">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Data</th>
                <th>Nome do Staff</th>
                <th>Email do Staff</th>
                <th class="no-print ações">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($staffs)) {
                foreach ($staffs as $row) {
                    echo "<tr id='row-" . $row['evento_id'] . "'>";
                    echo "<td>" . htmlspecialchars($row['evento_nome']) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($row['evento_data'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_email']) . "</td>";
                    echo "<td class='no-print action'>
  <button class='no-print print-button' title='Imprimir' onclick='printEventStaff(" . $row['evento_id'] . ")'>
    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-printer' viewBox='0 0 16 16'>
      <path d='M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1'/>
      <path d='M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1'/>
    </svg>
  </button>
</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhum staff associado encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Links de navegação para paginação -->
    <div class="no-print pagination" >
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i'";
            if ($pagina_atual == $i) echo " class='active'";
            echo ">$i</a> ";
        }
        ?>
    </div>
</section>

<script>

function printEventStaff(eventId) {
  // Seleciona todas as linhas de staff associadas ao evento
  var staffRows = document.querySelectorAll(`#eventTable tr[id^='row-${eventId}']`);

  // Cria uma nova tabela para exibir somente os staffs do evento
  var printTable = '<table class="printable-table" style="width: 100%; border-collapse: collapse;">';
  printTable += '<thead><tr><th>Evento</th><th>Data</th><th>Nome do Staff</th><th>Email do Staff</th></tr></thead>';
  printTable += '<tbody>';

  // Adiciona as linhas de staff à tabela de impressão
  staffRows.forEach(function(row) {
    printTable += row.outerHTML;
  });

  printTable += '</tbody></table>';

  // Cria o conteúdo da impressão
  var printContent = `
    <div id="printableContent">
      <h1 style="text-align: center; font-size: 24px;">Staff do Evento</h1>
      ${printTable}
    </div>
  `;

  // Cria um iframe temporário para impressão
  var iframe = document.createElement('iframe');
  iframe.style.position = 'absolute';
  iframe.style.width = '0px';
  iframe.style.height = '0px';
  iframe.style.border = 'none';
  document.body.appendChild(iframe);

  // Adiciona o conteúdo HTML ao iframe
  var iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(`
    <html>
      <head>
        <title>Impressão</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 20px;
          }
          h1 {
            color: #000;
            font-weight: 600; /* Título mais grosso e destacado */
            text-align: center; /* Centraliza o título */
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
          }
          th, td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
          }
          th {
            background-color: #000;
            font-weight: bold;
          }
          tr:nth-child(even) {
            background-color: #000;
          }

          /* Ocultar o botão de impressão e outros elementos durante a impressão */
          @media print {
            .no-print {
              display: none;
            }
          }
        </style>
      </head>
      <body>
        ${printContent}
      </body>
    </html>
  `);
  iframeDocument.close();

  // Dispara a impressão a partir do iframe
  iframe.contentWindow.focus();
  iframe.contentWindow.print();

  // Remove o iframe após a impressão
  iframe.remove();
}


</script>



<!-- Relatório Geral -->
<script> 
function generateStaffReport() {
  // Obter todos os eventos e seus staffs. Supondo que os dados estão disponíveis na página ou no banco de dados
  // Aqui você pode fazer uma consulta AJAX ou pegar os dados diretamente do DOM (dependendo da sua implementação)
  // Para este exemplo, vamos simular que temos as linhas de staff de todos os eventos na tabela com o ID "eventTable"

  // Criar a estrutura do relatório
  var reportTable = '<table class="printable-table" style="width: 100%; border-collapse: collapse;">';
  reportTable += '<thead><tr><th>Evento</th><th>Data</th><th>Nome do Staff</th><th>Email do Staff</th></tr></thead>';
  reportTable += '<tbody>';

  // Percorrer todos os eventos e staffs para preencher o relatório
  var allStaffRows = document.querySelectorAll('#eventTable tr'); // Obter todas as linhas da tabela de eventos
  allStaffRows.forEach(function(row) {
    var staffData = row.querySelectorAll('td'); // Supondo que as informações de staff estão nas células da linha
    if (staffData.length > 0) {
      var eventName = staffData[0].innerText; // Nome do evento
      var eventDate = staffData[1].innerText; // Data do evento
      var staffName = staffData[2].innerText; // Nome do staff
      var staffEmail = staffData[3].innerText; // Email do staff

      // Adiciona a linha ao relatório
      reportTable += `<tr>
                        <td>${eventName}</td>
                        <td>${eventDate}</td>
                        <td>${staffName}</td>
                        <td>${staffEmail}</td>
                      </tr>`;
    }
  });

  reportTable += '</tbody></table>';

  // Cria o conteúdo da impressão para o relatório completo
  var printContent = `
    <div id="printableContent">
      <h1 style="text-align: center; font-size: 24px;">Relatório Geral de Staffs</h1>
      ${reportTable}
    </div>
  `;

  // Cria um iframe temporário para impressão
  var iframe = document.createElement('iframe');
  iframe.style.position = 'absolute';
  iframe.style.width = '0px';
  iframe.style.height = '0px';
  iframe.style.border = 'none';
  document.body.appendChild(iframe);

  // Adiciona o conteúdo HTML ao iframe
  var iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(`
    <html>
      <head>
        <title>Impressão</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            margin: 20px;
          }
          h1 {
            color: #000;
            font-weight: 600; /* Título mais grosso e destacado */
            text-align: center; /* Centraliza o título */
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
          }
          th, td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
          }
          th {
            background-color: #000;
            font-weight: bold;
          }
          tr:nth-child(even) {
            background-color: #000;
          }
        </style>
      </head>
      <body>
        ${printContent}
      </body>
    </html>
  `);
  iframeDocument.close();

  // Dispara a impressão a partir do iframe
  iframe.contentWindow.focus();
  iframe.contentWindow.print();

  // Remove o iframe após a impressão
  iframe.remove();
}
</script>




</body>
</html>
