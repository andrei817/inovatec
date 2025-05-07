<?php
// Conexão com o banco de dados
include("php/Config.php");

// Variável para armazenar mensagem de sucesso ou erro
$msg = "";

// Configuração da paginação
$temas_por_pagina = 4;  // Quantos temas serão exibidos por página
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $temas_por_pagina;

// Adicionar novo tema
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar'])) {
    $nome = $conn->real_escape_string($_POST['nome']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $sql = "INSERT INTO tema (nome, descricao) VALUES ('$nome', '$descricao')";

    if ($conn->query($sql) === TRUE) {
        $msg = "Tema adicionado com sucesso!";
    } else {
        $msg = "Erro ao adicionar tema: " . $conn->error;
    }
}

// Excluir tema
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);  // Garantir que o ID seja um número inteiro
    $sql = "DELETE FROM tema WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $msg = "Tema excluído com sucesso!";
        // Redirecionar para recarregar a página após exclusão
        header("Location: tema lista.php");
        exit();
    } else {
        $msg = "Erro ao excluir tema: " . $conn->error;
    }
}

// Buscar temas com paginação
$sql = "SELECT * FROM tema LIMIT $temas_por_pagina OFFSET $offset";
$result = $conn->query($sql);

// Contar o número total de temas
$sql_total = "SELECT COUNT(*) AS total FROM tema";
$total_result = $conn->query($sql_total);
$total_temas = $total_result->fetch_assoc()['total'];
$total_paginas = ceil($total_temas / $temas_por_pagina);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="ico/SGE.ico" type="image/x-icon">
    <link rel="stylesheet" href="tema lista.css">
    <title>Gerenciar Temas</title>
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

    <section class="tema-evento">
        <div class="conteudo">
            <div class="nome-tema">
                <h1>TEMAS</h1>
            </div>
            <a href="tema cadastro.php" class="button">Adicionar Novo Tema</a>
        </div>



        <?php if ($msg != "") : ?>
            <div class="msg"><?= $msg ?></div> <!-- Exibe a mensagem de sucesso ou erro -->
        <?php endif; ?>

        <table>
            <tr>
                <th class="id-column">ID</th>
                <th>Nome do Tema</th>
                <th>Descrição</th>
                <th class="ações">Ações</th>
            </tr>
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td class="id-column"><?= $row['id'] ?></td>
                        <td><?= $row['nome'] ?></td>
                        <td><?= $row['descricao'] ?></td>
                        <td class="action">
                            <a href="editar tema.php?id=<?= $row['id'] ?>">
                                <button class='btn-edit' title="Editar">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                                        <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
                                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
                                    </svg>
                                </button>
                            </a>
                            <a title="Deletar" href="javascript:void(0);" onclick="openDeleteModal(<?= $row['id'] ?>)">
                                <button class="delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                    </svg>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Nenhum tema encontrado.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Modal de confirmação de exclusão -->
        <div id="deleteModal" class="modal-delete">
            <div class="modal-content-delete">
                <h2>Deseja excluir esse tema?</h2>

                <div class="modal-actions">
                    <button id="confirmDelete" class="confirm-btn">Sim</button>
                    <button id="cancelDelete" class="cancel-btn">Não</button>
                </div>
            </div>
        </div>

        <script>
            let deleteModal = document.getElementById("deleteModal");
            let confirmDeleteBtn = document.getElementById("confirmDelete");
            let cancelDeleteBtn = document.getElementById("cancelDelete");

            // Função para abrir o modal
            function openDeleteModal(id) {
                deleteModal.style.display = "flex";

                // Armazenar o ID do tema a ser excluído
                confirmDeleteBtn.onclick = function() {
                    window.location.href = "delete tema.php?excluir=" + id;
                }
            }

            // Função para fechar o modal
            cancelDeleteBtn.onclick = function() {
                deleteModal.style.display = "none";
            }

            // Fechar o modal se o usuário clicar fora da área do modal
            window.onclick = function(event) {
                if (event.target === deleteModal) {
                    deleteModal.style.display = "none";
                }
            }
        </script>


        <!-- Modal -->
        <div id="modalErro" style="display: none;">
            <div class="modal-content-associado">
                <span id="modalFechar" style="cursor: pointer;">&times;</span>
                <p>Este tema está associado a eventos e não pode ser excluído.</p>
            </div>
        </div>

        <div id="modalErro2" style="display: none;">
            <div class="modal-content-associado">
                <span id="modalFechar2" style="cursor: pointer;">&times;</span>
                <p>Este tema está associado a eventos e não pode ser excluído.</p>
            </div>
        </div>

        <!-- Estilos para o modal -->
        <style>
            #modalErro {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            #modalErro2 {
            display: none; /* por padrão invisível */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* fundo semi-transparente */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            }

            .modal-content-associado {
                background-color: #5214cb;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
                position: relative;
                width: 300px;
                /* Ajuste o tamanho conforme necessário */
            }

            #modalFechar {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 30px;
                color: white;
                cursor: pointer;
            }

            #modalFechar2 {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    font-weight: bold;
    color: white;
    transition: color 0.3s ease;
}
        </style>

        <!-- Verificação do erro via URL -->
        <script>
           window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            // Erro 1: Tema vinculado diretamente
            if (urlParams.get('erro') === '1') {
                document.getElementById('modalErro').style.display = 'flex';
            }

            // Erro 2: Tema vinculado via tabela auxiliar
            if (urlParams.get('erro') === '2') {
                document.getElementById('modalErro2').style.display = 'flex';
            }

            // Fechar modais
            document.getElementById('modalFechar')?.addEventListener('click', function() {
                document.getElementById('modalErro').style.display = 'none';
            });

            document.getElementById('modalFechar2')?.addEventListener('click', function() {
                document.getElementById('modalErro2').style.display = 'none';
            });

            // Remove os parâmetros da URL
            if (urlParams.has('erro')) {
                urlParams.delete('erro');
                window.history.replaceState({}, '', window.location.pathname + '?' + urlParams.toString());
            }
        };

        </script>

        <!-- Paginação -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                <a href="?pagina=<?= $i ?>" class="<?= ($pagina_atual == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

    </section>


</body>

</html>

<?php
// Fechar conexão
$conn->close();
?>