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
        e.faixa_etaria_id,   
        e.status_social_id,  
        e.status_do_evento_id,
        e.escolaridades_id,   
        GROUP_CONCAT(DISTINCT t.nome) AS tema_nome,  
        GROUP_CONCAT(DISTINCT o.nome) AS objetivo_nome,  
        GROUP_CONCAT(DISTINCT b.nome) AS buffet_nome,
        fe.descricao AS faixa_etaria_descricao,  -- Adicionando a descrição da faixa etária
        ss.descricao AS status_social_descricao,  -- Adicionando a descrição do status social
        sde.nome AS status_do_evento_nome,  -- Adicionando o nome do status do evento
        es.descricao AS escolaridades_descricao  -- Adicionando a descrição da escolaridade
    FROM eventos e
    LEFT JOIN evento_tema et ON e.id = et.evento_id
    LEFT JOIN tema t ON et.tema_id = t.id
    LEFT JOIN evento_objetivo eo ON e.id = eo.evento_id
    LEFT JOIN objetivo o ON eo.objetivo_id = o.id
    LEFT JOIN evento_buffet eb ON e.id = eb.evento_id
    LEFT JOIN buffet b ON eb.buffet_id = b.id
    LEFT JOIN faixa_etaria fe ON e.faixa_etaria_id = fe.id  -- Join para faixa etária
    LEFT JOIN status_social ss ON e.status_social_id = ss.id  -- Join para status social
    LEFT JOIN status_do_evento sde ON e.status_do_evento_id = sde.id  -- Join para status do evento
    LEFT JOIN escolaridades es ON e.escolaridades_id = es.id  -- Join para escolaridade
    GROUP BY e.id  
    LIMIT $offset, $events_per_page
";


// Executa a consulta SQL
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));  // Exibe erro caso a consulta falhe
}

// Consulta para contar o total de eventos
$sql_count = "SELECT COUNT(*) as total FROM eventos";
$result_count = mysqli_query($conn, $sql_count);
if (!$result_count) {
    die("Erro na consulta de contagem: " . mysqli_error($conn));  // Exibe erro caso a consulta de contagem falhe
}
$row_count = mysqli_fetch_assoc($result_count);
$total_events = $row_count['total'];
$total_pages = ceil($total_events / $events_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="lista de event.css">
    <title>SGE - Lista de Eventos</title>
</head>

<body>

    <div id="header"></div> <!-- Div onde o menu será injetado -->

    <script>
        fetch('/menu principal.php')
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
        document.addEventListener('click', function(event) {
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

    <section class="agenda-evento">
        <div class="conteudo-evento">
            <div class="nome-evento">
                <h1>LISTA DE EVENTOS</h1>
            </div>
            <a href="evento.php" class="button">Adicionar Novo Evento</a>
        </div>

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
                <th>Faixa Etária</th>
                <th>Status Social</th>
                <th>Status do Evento</th>
                <th>Escolaridade</th>
                <th class="event-image">Imagem</th>
                <th class="ações">Ações</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
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
                    echo "<td>" . $row['faixa_etaria_descricao'] . "</td>";
                    echo "<td>" . $row['status_social_descricao'] . "</td>";
                    echo "<td>" . $row['status_do_evento_nome'] . "</td>";
                    echo "<td>" . $row['escolaridades_descricao'] . "</td>";


                    $imagemPath = 'uploads/eventos/' . htmlspecialchars($row['imagem']);
                    echo "<td>";
                    if (!empty($imagemPath)) {
                        echo "<img src='" . $imagemPath . "' alt='Imagem do Evento' class='event-image'>";
                    } else {
                        echo "Sem imagem";
                    }

                    echo "<td class='evento-action'>
                    <a class='a' href='edit evento.php?edit=" . $row['id'] . "'>
                    <button class='btn-edit' title='Editar'> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                    <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                    <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/> </svg> </button></a>
                    </a>
                    <button class='btn-delete' title='Deletar' onclick='openDeleteModal(" . $row['id'] . ")'>
        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
            <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
        </svg>
    </button>
</td>";
                    echo "</tr>";
                }
            }
            ?>

        </table>

        <div class="pagination">
            <!-- Links para as páginas -->
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="lista de eventos.php?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        </div>

        <!-- Modal de Confirmação de Exclusão -->
        <div id="deleteModal" class="modal-apagar">
            <div class="modal-content-apagar">
                <h2>Deseja excluir esse evento?</h2>
                <a href="#" id="confirmDeleteLink"><button class="btn-confirm">Sim</button></a>
                <button class="btn-cancel" onclick="closeDeleteModal()">Não</button>
            </div>
        </div>


        <script>
            // Função para abrir o modal de confirmação
            function openDeleteModal(eventId) {
                // Define o link de confirmação de exclusão com o id do evento
                document.getElementById('confirmDeleteLink').href = 'delete evento.php?delete=' + eventId;
                // Exibe o modal
                document.getElementById('deleteModal').style.display = 'block';
            }

            // Função para fechar o modal
            function closeDeleteModal() {
                document.getElementById('deleteModal').style.display = 'none';
            }

            // Fecha o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                if (event.target == document.getElementById('deleteModal')) {
                    closeDeleteModal();
                }
            }
        </script>

    </section>
</body>

</html>