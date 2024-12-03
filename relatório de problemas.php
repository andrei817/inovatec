<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <title>Relatório de Problemas por Eventos</title>
    
    <link rel="stylesheet" href="relatório de probelmas.css">
    <link rel="stylesheet" href="print-relatório.css">
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


<div class="content">
  <!-- Conteúdo da página -->
</div>


<section class="agenda-relatorio">
    <div class="conteudo-relatorio">
        <h1>Problemas por Eventos</h1>
        <a href="reporte de problemas.php" class="button no-print">Reportar problemas</a>
    </div>

    <table>
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th>Evento</th>
                <th class="problem">Problema</th>
                <th>Data do Registro</th>
                <th>Contato</th> <!-- Nova coluna -->
                <th class="no-print">Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Conexão com o banco de dados
        include("php/Config.php");

        // Defina o número de registros por página
        $registros_por_pagina = 3;

        // Verifique a página atual
        $pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $offset = ($pagina_atual - 1) * $registros_por_pagina;

        // Consulta para contar o número total de registros
        $total_registros_query = "SELECT COUNT(*) as total FROM problemas_evento";
        $total_resultado = $conn->query($total_registros_query);
        $total_registros = $total_resultado->fetch_assoc()['total'];

        // Calcular o total de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        // Consulta SQL com LIMIT para limitar os registros por página
        $sql = "SELECT p.evento_id, p.descricao_problema, p.data_evento, p.contato, e.nome AS nome_evento
                FROM problemas_evento p
                JOIN eventos e ON p.evento_id = e.id
                LIMIT $offset, $registros_por_pagina";

        $resultado = $conn->query($sql);

        // Verificar se a consulta foi bem-sucedida
        if ($resultado === false) {
            echo "Erro na consulta: " . $conn->error;
        } else {
            if ($resultado->num_rows > 0) {
                // Exibir os resultados
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr id='row-" . $row['evento_id'] . "'>";
                    echo "<td class='id-column'>" . $row['evento_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome_evento']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao_problema']) . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['data_evento'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contato']) . "</td>";
                    echo "<td class='no-print action'>
                            <button class='no-print print-button' onclick='print()'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-printer' viewBox='0 0 16 16'>
                                    <path d='M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1'/>
                                    <path d='M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1'/>
                                </svg>
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum problema registrado.</td></tr>";
            }
        }
        ?>
        </tbody>
    </table>

    <!-- Links de navegação para paginação -->
    <div class="no-print pagination">
        <?php
        // Exibir os links de navegação
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i'";
            if ($pagina_atual == $i) echo " class='active'";
            echo ">$i</a> ";
        }
        ?>
    </div>

</section>

</body>
</html>
