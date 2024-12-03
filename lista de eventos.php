<?php
include("php/Config.php");

// Número de eventos por página
$events_per_page = 3;

// Determina a página atual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $events_per_page;

// Consulta SQL para buscar eventos e dados relacionados, com limite e offset
$sql = "
    SELECT 
        e.id, 
        e.nome, 
        e.data, 
        e.local, 
        e.hora, 
        e.lotacao, 
        e.duracao, 
        e.descricao, 
        e.imagem,  
        t.nome AS tema_nome, 
        o.nome AS objetivo_nome, 
        b.nome AS buffet_nome
    FROM eventos e
    LEFT JOIN tema t ON e.tema_id = t.id
    LEFT JOIN objetivo o ON e.objetivo_id = o.id
    LEFT JOIN buffet b ON e.buffet_id = b.id
    LIMIT $offset, $events_per_page
";
$result = mysqli_query($conn, $sql);

// Consulta para contar o total de eventos
$sql_count = "SELECT COUNT(*) as total FROM eventos";
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_events = $row_count['total'];
$total_pages = ceil($total_events / $events_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="lista de event.css">
    <title>SGE - Lista de Eventos</title>
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


<section class="agenda-evento">
<div class="conteudo">
    
        <h2>LISTA DE EVENTOS</h2>
        <a href="evento.php" class="button">Adicionar Novo Evento</a>

        <div class="scroll-box">
            <table>
                <tr>
                    <th class="id-column">ID</th>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>Local</th>
                    <th>Hora</th>
                    <th>Lotação</th>
                    <th>Duração</th>
                    <th>Descrição</th>
                    <th>Tema</th>
                    <th>Objetivo</th>
                    <th>Buffet</th>
                    <th>Imagem</th>
                    <th class="ações">Ações</th>
                </tr>

                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='id-column'>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nome'] . "</td>";
                        echo "<td>" . $row['data'] . "</td>";
                        echo "<td>" . $row['local'] . "</td>";
                        echo "<td>" . $row['hora'] . "</td>";
                        echo "<td>" . $row['lotacao'] . "</td>";
                        echo "<td>" . $row['duracao'] . "</td>";
                        echo "<td>" . $row['descricao'] . "</td>";
                        echo "<td>" . $row['tema_nome'] . "</td>";
                        echo "<td>" . $row['objetivo_nome'] . "</td>";
                        echo "<td>" . $row['buffet_nome'] . "</td>";

                        $imagemPath = 'uploads/eventos/' . htmlspecialchars($row['imagem']);
                        echo "<td>";
                        if (!empty($imagemPath)) {
                            echo "<img src='" . $imagemPath . "' alt='Imagem do Evento' style='width: 100px; height: auto;'>";
                        } else {
                            echo "Sem imagem";
                        }

                        echo "<td class='action'>
                                <a href='edit evento.php?edit=" . $row['id'] . "'>
                                <button class='btn-edit'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                                <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                                <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/> </a> </svg> </button></a>
                                </a>
                                <a href='delete evento.php?delete=" . $row['id'] . "'>
                                <button class='btn-delete'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                              </svg> </a> </button></a>
                                </a>
                              </td>"; 
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>Nenhum evento cadastrado.</td></tr>";
                }
                ?>
            </table>
        </div>

        <div class="pagination">
    <!-- Links para as páginas -->
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="lista de eventos.php?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

    </div>
</body>
</html>
